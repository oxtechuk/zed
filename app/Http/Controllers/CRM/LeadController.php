<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Jobs\SendWhatsAppCampaignMessage;
use App\Models\Booking;
use App\Models\Car;
use App\Models\ContactSource;
use App\Models\Employee;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeadController extends Controller
{
    public const ACTIVE_BOOKING_STATUSES = [
        'new', 'contacted_no_answer', 'recontact_client', 'waiting_documents',
        'bank_review', 'approved', 'authorized', 'pending', 'waiting_supervisor_approval',
    ];

    public const RECEIVED_BOOKING_STATUSES = [
        'received', 'sold', 'done',
    ];

    public const CLOSED_BOOKING_STATUSES = [
        'lost_no_answer', 'lost_no_response', 'lost_wrong_info', 'lost_offer_not_suitable',
        'lost_client_cancelled', 'lost_cancelled_after_approval', 'lost_rejected_high_liabilities',
        'lost_rejected_simah', 'lost_rejected_finance_terms', 'rejected', 'lost',
    ];

    public function index(Request $request)
    {
        $isAdmin = auth('employee')->user()?->isAdmin() ?? true;
        $activeStatuses = ['new', 'contacted_no_answer', 'recontact_client', 'waiting_documents', 'bank_review', 'approved', 'authorized'];

        $query = Lead::with(['contactSource', 'car.brand', 'employee', 'orders.car.brand'])
            ->withCount('orders');

        $this->applyFilters($query, $request, $isAdmin);

        $leads = $query->paginate(20)->withQueryString();
        $statuses = collect(Booking::STATUSES)->only($activeStatuses)->toArray();
        $leadStatuses = Lead::STATUSES;
        $bookingStatuses = Booking::STATUSES;
        $sources = ContactSource::activeOrdered()->get();
        $employees = Employee::where('is_active', true)->orderBy('name')->get();

        // Calculate dynamic stats for Booking Status Groups & Total
        $totalLeadsAllCount = Lead::count();
        $activeOrdersLeadsCount = Lead::whereHas('orders', fn ($q) => $q->whereIn('status', self::ACTIVE_BOOKING_STATUSES))->count();
        $receivedOrdersLeadsCount = Lead::whereHas('orders', fn ($q) => $q->whereIn('status', self::RECEIVED_BOOKING_STATUSES))->count();
        $closedOrdersLeadsCount = Lead::whereHas('orders', fn ($q) => $q->whereIn('status', self::CLOSED_BOOKING_STATUSES)->orWhere('status', 'like', 'lost_%'))->count();
        $noOrdersLeadsCount = Lead::whereDoesntHave('orders')->count();

        // Lead Statuses stats
        $activeLeadsCount = Lead::whereIn('status', ['new', 'contacted', 'interested', 'negotiation'])->count();
        $newLeadsCount = Lead::where('status', 'new')->count();
        $activeLeadsRatio = $totalLeadsAllCount > 0 ? round(($activeLeadsCount / $totalLeadsAllCount) * 100) : 0;
        $newLeadsRatio = $totalLeadsAllCount > 0 ? round(($newLeadsCount / $totalLeadsAllCount) * 100) : 0;

        return view('crm.leads.index', compact(
            'leads',
            'statuses',
            'leadStatuses',
            'bookingStatuses',
            'sources',
            'employees',
            'isAdmin',
            'totalLeadsAllCount',
            'activeOrdersLeadsCount',
            'receivedOrdersLeadsCount',
            'closedOrdersLeadsCount',
            'noOrdersLeadsCount',
            'activeLeadsCount',
            'newLeadsCount',
            'activeLeadsRatio',
            'newLeadsRatio'
        ));
    }

    private function applyFilters($query, Request $request, bool $isAdmin = true): void
    {
        // 1. Employee Filter
        if (! $isAdmin) {
            $query->where('assigned_to', auth()->id());
        } elseif ($request->filled('employee_id')) {
            $query->where('assigned_to', $request->employee_id);
        }

        // 2. Month Filter
        if ($request->filled('month')) {
            $parts = explode('-', $request->month);
            if (count($parts) === 2) {
                $query->whereYear('created_at', $parts[0])->whereMonth('created_at', $parts[1]);
            }
        } elseif ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // 3. Source & Type Filter
        if ($request->filled('source')) {
            if ($request->source === 'calculator') {
                $query->where(function ($q) {
                    $q->whereHas('orders', function ($oq) {
                        $oq->where('source', 'calculator')->orWhereNotNull('calculator_bank_id');
                    })->orWhereHas('contactSource', function ($sq) {
                        $sq->where('name', 'like', '%حاسبة%')->orWhere('name', 'like', '%calculator%');
                    });
                });
            } elseif ($request->source === 'cars' || $request->source === 'booking') {
                $query->where(function ($q) {
                    $q->whereHas('orders', function ($oq) {
                        $oq->where('source', '!=', 'calculator')->orWhereNull('source');
                    })->orWhereNotNull('car_id');
                });
            } elseif ($request->source === 'crm_manual') {
                $query->where(function ($q) {
                    $q->where('subject', 'like', '%CRM%')
                        ->orWhereHas('orders', function ($oq) {
                            $oq->where('source', 'like', '%CRM%');
                        });
                });
            }
        }

        // 4. Status Filter (Active / Booking / Lead Status)
        if ($request->filled('status')) {
            $status = $request->status;
            $query->where(function ($q) use ($status) {
                $q->where('status', $status)
                    ->orWhereHas('orders', function ($oq) use ($status) {
                        $oq->where('status', $status);
                    });
            });
        }

        // 5. Contact Source Filter
        if ($request->filled('contact_source_id')) {
            $query->where('contact_source_id', $request->contact_source_id);
        }

        // 6. Booking Status Group Tabs
        if ($request->filled('booking_status_group')) {
            $group = $request->booking_status_group;
            if ($group === 'active') {
                $query->whereHas('orders', fn ($q) => $q->whereIn('status', self::ACTIVE_BOOKING_STATUSES));
            } elseif ($group === 'received') {
                $query->whereHas('orders', fn ($q) => $q->whereIn('status', self::RECEIVED_BOOKING_STATUSES));
            } elseif ($group === 'closed' || $group === 'lost') {
                $query->whereHas('orders', fn ($q) => $q->whereIn('status', self::CLOSED_BOOKING_STATUSES)->orWhere('status', 'like', 'lost_%'));
            } elseif ($group === 'no_orders') {
                $query->whereDoesntHave('orders');
            }
        }

        if ($request->filled('booking_status')) {
            $bStatus = $request->booking_status;
            $query->whereHas('orders', fn ($q) => $q->where('status', $bStatus));
        }

        // 7. Search
        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('client_name', 'like', "%{$s}%")
                    ->orWhere('client_phone', 'like', "%{$s}%")
                    ->orWhere('client_email', 'like', "%{$s}%");
            });
        }

        // 8. Sorting
        $sort = $request->input('sort', 'newest');
        if ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }
    }

    public function create()
    {
        $statuses = Lead::STATUSES;
        $sources = ContactSource::activeOrdered()->get();
        $cars = Car::with('brand')->where('is_active', true)->orderByDesc('id')->get();
        $employees = Employee::where('is_active', true)->orderBy('name')->get();

        return view('crm.leads.create', compact('statuses', 'sources', 'cars', 'employees'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        Lead::create($data);

        return redirect()->route('crm.leads.index')->with('success', __('تم إضافة العميل بنجاح'));
    }

    public function show(Lead $lead)
    {
        $lead->load(['contactSource', 'car.brand', 'employee', 'orders.car.brand']);

        return view('crm.leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $statuses = Lead::STATUSES;
        $sources = ContactSource::activeOrdered()->get();
        $cars = Car::with('brand')->where('is_active', true)->orderByDesc('id')->get();
        $employees = Employee::where('is_active', true)->orderBy('name')->get();

        return view('crm.leads.edit', compact('lead', 'statuses', 'sources', 'cars', 'employees'));
    }

    public function update(Request $request, Lead $lead)
    {
        $data = $this->validated($request);
        $lead->update($data);

        return redirect()->route('crm.leads.show', $lead)->with('success', __('تم تحديث بيانات العميل'));
    }

    public function destroy(Lead $lead)
    {
        if (! auth('employee')->user()->isAdmin()) {
            abort(403, __('غير مصرح لك بحذف سجلات العملاء. صلاحية الحذف مقتصرة على المشرف/المدير فقط.'));
        }

        $lead->delete();

        return redirect()->route('crm.leads.index')->with('success', __('تم حذف السجل'));
    }

    public function whatsappCampaign(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        if ($request->boolean('target_all_filtered')) {
            $query = Lead::query();
            $this->applyFilters($query, $request);
            $leads = $query->whereNotNull('client_phone')->where('client_phone', '!=', '')->get();
        } else {
            $request->validate([
                'lead_ids' => 'required|array|min:1',
                'lead_ids.*' => 'integer|exists:leads,id',
            ]);

            $leads = Lead::whereIn('id', $request->lead_ids)
                ->whereNotNull('client_phone')
                ->where('client_phone', '!=', '')
                ->get();
        }

        if ($leads->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => __('لا يوجد عملاء بأرقام هواتف صالحة للإرسال'),
            ], 422);
        }

        $delay = 0;
        foreach ($leads as $lead) {
            SendWhatsAppCampaignMessage::dispatch($lead->id, $request->message)
                ->delay(now()->addSeconds($delay));
            $delay += 1;
        }

        return response()->json([
            'success' => true,
            'total' => $leads->count(),
            'message' => __('تم جدولة إرسال :count رسالة واتساب بنجاح', ['count' => $leads->count()]),
        ]);
    }

    private function validated(Request $request): array
    {
        $statuses = array_keys(Lead::STATUSES);

        $data = $request->validate([
            'client_name' => 'required|string|max:200',
            'client_phone' => 'nullable|string|max:40',
            'client_email' => 'nullable|email|max:200',
            'contact_source_id' => 'required|exists:contact_sources,id',
            'status' => ['required', Rule::in($statuses)],
            'started_at' => 'required|date',
            'status_details' => 'nullable|string|max:5000',
            'car_id' => 'nullable|exists:cars,id',
            'assigned_to' => 'nullable|exists:employees,id',
        ]);

        return $data;
    }
}
