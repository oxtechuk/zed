<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Services\Cache\HomeCacheService;
use App\Services\ImageOptimizerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSlideController extends Controller
{
    public function __construct(
        private readonly HomeCacheService $homeCache,
        private readonly ImageOptimizerService $imageOptimizer,
    ) {}

    public function index()
    {
        $slides = HeroSlide::query()->orderBy('sort_order')->get();

        return view('crm.settings.hero-slides.index', compact('slides'));
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
            'image_desktop' => ($isCreate ? 'required' : 'nullable').'|image|max:8192',
            'image_mobile' => 'nullable|image|max:8192',
            'button_text.ar' => 'nullable|string|max:100',
            'button_text.en' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'badge.ar' => 'nullable|string|max:100',
            'badge.en' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(isCreate: true));
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image_desktop')) {
            $data['image_desktop'] = $this->imageOptimizer->storeAndOptimize($request->file('image_desktop'), 'hero-slides', ['maxWidth' => 1920, 'quality' => 82]);
        }
        if ($request->hasFile('image_mobile')) {
            $data['image_mobile'] = $this->imageOptimizer->storeAndOptimize($request->file('image_mobile'), 'hero-slides', ['maxWidth' => 1080, 'quality' => 82]);
        }

        HeroSlide::create($data);
        $this->homeCache->forgetHome();
        $this->homeCache->forgetSection('hero');

        return back()->with('success', __('تمت إضافة الشريحة بنجاح'));
    }

    public function update(Request $request, HeroSlide $heroSlide)
    {
        $data = $request->validate($this->rules(isCreate: false));
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image_desktop')) {
            if ($heroSlide->getRawOriginal('image_desktop')) {
                Storage::disk('public')->delete($heroSlide->getRawOriginal('image_desktop'));
            }
            $data['image_desktop'] = $this->imageOptimizer->storeAndOptimize($request->file('image_desktop'), 'hero-slides', ['maxWidth' => 1920, 'quality' => 82]);
        }
        if ($request->hasFile('image_mobile')) {
            if ($heroSlide->getRawOriginal('image_mobile')) {
                Storage::disk('public')->delete($heroSlide->getRawOriginal('image_mobile'));
            }
            $data['image_mobile'] = $this->imageOptimizer->storeAndOptimize($request->file('image_mobile'), 'hero-slides', ['maxWidth' => 1080, 'quality' => 82]);
        }

        $heroSlide->update($data);
        $this->homeCache->forgetHome();
        $this->homeCache->forgetSection('hero');

        return back()->with('success', __('تم تعديل الشريحة بنجاح'));
    }

    public function destroy(HeroSlide $heroSlide)
    {
        if ($heroSlide->getRawOriginal('image_desktop')) {
            Storage::disk('public')->delete($heroSlide->getRawOriginal('image_desktop'));
        }
        if ($heroSlide->getRawOriginal('image_mobile')) {
            Storage::disk('public')->delete($heroSlide->getRawOriginal('image_mobile'));
        }

        $heroSlide->delete();
        $this->homeCache->forgetHome();
        $this->homeCache->forgetSection('hero');

        return back()->with('success', __('تم حذف الشريحة بنجاح'));
    }
}
