<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Booking;
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

        $baseBookings = Booking::with('car.brand')
            ->where('assigned_to', $employee->id)
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
            'open' => (clone $baseBookings)->whereIn('status', ['new', 'contacted', 'interested'])->count(),
            'closed' => (clone $baseBookings)->where('status', 'rejected')->count(),
            'completed' => (clone $baseBookings)->where('status', 'sold')->count(),
        ];

        $requestsQuery = clone $baseBookings;
        if ($sort === 'priority') {
            $requestsQuery->orderByRaw("FIELD(status,'new','interested','contacted','sold','rejected')")->latest();
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
            'follow_ups' => (clone $baseBookings)->whereIn('status', ['new', 'contacted', 'interested'])
                ->where(function (Builder $q) {
                    $q->whereNull('last_contacted_at')->orWhere('last_contacted_at', '<=', now()->subDays(2));
                })
                ->latest('created_at')
                ->limit(5)
                ->get(),
            'approvals' => (clone $baseBookings)->where('status', 'sold')
                ->whereDoesntHave('documents')
                ->latest('updated_at')
                ->limit(5)
                ->get(),
        ];

        return view('crm.dashboard', compact(
            'stats', 'requests', 'tasksToday', 'tasksOverdue', 'tasksUpcoming',
            'alerts', 'range', 'from', 'to', 'search', 'sort'
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
