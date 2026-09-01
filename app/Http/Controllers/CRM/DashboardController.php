<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Employee;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $employee = auth()->user();
        $isAdmin = $employee->isAdmin();

        [$from, $to, $range] = $this->resolveRange($request);
        $search = trim((string) $request->get('search'));
        $sort = $request->get('sort', 'priority') === 'recent' ? 'recent' : 'priority';

        $activeStatuses = ['new', 'contacted_no_answer', 'recontact_client', 'waiting_documents', 'bank_review', 'approved', 'authorized', 'contacted', 'interested'];

        $baseBookings = Booking::with(['car.brand', 'employee'])
            ->when(! $isAdmin, fn (Builder $q) => $q->where('assigned_to', $employee->id))
            ->when($isAdmin && $request->filled('employee_id'), fn (Builder $q) => $q->where('assigned_to', $request->employee_id))
            ->when($from, fn (Builder $q) => $q->whereBetween('created_at', [$from, $to]))
            ->when($search !== '', function (Builder $q) use ($search) {
                $q->where(function (Builder $q) use ($search) {
                    $q->where('id', $search)
                        ->orWhere('client_name', 'like', "%{$search}%")
                        ->orWhere('client_phone', 'like', "%{$search}%");
                });
            });

        $stats = [
            'total' => (clone $baseBookings)->count(),
            'open' => (clone $baseBookings)->whereIn('status', $activeStatuses)->count(),
            'pending' => (clone $baseBookings)->where('status', 'pending')->count(),
            'closed' => (clone $baseBookings)->where(function (Builder $q) {
                $q->where('status', 'like', 'lost_%')->orWhere('status', 'rejected');
            })->count(),
            'completed' => (clone $baseBookings)->whereIn('status', ['received', 'sold', 'done'])->count(),
            'total_commission' => (clone $baseBookings)->whereIn('status', ['received', 'sold', 'done'])->sum('net_commission'),
        ];

        $requestsQuery = clone $baseBookings;
        if ($sort === 'priority') {
            $requestsQuery->orderByRaw("FIELD(status,'new','pending','bank_review','approved','authorized','received')")->latest();
        } else {
            $requestsQuery->latest('updated_at');
        }
        $requests = $requestsQuery->limit(12)->get();

        $baseTasks = Task::query()->where('assigned_to', $employee->id);
        $today = now()->toDateString();

        $tasksToday = (clone $baseTasks)->whereDate('due_date', $today)
            ->where('status', '!=', 'done')
            ->orderByRaw("FIELD(priority,'high','medium','low')")
            ->get();

        $tasksOverdue = (clone $baseTasks)->whereDate('due_date', '<', $today)
            ->where('status', '!=', 'done')
            ->orderBy('due_date')
            ->get();

        $tasksUpcoming = (clone $baseTasks)->whereDate('due_date', '>', $today)
            ->where('status', '!=', 'done')
            ->orderBy('due_date')
            ->limit(10)
            ->get();

        $alerts = [
            'tasks' => (clone $baseTasks)->whereDate('due_date', '<=', $today)
                ->where('status', '!=', 'done')
                ->orderBy('due_date')
                ->limit(5)
                ->get(),
            'follow_ups' => (clone $baseBookings)->whereIn('status', $activeStatuses)
                ->where(function (Builder $q) {
                    $q->whereNull('last_contacted_at')->orWhere('last_contacted_at', '<=', now()->subDays(2));
                })
                ->latest('created_at')
                ->limit(5)
                ->get(),
            'approvals' => (clone $baseBookings)->whereIn('status', ['received', 'sold'])
                ->whereDoesntHave('documents')
                ->latest('updated_at')
                ->limit(5)
                ->get(),
        ];

        $employees = $isAdmin ? Employee::where('is_active', true)->orderBy('name')->get() : collect();

        return view('crm.dashboard', compact(
            'stats', 'requests', 'tasksToday', 'tasksOverdue', 'tasksUpcoming',
            'alerts', 'range', 'from', 'to', 'search', 'sort', 'isAdmin', 'employees'
        ));
    }

    /**
     * @return array{0: ?Carbon, 1: ?Carbon, 2: string}
     */
    private function resolveRange(Request $request): array
    {
        $range = $request->get('range', 'all');
        $now = now();

        return match ($range) {
            'today' => [$now->copy()->startOfDay(), $now->copy()->endOfDay(), $range],
            'week' => [$now->copy()->startOfWeek(), $now->copy()->endOfWeek(), $range],
            'month' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth(), $range],
            'year' => [$now->copy()->startOfYear(), $now->copy()->endOfYear(), $range],
            'ytd' => [$now->copy()->startOfYear(), $now->copy()->endOfDay(), $range],
            'custom' => [
                $request->filled('from') ? Carbon::parse($request->get('from'))->startOfDay() : null,
                $request->filled('to') ? Carbon::parse($request->get('to'))->endOfDay() : $now->copy()->endOfDay(),
                $range,
            ],
            default => [null, null, 'all'],
        };
    }
}
