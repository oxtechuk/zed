<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::all();

        return view('crm.settings.testimonials.index', compact('testimonials'));
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'review_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'review_video' => 'nullable|file|mimes:mp4,mov,ogg,qt,webm|max:20480',
        ]);

        $data = $request->except(['image', 'review_image', 'review_video']);
        $data['is_visible'] = $request->has('is_visible');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('testimonials', 'public');
        }

        if ($request->hasFile('review_image')) {
            $data['review_image'] = $request->file('review_image')->store('testimonials/reviews', 'public');
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'review_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'review_video' => 'nullable|file|mimes:mp4,mov,ogg,qt,webm|max:20480',
        ]);

        $data = $request->except(['image', 'review_image', 'review_video']);
        $data['is_visible'] = $request->has('is_visible');

        if ($request->hasFile('image')) {
            if ($testimonial->image) {
                Storage::disk('public')->delete($testimonial->image);
            }
            $data['image'] = $request->file('image')->store('testimonials', 'public');
        }

        if ($request->hasFile('review_image')) {
            if ($testimonial->review_image) {
                Storage::disk('public')->delete($testimonial->review_image);
            }
            $data['review_image'] = $request->file('review_image')->store('testimonials/reviews', 'public');
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
