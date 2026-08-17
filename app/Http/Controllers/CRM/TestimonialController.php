<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Services\ImageOptimizerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function __construct(
        protected ImageOptimizerService $imageOptimizer
    ) {}

    public function index()
    {
        $textTestimonials = Testimonial::where('type', 'text')->latest()->get();
        $videoTestimonials = Testimonial::where('type', 'video')->latest()->get();

        return view('crm.settings.testimonials.index', compact('textTestimonials', 'videoTestimonials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('crm.settings.testimonials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|array',
            'title' => 'nullable|array',
            'content' => 'required|array',
            'rating' => 'required|integer|min:1|max:5',
            'type' => 'required|string|in:text,video',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:8192',
            'review_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:8192',
            'review_video' => 'nullable|file|mimes:mp4,mov,ogg,qt,webm|max:20480',
        ]);

        $data = $request->except(['image', 'review_image', 'review_video']);
        $data['is_visible'] = $request->has('is_visible');

        if ($request->hasFile('image')) {
            $data['image'] = $this->imageOptimizer->storeAndOptimize($request->file('image'), 'testimonials', ['maxWidth' => 600, 'quality' => 85]);
        }

        if ($request->hasFile('review_image')) {
            $data['review_image'] = $this->imageOptimizer->storeAndOptimize($request->file('review_image'), 'testimonials/reviews', ['maxWidth' => 1200, 'quality' => 82]);
        }

        if ($request->hasFile('review_video')) {
            $data['review_video'] = $request->file('review_video')->store('testimonials/videos', 'public');
        }

        Testimonial::create($data);

        return redirect()->route('crm.settings.testimonials.index')->with('success', __('تم إضافة التوصية بنجاح'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        return view('crm.settings.testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $request->validate([
            'name' => 'required|array',
            'title' => 'nullable|array',
            'content' => 'required|array',
            'rating' => 'required|integer|min:1|max:5',
            'type' => 'required|string|in:text,video',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:8192',
            'review_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:8192',
            'review_video' => 'nullable|file|mimes:mp4,mov,ogg,qt,webm|max:20480',
        ]);

        $data = $request->except(['image', 'review_image', 'review_video']);
        $data['is_visible'] = $request->has('is_visible');

        if ($request->hasFile('image')) {
            if ($testimonial->image) {
                Storage::disk('public')->delete($testimonial->image);
            }
            $data['image'] = $this->imageOptimizer->storeAndOptimize($request->file('image'), 'testimonials', ['maxWidth' => 600, 'quality' => 85]);
        }

        if ($request->hasFile('review_image')) {
            if ($testimonial->review_image) {
                Storage::disk('public')->delete($testimonial->review_image);
            }
            $data['review_image'] = $this->imageOptimizer->storeAndOptimize($request->file('review_image'), 'testimonials/reviews', ['maxWidth' => 1200, 'quality' => 82]);
        }

        if ($request->hasFile('review_video')) {
            if ($testimonial->review_video) {
                Storage::disk('public')->delete($testimonial->review_video);
            }
            $data['review_video'] = $request->file('review_video')->store('testimonials/videos', 'public');
        }

        $testimonial->update($data);

        return redirect()->route('crm.settings.testimonials.index')->with('success', __('تم تعديل التوصية بنجاح'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        if ($testimonial->image) {
            Storage::disk('public')->delete($testimonial->image);
        }

        if ($testimonial->review_image) {
            Storage::disk('public')->delete($testimonial->review_image);
        }

        if ($testimonial->review_video) {
            Storage::disk('public')->delete($testimonial->review_video);
        }

        $testimonial->delete();

        return back()->with('success', __('تم حذف التوصية بنجاح'));
    }
}
