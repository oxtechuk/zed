<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function index()
    {
        $features = Feature::latest()->paginate(20);

        return view('crm.features.index', compact('features'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|array',
            'name.ar' => 'required|string',
            'name.en' => 'required|string',
            'icon' => 'nullable|string',
        ]);

        $feature = Feature::create($data);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تمت إضافة الخاصية بنجاح',
                'feature' => [
                    'id' => $feature->id,
                    'name' => $feature->getTranslation('name', app()->getLocale()) ?? $feature->name,
                    'name_ar' => $feature->getTranslation('name', 'ar', false),
                    'name_en' => $feature->getTranslation('name', 'en', false),
                    'icon' => $feature->icon,
                ],
            ]);
        }

        return back()->with('success', 'تمت إضافة الخاصية بنجاح');
    }

    public function update(Request $request, Feature $feature)
    {
        $request->validate([
            'name' => 'required|array',
            'name.ar' => 'required|string',
            'name.en' => 'required|string',
            'icon' => 'nullable|string',
        ]);

        $feature->update($request->all());

        return back()->with('success', 'تم تحديث الخاصية بنجاح');
    }

    public function destroy(Feature $feature)
    {
        $feature->delete();

        return back()->with('success', 'تم حذف الخاصية');
    }
}
