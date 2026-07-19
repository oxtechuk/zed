<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\FinanceStep;
use App\Services\Cache\HomeCacheService;
use Illuminate\Http\Request;

class FinanceStepController extends Controller
{
    public function __construct(
        private readonly HomeCacheService $homeCache,
    ) {}

    public function index()
    {
        $steps = FinanceStep::query()->orderBy('sort_order')->orderBy('number')->get();

        return view('crm.settings.finance-steps.index', compact('steps'));
    }

    private function rules(): array
    {
        return [
            'number' => 'required|integer|min:1',
            'title.ar' => 'required|string|max:255',
            'title.en' => 'required|string|max:255',
            'description.ar' => 'nullable|string',
            'description.en' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['is_active'] = $request->boolean('is_active', true);

        FinanceStep::create($data);
        $this->homeCache->forgetSection('finance');

        return back()->with('success', __('تمت إضافة خطوة التمويل بنجاح'));
    }

    public function update(Request $request, FinanceStep $financeStep)
    {
        $data = $request->validate($this->rules());
        $data['is_active'] = $request->boolean('is_active');

        $financeStep->update($data);
        $this->homeCache->forgetSection('finance');

        return back()->with('success', __('تم تعديل خطوة التمويل بنجاح'));
    }

    public function destroy(FinanceStep $financeStep)
    {
        $financeStep->delete();
        $this->homeCache->forgetSection('finance');

        return back()->with('success', __('تم حذف خطوة التمويل بنجاح'));
    }
}
