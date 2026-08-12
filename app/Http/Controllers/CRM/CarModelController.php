<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\CarModel;
use Illuminate\Http\Request;

class CarModelController extends Controller
{
    public function index(Request $request)
    {
        $query = CarModel::with('brand')->latest();

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        $carModels = $query->paginate(20);
        $brands = Brand::all();

        return view('crm.car-models.index', compact('carModels', 'brands'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|array',
            'name.ar' => 'required|string|max:100',
            'name.en' => 'required|string|max:100',
        ]);

        $carModel = CarModel::create($data);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تمت إضافة الموديل بنجاح',
                'model' => [
                    'id' => $carModel->id,
                    'name_ar' => $carModel->getTranslation('name', 'ar'),
                    'name_en' => $carModel->getTranslation('name', 'en'),
                    'name' => $carModel->getTranslation('name', app()->getLocale()),
                ],
            ]);
        }

        return back()->with('success', 'تمت إضافة الموديل بنجاح');
    }

    public function update(Request $request, CarModel $carModel)
    {
        $data = $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|array',
            'name.ar' => 'required|string|max:100',
            'name.en' => 'required|string|max:100',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $carModel->update($data);

        return back()->with('success', 'تم تحديث الموديل بنجاح');
    }

    public function destroy(CarModel $carModel)
    {
        $carModel->delete();

        return back()->with('success', 'تم حذف الموديل بنجاح');
    }

    public function getModelsByBrand(Brand $brand)
    {
        $models = CarModel::where('brand_id', $brand->id)
            ->where('is_active', true)
            ->get()
            ->map(fn ($model) => [
                'id' => $model->id,
                'name' => $model->getTranslation('name', app()->getLocale()) ?? $model->name,
            ]);

        return response()->json($models);
    }
}
