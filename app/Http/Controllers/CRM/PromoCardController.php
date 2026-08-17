<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\PromoCard;
use App\Services\Cache\HomeCacheService;
use App\Services\ImageOptimizerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromoCardController extends Controller
{
    public function __construct(
        private readonly HomeCacheService $homeCache,
        private readonly ImageOptimizerService $imageOptimizer,
    ) {}

    public function index()
    {
        $cards = PromoCard::query()->orderBy('sort_order')->get();

        return view('crm.settings.promo-cards.index', compact('cards'));
    }

    private function rules(bool $isCreate): array
    {
        return [
            'type' => 'required|in:'.implode(',', PromoCard::TYPES),
            'title.ar' => 'required|string|max:255',
            'title.en' => 'required|string|max:255',
            'subtitle.ar' => 'nullable|string|max:255',
            'subtitle.en' => 'nullable|string|max:255',
            'image' => ($isCreate ? 'required' : 'nullable').'|image|max:8192',
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

        if ($request->hasFile('image')) {
            $data['image'] = $this->imageOptimizer->storeAndOptimize($request->file('image'), 'promo-cards', ['maxWidth' => 1200, 'quality' => 82]);
        }

        PromoCard::create($data);
        $this->homeCache->forgetSection('promo');

        return back()->with('success', __('تمت إضافة البطاقة الترويجية بنجاح'));
    }

    public function update(Request $request, PromoCard $promoCard)
    {
        $data = $request->validate($this->rules(isCreate: false));
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($promoCard->getRawOriginal('image')) {
                Storage::disk('public')->delete($promoCard->getRawOriginal('image'));
            }
            $data['image'] = $this->imageOptimizer->storeAndOptimize($request->file('image'), 'promo-cards', ['maxWidth' => 1200, 'quality' => 82]);
        }

        $promoCard->update($data);
        $this->homeCache->forgetSection('promo');

        return back()->with('success', __('تم تعديل البطاقة الترويجية بنجاح'));
    }

    public function destroy(PromoCard $promoCard)
    {
        if ($promoCard->getRawOriginal('image')) {
            Storage::disk('public')->delete($promoCard->getRawOriginal('image'));
        }

        $promoCard->delete();
        $this->homeCache->forgetSection('promo');

        return back()->with('success', __('تم حذف البطاقة الترويجية بنجاح'));
    }
}
