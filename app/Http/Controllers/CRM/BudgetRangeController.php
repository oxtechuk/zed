<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\BudgetRange;
use App\Services\Cache\HomeCacheService;
use Illuminate\Http\Request;

class BudgetRangeController extends Controller
{
    public function __construct(
        private readonly HomeCacheService $homeCache,
    ) {}

    public function index()
    {
        $ranges = BudgetRange::query()->orderBy('sort_order')->get();

        return view('crm.settings.budget-ranges.index', compact('ranges'));
    }

    private function rules(): array
    {
        return [
            'label.ar' => 'required|string|max:100',
            'label.en' => 'required|string|max:100',
            'min' => 'required|integer|min:0',
            'max' => 'nullable|integer|gt:min',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['is_active'] = $request->boolean('is_active', true);

        BudgetRange::create($data);
        $this->homeCache->forgetSection('budget');

        return back()->with('success', __('تمت إضافة نطاق الميزانية بنجاح'));
    }

    public function update(Request $request, BudgetRange $budgetRange)
    {
        $data = $request->validate($this->rules());
        $data['is_active'] = $request->boolean('is_active');

        $budgetRange->update($data);
        $this->homeCache->forgetSection('budget');

        return back()->with('success', __('تم تعديل نطاق الميزانية بنجاح'));
    }

    public function destroy(BudgetRange $budgetRange)
    {
        $budgetRange->delete();
        $this->homeCache->forgetSection('budget');

        return back()->with('success', __('تم حذف نطاق الميزانية بنجاح'));
    }
}
