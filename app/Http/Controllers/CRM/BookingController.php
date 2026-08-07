<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDocument;
use App\Models\BookingNote;
use App\Models\CalculatorBank;
use App\Models\Car;
use App\Models\Employee;
use App\Models\Setting;
use App\Notifications\NewBookingNotification;
use App\Services\TwilioWhatsAppService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    public function index(Request $request)
    {

        $query = Booking::with(['car.brand', 'employee'])->latest();

        // فلترة بالحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة بالموظف
        if ($request->filled('employee_id')) {
            $query->where('assigned_to', $request->employee_id);
        }
        if (! auth()->user()->hasRole('super-admin')) {
            $query->where('assigned_to', \auth()->id());
        }
        // بحث
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('client_name', 'like', "%$s%")
                    ->orWhere('client_phone', 'like', "%$s%");
            });
        }

        $bookings = $query->paginate(20);
        $employees = Employee::where('is_active', true)->get();
        $statuses = Booking::STATUSES;
        $cars = Car::with('brand')->where('is_active', true)->get();

        return view('crm.bookings.index', compact('bookings', 'employees', 'statuses', 'cars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:191',
            'client_phone' => 'required|string|max:191',
            'client_email' => 'nullable|email|max:191',
            'car_id' => 'nullable|exists:cars,id',
            'total_price' => 'nullable|numeric',
            'down_payment' => 'nullable|numeric',
            'duration_years' => 'nullable|integer',
            'monthly_installment' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'type' => 'nullable|string',
            'booking_type' => 'nullable|string|in:test_drive,purchase,inquiry',
            'location' => 'nullable|string|max:500',
        ]);

        $booking = Booking::create([
            'client_name' => $request->client_name,
            'client_phone' => $request->client_phone,
            'client_email' => $request->client_email,
            'car_id' => $request->car_id,
            'total_price' => $request->total_price ?? 0,
            'down_payment' => $request->down_payment ?? 0,
            'duration_years' => $request->duration_years ?? 5,
            'monthly_installment' => $request->monthly_installment ?? 0,
            'notes' => $request->notes,
            'booking_type' => $request->booking_type,
            'location' => $request->location,
            'source' => 'CRM (يدوي)',
            'status' => 'new',
            'assigned_to' => auth('employee')->id(), // assign to the creator by default
        ]);

        return back()->with('success', 'تم إنشاء الطلب بنجاح');
    }

    public function show(Booking $booking)
    {
        $booking->load(['car.brand', 'employee', 'financingBank', 'notes_list.employee', 'documents.employee', 'tasks.assignedTo']);
        $employees = Employee::where('is_active', true)->get();
        $statuses = Booking::STATUSES;
        $calculatorBanks = CalculatorBank::activeOrdered()->get();

        return view('crm.bookings.show', compact('booking', 'employees', 'statuses', 'calculatorBanks'));
    }

    public function updateOffer(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'calculator_bank_id' => 'nullable|exists:calculator_banks,id',
            'balloon_payment' => 'nullable|numeric|min:0',
            'offer_notes' => 'nullable|string|max:2000',
        ]);

        $booking->update($data);

        return back()->with('success', 'تم تحديث تفاصيل العرض بنجاح');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate(['status' => 'required|in:'.implode(',', array_keys(Booking::STATUSES))]);

        $targetStatus = $request->status;
        $oldStatus = $booking->status;
        $employee = auth('employee')->user();

        // Check if the current status is supervisor approval and user is not admin
        if ($oldStatus === 'waiting_supervisor_approval' && ! $employee->isAdmin()) {
            return back()->with('error', 'لا يمكن تعديل حالة الطلب وهو بانتظار اعتماد المشرف.');
        }

        // If target status is pending, enforce validation
        if ($targetStatus === 'pending') {
            $request->validate([
                'pending_reason' => 'required|string|max:500',
                'follow_up_at' => 'required|date|after:now',
                'note' => 'required|string|max:2000',
            ], [
                'pending_reason.required' => 'يرجى إدخال سبب الانتظار.',
                'follow_up_at.required' => 'يرجى تحديد وقت وتاريخ إعادة المتابعة.',
                'follow_up_at.after' => 'يجب أن يكون وقت المتابعة في المستقبل.',
                'note.required' => 'يرجى كتابة ملاحظة توضيحية.',
            ]);

            $booking->update([
                'status' => 'pending',
                'pending_reason' => $request->pending_reason,
                'follow_up_at' => $request->follow_up_at,
                'proposed_status' => null,
                'last_contacted_at' => now(),
            ]);

            BookingNote::create([
                'booking_id' => $booking->id,
                'employee_id' => $employee->id,
                'note' => "تفاصيل قيد الانتظار:\n- سبب الانتظار: {$request->pending_reason}\n- موعد المتابعة: ".Carbon::parse($request->follow_up_at)->format('d/m/Y H:i')."\n- ملاحظة: {$request->note}",
                'type' => 'status_change',
                'old_status' => $oldStatus,
                'new_status' => 'pending',
            ]);

            return back()->with('success', 'تم نقل الطلب إلى "قيد الانتظار" بنجاح.');
        }

        $isClosingStatus = Booking::STATUSES[$targetStatus]['is_close'] ?? false;

        // If target status is a closing status
        if ($isClosingStatus) {
            if (! $employee->isAdmin()) {
                // Not a supervisor: route to supervisor approval
                $booking->update([
                    'status' => 'waiting_supervisor_approval',
                    'proposed_status' => $targetStatus,
                    'last_contacted_at' => now(),
                ]);

                $noteText = 'طلب إغلاق الحجز بـ "'.(Booking::STATUSES[$targetStatus]['label'] ?? $targetStatus).'" بانتظار اعتماد المشرف.';
                if ($request->filled('note')) {
                    $noteText .= "\nملاحظة الموظف: ".$request->note;
                }

                BookingNote::create([
                    'booking_id' => $booking->id,
                    'employee_id' => $employee->id,
                    'note' => $noteText,
                    'type' => 'status_change',
                    'old_status' => $oldStatus,
                    'new_status' => 'waiting_supervisor_approval',
                ]);

                return back()->with('success', 'تم إرسال طلب إغلاق الحجز للمشرف للاعتماد.');
            } else {
                // Supervisor can close directly
                $booking->update([
                    'status' => $targetStatus,
                    'proposed_status' => null,
                    'last_contacted_at' => now(),
                ]);

                $noteText = 'تم إغلاق الحجز مباشرة من قبل المشرف إلى "'.(Booking::STATUSES[$targetStatus]['label'] ?? $targetStatus).'".';
                if ($request->filled('note')) {
                    $noteText .= "\nملاحظة المشرف: ".$request->note;
                }

                BookingNote::create([
                    'booking_id' => $booking->id,
                    'employee_id' => $employee->id,
                    'note' => $noteText,
                    'type' => 'status_change',
                    'old_status' => $oldStatus,
                    'new_status' => $targetStatus,
                ]);

                return back()->with('success', 'تم إغلاق الحجز بنجاح.');
            }
        }

        // Normal active status update
        $booking->update([
            'status' => $targetStatus,
            'proposed_status' => null,
            'pending_reason' => null,
            'follow_up_at' => null,
            'last_contacted_at' => now(),
        ]);

        BookingNote::create([
            'booking_id' => $booking->id,
            'employee_id' => $employee->id,
            'note' => 'تم تغيير الحالة من "'.(Booking::STATUSES[$oldStatus]['label'] ?? $oldStatus).'" إلى "'.(Booking::STATUSES[$targetStatus]['label'] ?? $targetStatus).'"',
            'type' => 'status_change',
            'old_status' => $oldStatus,
            'new_status' => $targetStatus,
        ]);

        if ($booking->assignedTo && $booking->assignedTo->id !== $employee->id) {
            $booking->assignedTo->notify(new NewBookingNotification($booking, __('تحديث حالة الطلب'), __('تم تغيير حالة طلب العميل').' '.$booking->client_name.' '.__('إلى').' '.(Booking::STATUSES[$targetStatus]['label'] ?? $targetStatus)));
        }

        // Send WhatsApp if configured
        $settings = Setting::whereIn('key', ['whatsapp_template_status_update'])->pluck('value', 'key');
        $template = $settings['whatsapp_template_status_update'] ?? '';

        if (! empty($template) && ! empty($booking->client_phone)) {
            $message = str_replace(
                ['{customer_name}', '{car_name}', '{status}'],
                [$booking->client_name, $booking->car?->name ?? 'السيارة', Booking::STATUSES[$targetStatus]['label'] ?? $targetStatus],
                $template
            );
            $twilioService = app(TwilioWhatsAppService::class);
            $twilioService->sendWhatsApp($booking->client_phone, $message);
        }

        return back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }

    public function approveStatus(Booking $booking)
    {
        $employee = auth('employee')->user();
        if (! $employee->isAdmin()) {
            abort(403, 'غير مصرح لك بزيارة هذه الصفحة');
        }

        if ($booking->status !== 'waiting_supervisor_approval' || empty($booking->proposed_status)) {
            return back()->with('error', 'الطلب ليس بانتظار اعتماد الإغلاق.');
        }

        $oldStatus = $booking->status;
        $targetStatus = $booking->proposed_status;

        $booking->update([
            'status' => $targetStatus,
            'proposed_status' => null,
            'last_contacted_at' => now(),
        ]);

        BookingNote::create([
            'booking_id' => $booking->id,
            'employee_id' => $employee->id,
            'note' => 'تم اعتماد إغلاق الطلب بـ "'.(Booking::STATUSES[$targetStatus]['label'] ?? $targetStatus).'" من قبل المشرف: '.$employee->name,
            'type' => 'status_change',
            'old_status' => $oldStatus,
            'new_status' => $targetStatus,
        ]);

        if ($booking->assignedTo) {
            $booking->assignedTo->notify(new NewBookingNotification($booking, __('اعتماد إغلاق الطلب'), __('تم اعتماد إغلاق طلب العميل').' '.$booking->client_name.' '.__('كـ').' '.(Booking::STATUSES[$targetStatus]['label'] ?? $targetStatus)));
        }

        return back()->with('success', 'تم اعتماد إغلاق الطلب بنجاح.');
    }

    public function rejectStatus(Request $request, Booking $booking)
    {
        $employee = auth('employee')->user();
        if (! $employee->isAdmin()) {
            abort(403, 'غير مصرح لك بزيارة هذه الصفحة');
        }

        if ($booking->status !== 'waiting_supervisor_approval' || empty($booking->proposed_status)) {
            return back()->with('error', 'الطلب ليس بانتظار اعتماد الإغلاق.');
        }

        $activeStatuses = array_keys(array_filter(Booking::STATUSES, fn ($s) => ($s['group'] === 'active' && ($s['is_close'] ?? false) === false)));

        $request->validate([
            'status' => 'required|in:'.implode(',', $activeStatuses),
            'note' => 'required|string|max:2000',
        ], [
            'status.required' => 'يرجى اختيار المرحلة المراد إعادة الطلب إليها.',
            'note.required' => 'يرجى كتابة سبب رفض الإغلاق / إعادة الطلب.',
        ]);

        $oldStatus = $booking->status;
        $targetStatus = $request->status;

        $booking->update([
            'status' => $targetStatus,
            'proposed_status' => null,
            'last_contacted_at' => now(),
        ]);

        BookingNote::create([
            'booking_id' => $booking->id,
            'employee_id' => $employee->id,
            'note' => 'تم رفض إغلاق الطلب وإعادته لمرحلة "'.(Booking::STATUSES[$targetStatus]['label'] ?? $targetStatus)."\" من قبل المشرف.\nالسبب: ".$request->note,
            'type' => 'status_change',
            'old_status' => $oldStatus,
            'new_status' => $targetStatus,
        ]);

        if ($booking->assignedTo) {
            $booking->assignedTo->notify(new NewBookingNotification($booking, __('رفض إغلاق الطلب وإعادته لمرحلة أخرى'), __('تم رفض طلب إغلاق العميل').' '.$booking->client_name.' '.__('وإعادته لمرحلة').' '.(Booking::STATUSES[$targetStatus]['label'] ?? $targetStatus)));
        }

        return back()->with('success', 'تم رفض الإغلاق وإعادة الطلب لمرحلة '.(Booking::STATUSES[$targetStatus]['label'] ?? $targetStatus).' بنجاح.');
    }

    public function assign(Request $request, Booking $booking)
    {
        $request->validate(['employee_id' => 'required|exists:employees,id']);
        $booking->update(['assigned_to' => $request->employee_id]);

        // Notify the assigned employee
        $employee = Employee::find($request->employee_id);
        if ($employee) {
            $employee->notify(new NewBookingNotification($booking, __('طلب جديد'), __('تم تعيين طلب جديد لك للعميل').' '.$booking->client_name));
        }

        return back()->with('success', 'تم توزيع الطلب على الموظف');
    }

    public function addNote(Request $request, Booking $booking)
    {
        $request->validate(['note' => 'required|string|max:2000', 'type' => 'in:note,call']);
        BookingNote::create([
            'booking_id' => $booking->id,
            'employee_id' => auth('employee')->id(),
            'note' => $request->note,
            'type' => $request->type ?? 'note',
        ]);

        return back()->with('success', 'تمت إضافة الملاحظة');
    }

    public function uploadDocument(Request $request, Booking $booking)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        $file = $request->file('file');
        $path = $file->store('booking_documents', 'public');

        $booking->documents()->create([
            'employee_id' => auth('employee')->id(),
            'title' => $request->title ?? $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
        ]);

        return back()->with('success', 'تم رفع المستند بنجاح');
    }

    public function deleteDocument(BookingDocument $document)
    {
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }
        $document->delete();

        return back()->with('success', 'تم حذف المستند بنجاح');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('crm.bookings.index')->with('success', 'تم حذف الطلب');
    }
}
