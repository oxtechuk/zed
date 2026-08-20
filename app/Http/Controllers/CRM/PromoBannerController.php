<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\PromoBanner;
use App\Services\Cache\HomeCacheService;
use App\Services\ImageOptimizerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromoBannerController extends Controller
{
    public function __construct(
        private readonly HomeCacheService $homeCache,
        private readonly ImageOptimizerService $imageOptimizer,
    ) {}

    public function index()
    {
        $banners = PromoBanner::query()->orderBy('sort_order')->get();

        return view('crm.settings.promo-banners.index', compact('banners'));
    }

    private function rules(bool $isCreate): array
    {
        return [
            'title.ar' => 'required|string|max:255',
            'title.en' => 'required|string|max:255',
            'subtitle.ar' => 'nullable|string|max:255',
            'subtitle.en' => 'nullable|string|max:255',
            'description.ar' => 'nullable|string',
            'description.en' => 'nullable|string',
            'image' => ($isCreate ? 'required' : 'nullable').'|image|max:8192',
            'button_text.ar' => 'nullable|string|max:100',
            'button_text.en' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(isCreate: true));
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $data['image'] = $this->imageOptimizer->storeAndOptimize(
                $request->file('image'),
                'promo-banners',
                ['maxWidth' => 1600, 'quality' => 82]
            );
        }

        PromoBanner::create($data);
        $this->homeCache->forgetSection('promo_banners');

        return back()->with('success', __('تمت إضافة البانر الترويجي بنجاح'));
    }

    public function update(Request $request, PromoBanner $promoBanner)
    {
        $data = $request->validate($this->rules(isCreate: false));
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($promoBanner->getRawOriginal('image')) {
                Storage::disk('public')->delete($promoBanner->getRawOriginal('image'));
            }
            $data['image'] = $this->imageOptimizer->storeAndOptimize(
                $request->file('image'),
                'promo-banners',
                ['maxWidth' => 1600, 'quality' => 82]
            );
        }

        $promoBanner->update($data);
        $this->homeCache->forgetSection('promo_banners');

        return back()->with('success', __('تم تعديل البانر الترويجي بنجاح'));
    }

    public function destroy(PromoBanner $promoBanner)
    {
        if ($promoBanner->getRawOriginal('image')) {
            Storage::disk('public')->delete($promoBanner->getRawOriginal('image'));
        }

        $promoBanner->delete();
        $this->homeCache->forgetSection('promo_banners');

        return back()->with('success', __('تم حذف البانر الترويجي بنجاح'));
    }
}
