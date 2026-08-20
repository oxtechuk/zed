<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\HomeSection;
use App\Services\Cache\HomeCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeSectionController extends Controller
{
    public function __construct(
        private readonly HomeCacheService $homeCache,
    ) {}

    /**
     * The homepage sections are fixed (one row per section key) — this screen only edits them.
     */
    public function index()
    {
        $activeKeys = ['featured_banner', 'search', 'featured_cars', 'offers', 'budget', 'finance'];
        $sections = HomeSection::query()
            ->whereIn('key', $activeKeys)
            ->orderBy('sort_order')
            ->get();

        return view('crm.settings.home-sections.index', compact('sections'));
    }

    public function update(Request $request, HomeSection $homeSection)
    {
        $data = $request->validate([
            'title' => 'nullable|array',
            'title.ar' => 'nullable|string|max:255',
            'title.en' => 'nullable|string|max:255',
            'subtitle' => 'nullable|array',
            'subtitle.ar' => 'nullable|string|max:255',
            'subtitle.en' => 'nullable|string|max:255',
            'description' => 'nullable|array',
            'description.ar' => 'nullable|string',
            'description.en' => 'nullable|string',
            'badge' => 'nullable|array',
            'badge.ar' => 'nullable|string|max:100',
            'badge.en' => 'nullable|string|max:100',
            'extra_tag' => 'nullable|array',
            'extra_tag.ar' => 'nullable|string|max:100',
            'extra_tag.en' => 'nullable|string|max:100',
            'disclaimer' => 'nullable|array',
            'disclaimer.ar' => 'nullable|string|max:255',
            'disclaimer.en' => 'nullable|string|max:255',
            'countdown_end' => 'nullable|date',
            'button_text' => 'nullable|array',
            'button_text.ar' => 'nullable|string|max:100',
            'button_text.en' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:4096',
            'background_image' => 'nullable|image|max:4096',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($homeSection->getRawOriginal('image')) {
                Storage::disk('public')->delete($homeSection->getRawOriginal('image'));
            }
            $data['image'] = $request->file('image')->store('home-sections', 'public');
        }

        if ($request->hasFile('background_image')) {
            if ($homeSection->getRawOriginal('background_image')) {
                Storage::disk('public')->delete($homeSection->getRawOriginal('background_image'));
            }
            $data['background_image'] = $request->file('background_image')->store('home-sections', 'public');
        }

        $homeSection->update($data);
        $this->homeCache->forgetSection($homeSection->key);

        return back()->with('success', __('تم تحديث قسم الصفحة الرئيسية بنجاح'));
    }
}
