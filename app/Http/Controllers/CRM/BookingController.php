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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    /**
     * Active bookings list (مسار المبيعات النشط)
     */
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'newest');
        $isAdmin = auth('employee')->user()->isAdmin();

        // Active pipeline statuses (excludes pending, received, lost statuses)
        $activeStatuses = ['new', 'contacted_no_answer', 'recontact_client', 'waiting_documents', 'bank_review', 'approved', 'authorized'];

        // Base scoped query (per employee or filtered for admin)
        $scopedQuery = Booking::query()->whereIn('status', $activeStatuses);
        if (! $isAdmin) {
            $scopedQuery->where('assigned_to', auth()->id());
        } elseif ($request->filled('employee_id')) {
            $scopedQuery->where('assigned_to', $request->employee_id);
        }

        // Apply month/date filter to stats if provided
        if ($request->filled('month')) {
            $parts = explode('-', $request->month);
            if (count($parts) === 2) {
                $scopedQuery->whereYear('created_at', $parts[0])->whereMonth('created_at', $parts[1]);
            }
        } elseif ($request->filled('date')) {
            $scopedQuery->whereDate('created_at', $request->date);
        }

        $stats = [
            'total' => (clone $scopedQuery)->count(),
            'car_requests' => (clone $scopedQuery)->where(function ($q) {
                $q->where('source', '!=', 'calculator')->orWhereNull('source');
            })->count(),
            'calculator_leads' => (clone $scopedQuery)->where(function ($q) {
                $q->where('source', 'calculator')->orWhereNotNull('calculator_bank_id');
            })->count(),
            'pending_review' => (clone $scopedQuery)->whereIn('status', ['new', 'contacted_no_answer', 'recontact_client'])->count(),
            'under_bank' => (clone $scopedQuery)->whereIn('status', ['waiting_documents', 'bank_review', 'approved', 'authorized'])->count(),
        ];

        // Pending supervisor approvals (admin only)
        $pendingApprovals = $isAdmin
            ? Booking::with(['car.brand', 'employee'])
                ->where('status', 'waiting_supervisor_approval')
                ->latest()
                ->get()
            : collect();

        $query = Booking::with(['car.brand', 'employee'])->whereIn('status', $activeStatuses);

        if (! $isAdmin) {
            $query->where('assigned_to', auth()->id());
        } elseif ($request->filled('employee_id')) {
            $query->where('assigned_to', $request->employee_id);
        }

        // Month / Date filter
        if ($request->filled('month')) {
            $parts = explode('-', $request->month);
            if (count($parts) === 2) {
                $query->whereYear('created_at', $parts[0])->whereMonth('created_at', $parts[1]);
            }
        } elseif ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Source / Type filter
        if ($request->filled('source')) {
            if ($request->source === 'calculator') {
                $query->where(function ($q) {
                    $q->where('source', 'calculator')
                        ->orWhereNotNull('calculator_bank_id');
                });
            } elseif ($request->source === 'cars' || $request->source === 'booking') {
                $query->where(function ($q) {
                    $q->where('source', '!=', 'calculator')
                        ->orWhereNull('source');
                });
            } elseif ($request->source === 'test_drive') {
                $query->where('booking_type', 'test_drive');
            } elseif ($request->source === 'purchase') {
                $query->where('booking_type', 'purchase');
            } elseif ($request->source === 'crm_manual') {
                $query->where('source', 'like', '%CRM%');
            }
        }

        // Status filter (within active statuses)
        if ($request->filled('status') && in_array($request->status, $activeStatuses)) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('client_name', 'like', "%$s%")
                    ->orWhere('client_phone', 'like', "%$s%");
            });
        }

        // Sort
        if ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $bookings = $query->paginate(20)->withQueryString();
        $employees = Employee::where('is_active', true)->get();
        $statuses = collect(Booking::STATUSES)->only($activeStatuses)->toArray();
        $cars = Car::with('brand')->where('is_active', true)->get();

        return view('crm.bookings.index', compact(
            'bookings', 'employees', 'statuses', 'cars', 'stats', 'pendingApprovals', 'isAdmin'
        ));
    }

    /**
     * Pending bookings list (طلبات قيد الانتظار)
     */
    public function pendingIndex(Request $request)
    {
        $sort = $request->input('sort', 'nearest_follow_up');
        $isAdmin = auth('employee')->user()->isAdmin();

        $scopedQuery = Booking::query()->where('status', 'pending');
        if (! $isAdmin) {
            $scopedQuery->where('assigned_to', auth()->id());
        } elseif ($request->filled('employee_id')) {
            $scopedQuery->where('assigned_to', $request->employee_id);
        }

        // Month filter
        if ($request->filled('month')) {
            $parts = explode('-', $request->month);
            if (count($parts) === 2) {
                $scopedQuery->whereYear('created_at', $parts[0])->whereMonth('created_at', $parts[1]);
            }
        } elseif ($request->filled('date')) {
            $scopedQuery->whereDate('created_at', $request->date);
        }

        $today = today();
        $now = now();

        $stats = [
            'total' => (clone $scopedQuery)->count(),
            'today' => (clone $scopedQuery)->whereDate('follow_up_at', $today)->count(),
            'overdue' => (clone $scopedQuery)->where('follow_up_at', '<', $now)->count(),
            'upcoming' => (clone $scopedQuery)->where('follow_up_at', '>', $now)->count(),
        ];

        $query = Booking::with(['car.brand', 'employee'])->where('status', 'pending');

        if (! $isAdmin) {
            $query->where('assigned_to', auth()->id());
        } elseif ($request->filled('employee_id')) {
            $query->where('assigned_to', $request->employee_id);
        }

        // Month / Date filter
        if ($request->filled('month')) {
            $parts = explode('-', $request->month);
            if (count($parts) === 2) {
                $query->whereYear('created_at', $parts[0])->whereMonth('created_at', $parts[1]);
            }
        } elseif ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Timing filter
        if ($request->filled('timing')) {
            if ($request->timing === 'today') {
                $query->whereDate('follow_up_at', $today);
            } elseif ($request->timing === 'overdue') {
                $query->where('follow_up_at', '<', $now);
            } elseif ($request->timing === 'upcoming') {
                $query->where('follow_up_at', '>', $now);
            }
        }

        // Source / Type filter
        if ($request->filled('source')) {
            if ($request->source === 'calculator') {
                $query->where(function ($q) {
                    $q->where('source', 'calculator')
                        ->orWhereNotNull('calculator_bank_id');
                });
            } elseif ($request->source === 'cars' || $request->source === 'booking') {
                $query->where(function ($q) {
                    $q->where('source', '!=', 'calculator')
                        ->orWhereNull('source');
                });
            } elseif ($request->source === 'test_drive') {
                $query->where('booking_type', 'test_drive');
            } elseif ($request->source === 'purchase') {
                $query->where('booking_type', 'purchase');
            } elseif ($request->source === 'crm_manual') {
                $query->where('source', 'like', '%CRM%');
            }
        }

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('client_name', 'like', "%$s%")
                    ->orWhere('client_phone', 'like', "%$s%");
            });
        }

        // Sort
        if ($sort === 'oldest') {
            $query->oldest('created_at');
        } elseif ($sort === 'newest') {
            $query->latest('created_at');
        } elseif ($sort === 'furthest_follow_up') {
            $query->orderByDesc('follow_up_at');
        } else {
            // nearest_follow_up (default)
            $query->orderBy('follow_up_at', 'asc');
        }

        $bookings = $query->paginate(20)->withQueryString();
        $employees = Employee::where('is_active', true)->get();
        $allStatuses = Booking::STATUSES;
        $cars = Car::with('brand')->where('is_active', true)->get();

        return view('crm.bookings.pending', compact(
            'bookings', 'employees', 'allStatuses', 'cars', 'stats', 'isAdmin'
        ));
    }

    /**
     * Delivered bookings list (طلبات تم التسليم / المستلمة)
     */
    public function deliveredIndex(Request $request)
    {
        $sort = $request->input('sort', 'newest');
        $isAdmin = auth('employee')->user()->isAdmin();

        $scopedQuery = Booking::query()->where('status', 'received');
        if (! $isAdmin) {
            $scopedQuery->where('assigned_to', auth()->id());
        } elseif ($request->filled('employee_id')) {
            $scopedQuery->where('assigned_to', $request->employee_id);
        }

        // Month filter
        if ($request->filled('month')) {
            $parts = explode('-', $request->month);
            if (count($parts) === 2) {
                $scopedQuery->where(function ($q) use ($parts) {
                    $q->where(function ($sub) use ($parts) {
                        $sub->whereNotNull('delivered_at')
                            ->whereYear('delivered_at', $parts[0])
                            ->whereMonth('delivered_at', $parts[1]);
                    })->orWhere(function ($sub) use ($parts) {
                        $sub->whereNull('delivered_at')
                            ->whereYear('updated_at', $parts[0])
                            ->whereMonth('updated_at', $parts[1]);
                    });
                });
            }
        } elseif ($request->filled('date')) {
            $scopedQuery->where(function ($q) use ($request) {
                $q->whereDate('delivered_at', $request->date)
                    ->orWhere(function ($sub) use ($request) {
                        $sub->whereNull('delivered_at')->whereDate('updated_at', $request->date);
                    });
            });
        }

        $now = now();
        $currentMonthDeliveredQuery = (clone $scopedQuery)->where(function ($q) use ($now) {
            $q->where(function ($sub) use ($now) {
                $sub->whereNotNull('delivered_at')
                    ->whereYear('delivered_at', $now->year)
                    ->whereMonth('delivered_at', $now->month);
            })->orWhere(function ($sub) use ($now) {
                $sub->whereNull('delivered_at')
                    ->whereYear('updated_at', $now->year)
                    ->whereMonth('updated_at', $now->month);
            });
        });

        $stats = [
            'total_delivered' => (clone $scopedQuery)->count(),
            'month_delivered' => (clone $currentMonthDeliveredQuery)->count(),
            'total_commission' => (clone $scopedQuery)->sum('net_commission'),
            'month_commission' => (clone $currentMonthDeliveredQuery)->sum('net_commission'),
            'total_sales_value' => (clone $scopedQuery)->sum('total_price'),
        ];

        $query = Booking::with(['car.brand', 'employee'])->where('status', 'received');

        if (! $isAdmin) {
            $query->where('assigned_to', auth()->id());
        } elseif ($request->filled('employee_id')) {
            $query->where('assigned_to', $request->employee_id);
        }

        // Month / Date filter
        if ($request->filled('month')) {
            $parts = explode('-', $request->month);
            if (count($parts) === 2) {
                $query->where(function ($q) use ($parts) {
                    $q->where(function ($sub) use ($parts) {
                        $sub->whereNotNull('delivered_at')
                            ->whereYear('delivered_at', $parts[0])
                            ->whereMonth('delivered_at', $parts[1]);
                    })->orWhere(function ($sub) use ($parts) {
                        $sub->whereNull('delivered_at')
                            ->whereYear('updated_at', $parts[0])
                            ->whereMonth('updated_at', $parts[1]);
                    });
                });
            }
        } elseif ($request->filled('date')) {
            $query->where(function ($q) use ($request) {
                $q->whereDate('delivered_at', $request->date)
                    ->orWhere(function ($sub) use ($request) {
                        $sub->whereNull('delivered_at')->whereDate('updated_at', $request->date);
                    });
            });
        }

        // Source / Type filter
        if ($request->filled('source')) {
            if ($request->source === 'calculator') {
                $query->where(function ($q) {
                    $q->where('source', 'calculator')
                        ->orWhereNotNull('calculator_bank_id');
                });
            } elseif ($request->source === 'cars' || $request->source === 'booking') {
                $query->where(function ($q) {
                    $q->where('source', '!=', 'calculator')
                        ->orWhereNull('source');
                });
            } elseif ($request->source === 'test_drive') {
                $query->where('booking_type', 'test_drive');
            } elseif ($request->source === 'purchase') {
                $query->where('booking_type', 'purchase');
            } elseif ($request->source === 'crm_manual') {
                $query->where('source', 'like', '%CRM%');
            }
        }

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('client_name', 'like', "%$s%")
                    ->orWhere('client_phone', 'like', "%$s%");
            });
        }

        // Sort
        if ($sort === 'oldest') {
            $query->orderBy(DB::raw('COALESCE(delivered_at, updated_at)'), 'asc');
        } elseif ($sort === 'highest_commission') {
            $query->orderByDesc('net_commission');
        } elseif ($sort === 'highest_price') {
            $query->orderByDesc('total_price');
        } else {
            $query->orderBy(DB::raw('COALESCE(delivered_at, updated_at)'), 'desc');
        }

        $bookings = $query->paginate(20)->withQueryString();
        $employees = Employee::where('is_active', true)->get();
        $allStatuses = Booking::STATUSES;
        $cars = Car::with('brand')->where('is_active', true)->get();

        return view('crm.bookings.delivered', compact(
            'bookings', 'employees', 'allStatuses', 'cars', 'stats', 'isAdmin'
        ));
    }

    /**
     * Closed bookings list (الحالات المغلقة / الخاسرة)
     */
    public function closedIndex(Request $request)
    {
        $sort = $request->input('sort', 'newest');
        $isAdmin = auth('employee')->user()->isAdmin();

        // Get all closed lost status keys from Booking::STATUSES (is_lost == true or group == 'lost')
        $closedStatuses = collect(Booking::STATUSES)
            ->filter(fn ($s) => ($s['is_lost'] ?? false) === true || ($s['group'] ?? '') === 'lost')
            ->keys()
            ->toArray();

        // Base scoped query (per employee or all for admin)
        $scopedQuery = Booking::query()->whereIn('status', $closedStatuses);
        if (! $isAdmin) {
            $scopedQuery->where('assigned_to', auth()->id());
        } elseif ($request->filled('employee_id')) {
            $scopedQuery->where('assigned_to', $request->employee_id);
        }

        // Month filter
        if ($request->filled('month')) {
            $parts = explode('-', $request->month);
            if (count($parts) === 2) {
                $scopedQuery->whereYear('updated_at', $parts[0])->whereMonth('updated_at', $parts[1]);
            }
        } elseif ($request->filled('date')) {
            $scopedQuery->whereDate('updated_at', $request->date);
        }

        // Calculate closed stats
        $totalClosed = (clone $scopedQuery)->count();
        $now = now();
        $closedThisMonth = (clone $scopedQuery)->whereYear('updated_at', $now->year)->whereMonth('updated_at', $now->month)->count();

        $statsByStatus = [];
        foreach ($closedStatuses as $statusKey) {
            $statusCount = (clone $scopedQuery)->where('status', $statusKey)->count();
            $percentage = $totalClosed > 0 ? round(($statusCount / $totalClosed) * 100) : 0;
            $statsByStatus[$statusKey] = [
                'count' => $statusCount,
                'percentage' => $percentage,
                'label' => Booking::STATUSES[$statusKey]['label'] ?? $statusKey,
                'color' => Booking::STATUSES[$statusKey]['color'] ?? 'secondary',
            ];
        }

        // Sort stats by count descending
        uasort($statsByStatus, fn ($a, $b) => $b['count'] <=> $a['count']);

        $query = Booking::with(['car.brand', 'employee'])->whereIn('status', $closedStatuses);

        if (! $isAdmin) {
            $query->where('assigned_to', auth()->id());
        } elseif ($request->filled('employee_id')) {
            $query->where('assigned_to', $request->employee_id);
        }

        // Month / Date filter
        if ($request->filled('month')) {
            $parts = explode('-', $request->month);
            if (count($parts) === 2) {
                $query->whereYear('updated_at', $parts[0])->whereMonth('updated_at', $parts[1]);
            }
        } elseif ($request->filled('date')) {
            $query->whereDate('updated_at', $request->date);
        }

        // Filtering by source / type
        if ($request->filled('source')) {
            if ($request->source === 'calculator') {
                $query->where(function ($q) {
                    $q->where('source', 'calculator')
                        ->orWhereNotNull('calculator_bank_id');
                });
            } elseif ($request->source === 'cars' || $request->source === 'booking') {
                $query->where(function ($q) {
                    $q->where('source', '!=', 'calculator')
                        ->orWhereNull('source');
                });
            } elseif ($request->source === 'test_drive') {
                $query->where('booking_type', 'test_drive');
            } elseif ($request->source === 'purchase') {
                $query->where('booking_type', 'purchase');
            } elseif ($request->source === 'crm_manual') {
                $query->where('source', 'like', '%CRM%');
            }
        }

        // Filtering by status (only if it is a closed status)
        if ($request->filled('status') && in_array($request->status, $closedStatuses)) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('client_name', 'like', "%$s%")
                    ->orWhere('client_phone', 'like', "%$s%");
            });
        }

        // Sorting
        if ($sort === 'oldest') {
            $query->oldest('updated_at');
        } else {
            $query->latest('updated_at');
        }

        $bookings = $query->paginate(20)->withQueryString();
        $employees = Employee::where('is_active', true)->get();
        $statuses = collect(Booking::STATUSES)->only($closedStatuses)->toArray();
        $cars = Car::with('brand')->where('is_active', true)->get();

        return view('crm.bookings.closed', compact(
            'bookings', 'employees', 'statuses', 'cars', 'totalClosed', 'closedThisMonth', 'statsByStatus', 'isAdmin'
        ));
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
            'total_price' => 'nullable|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'authorization_price' => 'nullable|numeric|min:0',
            'expenses' => 'nullable|numeric|min:0',
            'net_commission' => 'nullable|numeric',
            'down_payment' => 'nullable|numeric|min:0',
            'duration_years' => 'nullable|integer|min:1|max:10',
            'monthly_installment' => 'nullable|numeric|min:0',
            'balloon_payment' => 'nullable|numeric|min:0',
            'offer_notes' => 'nullable|string|max:2000',
            'delivery_note' => 'nullable|string|max:2000',
        ]);

        $booking->update($data);

        return back()->with('success', 'تم تحديث تفاصيل العرض والبيانات المالية بنجاح');
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

        // Case 1: Target status is delivered / received (تم التسليم)
        if ($targetStatus === 'received') {
            $request->validate([
                'purchase_price' => 'nullable|numeric|min:0',
                'authorization_price' => 'nullable|numeric|min:0',
                'expenses' => 'nullable|numeric|min:0',
                'net_commission' => 'nullable|numeric',
                'down_payment' => 'nullable|numeric|min:0',
                'monthly_installment' => 'nullable|numeric|min:0',
                'note' => 'nullable|string|max:2000',
            ]);

            $purchasePrice = $request->filled('purchase_price') ? (float) $request->purchase_price : null;
            $authPrice = $request->filled('authorization_price') ? (float) $request->authorization_price : null;
            $expenses = $request->filled('expenses') ? (float) $request->expenses : null;
            $netCommission = $request->filled('net_commission') ? (float) $request->net_commission : null;
            $downPayment = $request->filled('down_payment') ? (float) $request->down_payment : $booking->down_payment;
            $monthlyInstallment = $request->filled('monthly_installment') ? (float) $request->monthly_installment : $booking->monthly_installment;
            $deliveryNote = $request->filled('note') ? trim($request->note) : ($booking->delivery_note ?? null);

            $booking->update([
                'status' => 'received',
                'purchase_price' => $purchasePrice,
                'authorization_price' => $authPrice,
                'expenses' => $expenses,
                'net_commission' => $netCommission,
                'down_payment' => $downPayment,
                'monthly_installment' => $monthlyInstallment,
                'delivery_note' => $deliveryNote,
                'delivered_at' => $booking->delivered_at ?? now(),
                'pending_reason' => null,
                'follow_up_at' => null,
                'proposed_status' => null,
                'last_contacted_at' => now(),
            ]);

            $noteDetails = "تم تسليم الطلب بنجاح (تم الاستلام):\n"
                .'- سعر شراء السيارة: '.($purchasePrice !== null ? number_format($purchasePrice, 2).' ر.س' : '—')."\n"
                .'- سعر تعميد السيارة: '.($authPrice !== null ? number_format($authPrice, 2).' ر.س' : '—')."\n"
                .'- المصروفات: '.($expenses !== null ? number_format($expenses, 2).' ر.س' : '—')."\n"
                .'- صافي عمولة الشركة: '.($netCommission !== null ? number_format($netCommission, 2).' ر.س' : '—')."\n"
                .'- القسط الشهري: '.($monthlyInstallment > 0 ? number_format($monthlyInstallment, 2).' ر.س' : '—')."\n"
                .'- الدفعة الأولى: '.($downPayment > 0 ? number_format($downPayment, 2).' ر.س' : '—');
            if ($request->filled('note')) {
                $noteDetails .= "\nملاحظة التسليم: ".$request->note;
            }

            BookingNote::create([
                'booking_id' => $booking->id,
                'employee_id' => $employee->id,
                'note' => $noteDetails,
                'type' => 'status_change',
                'old_status' => $oldStatus,
                'new_status' => 'received',
            ]);

            if ($booking->assignedTo && $booking->assignedTo->id !== $employee->id) {
                $booking->assignedTo->notify(new NewBookingNotification($booking, __('تم تسليم الطلب'), __('تم تغيير حالة طلب العميل ').$booking->client_name.__(' إلى تم التسليم')));
            }

            return back()->with('success', 'تم نقل الطلب إلى قائمة "تم التسليم" بنجاح.');
        }

        // Case 2: Target status is pending (قيد الانتظار)
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

        // Case 3: Target status is a closing lost status
        $isLostStatus = (Booking::STATUSES[$targetStatus]['is_lost'] ?? false) || ((Booking::STATUSES[$targetStatus]['group'] ?? '') === 'lost');

        if ($isLostStatus) {
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
                    'pending_reason' => null,
                    'follow_up_at' => null,
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

        // Case 4: Normal active status update (returning from pending/closed or advancing in pipeline)
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
        $employee = auth('employee')->user();
        if (! $employee->isAdmin()) {
            abort(403, __('غير مصرح لك بإعادة إسناد أو تحويل الطلب. الصلاحية مقتصرة على المشرف/المدير فقط.'));
        }

        $request->validate(['employee_id' => 'required|exists:employees,id']);
        $booking->update(['assigned_to' => $request->employee_id]);

        // Notify the assigned employee
        $assignedEmployee = Employee::find($request->employee_id);
        if ($assignedEmployee) {
            $assignedEmployee->notify(new NewBookingNotification($booking, __('طلب جديد'), __('تم تعيين طلب جديد لك للعميل').' '.$booking->client_name));
        }

        return back()->with('success', 'تم توزيع الطلب على الموظف بنجاح');
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
        $employee = auth('employee')->user();
        if (! $employee->isAdmin()) {
            abort(403, __('غير مصرح لك بحذف الطلبات. صلاحية الحذف مقتصرة على المشرف/المدير فقط.'));
        }

        $booking->delete();

        return redirect()->route('crm.bookings.index')->with('success', 'تم حذف الطلب بنجاح');
    }
}
