<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Specification;
use Illuminate\Http\Request;

class SpecificationController extends Controller
{
    public function index()
    {
        $specifications = Specification::latest()->paginate(20);

        return view('crm.specifications.index', compact('specifications'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|array',
            'name.ar' => 'required|string',
            'name.en' => 'required|string',
            'icon' => 'nullable|string',
        ]);

        $specification = Specification::create($data);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تمت إضافة المواصفة بنجاح',
                'specification' => [
                    'id' => $specification->id,
                    'name' => $specification->getTranslation('name', app()->getLocale()) ?? $specification->name,
                    'name_ar' => $specification->getTranslation('name', 'ar', false),
                    'name_en' => $specification->getTranslation('name', 'en', false),
                    'icon' => $specification->icon,
                ],
            ]);
        }

        return back()->with('success', 'تمت إضافة المواصفة بنجاح');
    }

    public function update(Request $request, Specification $specification)
    {
        $request->validate([
            'name' => 'required|array',
            'name.ar' => 'required|string',
            'name.en' => 'required|string',
            'icon' => 'nullable|string',
        ]);

        $specification->update($request->all());

        return back()->with('success', 'تم تحديث المواصفة بنجاح');
    }

    public function destroy(Specification $specification)
    {
        $specification->delete();

        return back()->with('success', 'تم حذف المواصفة');
    }
}
