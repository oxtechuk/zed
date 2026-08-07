<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Car;
use App\Models\Employee;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['car.brand', 'assignedTo'])->withCount('notes_list');

        // Apply filters (identical to bookings index)
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }
        if ($request->filled('car_id')) {
            $query->where('car_id', $request->car_id);
        }
        if ($request->filled('booking_type')) {
            $query->where('booking_type', $request->booking_type);
        }
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $bookings = $query->latest()
            ->get()
            ->groupBy(function ($b) {
                return array_key_exists($b->status, Booking::STATUSES) ? $b->status : 'new';
            });

        // Map colors for active statuses, defaulting to red for lost statuses
        $statusColors = [
            'new' => '#1877F2',
            'contacted_no_answer' => '#0DCAF0',
            'recontact_client' => '#05B2DE',
            'waiting_documents' => '#FF9800',
            'bank_review' => '#FD7E14',
            'approved' => '#198754',
            'authorized' => '#4CAF50',
            'received' => '#20C997',
            'pending' => '#6C757D',
            'waiting_supervisor_approval' => '#343A40',
        ];

        $columns = [];
        foreach (Booking::STATUSES as $key => $status) {
            $columns[$key] = [
                'label' => $status['label'],
                'color' => $statusColors[$key] ?? '#ED5E5E',
                'group' => $status['group'],
                'count' => 0,
                'items' => $bookings[$key] ?? collect(),
            ];
            $columns[$key]['count'] = $columns[$key]['items']->count();
        }

        $employees = Employee::where('is_active', true)->get();
        $cars = Car::with('brand')->get();
        $sources = Booking::distinct()->whereNotNull('source')->pluck('source');

        return view('crm.tracking.index', compact('columns', 'employees', 'cars', 'sources'));
    }
}
