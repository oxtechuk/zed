<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $taskQuery = Task::query()->when(! \auth()->user()->hasRole('admin'), function ($q) {
            $q->where('assigned_to', \auth()->id());
        });
        $tasks = $taskQuery->with(['assignedTo', 'createdBy'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
            ->latest()
            ->paginate(20);

        $employees = Employee::where('is_active', true)->get();

        $counts = [
            'new' => $taskQuery->where('status', 'new')->count(),
            'in_progress' => $taskQuery->where('status', 'in_progress')->count(),
            'done' => $taskQuery->where('status', 'done')->count(),
            'total' => $taskQuery->count(),
        ];

        return view('crm.tasks.index', compact('tasks', 'employees', 'counts', 'status'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:new,in_progress,done',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|exists:employees,id',
            'due_date' => 'nullable|date',
            'booking_id' => 'nullable|exists:bookings,id',
        ]);

        $data['created_by'] = Auth::guard('employee')->id();

        Task::create($data);

        return back()->with('success', 'تم إضافة المهمة بنجاح');
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:new,in_progress,done',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|exists:employees,id',
            'due_date' => 'nullable|date',
        ]);

        $task->update($data);

        return back()->with('success', 'تم تحديث المهمة بنجاح');
    }

    public function toggle(Task $task)
    {
        $next = match ($task->status) {
            'new' => 'in_progress',
            'in_progress' => 'done',
            'done' => 'new',
            default => 'new',
        };
        $task->update(['status' => $next]);

        return back()->with('success', 'تم تحديث حالة المهمة');
    }

    public function start(Task $task)
    {
        $task->update(['status' => 'in_progress']);

        return back()->with('success', 'تم بدء تنفيذ المهمة');
    }

    public function complete(Task $task)
    {
        $task->update(['status' => 'done']);

        return back()->with('success', 'تم إنهاء المهمة');
    }

    public function postpone(Request $request, Task $task)
    {
        $days = max(1, (int) $request->get('days', 1));
        $base = $task->due_date ? $task->due_date->copy() : now();

        $task->update(['due_date' => $base->addDays($days)]);

        return back()->with('success', 'تم تأجيل المهمة');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return back()->with('success', 'تم حذف المهمة');
    }
}
