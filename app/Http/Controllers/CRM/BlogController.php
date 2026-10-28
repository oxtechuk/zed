<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Services\ImageOptimizerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function __construct(
        protected ImageOptimizerService $imageOptimizer
    ) {}

    public function index()
    {
        $posts = BlogPost::with('employee')->latest()->paginate(15);

        return view('crm.blog.index', compact('posts'));
    }

    public function create()
    {
        $categories = BlogCategory::activeOrdered()->get();

        return view('crm.blog.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|array',
            'title.ar' => 'required|string|max:255',
            'title.en' => 'nullable|string|max:255',
            'excerpt' => 'nullable|array',
            'excerpt.ar' => 'nullable|string|max:500',
            'excerpt.en' => 'nullable|string|max:500',
            'content' => 'required|array',
            'content.ar' => 'required|string',
            'content.en' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:8192',
            'slug' => 'nullable|string|max:191',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:blog_categories,id',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'tag' => 'nullable|string|in:popular,exclusive,new,limited',
        ]);

        $data = $this->fillMissingEnglish($data);
        $data['slug'] = $this->uniqueSlug($request->input('slug') ?: $data['title']['ar']);
        $data['employee_id'] = auth('employee')->id();
        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['published_at'] = $data['is_published'] ? now() : null;

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $this->imageOptimizer->storeAndOptimize($request->file('thumbnail'), 'blog', ['maxWidth' => 1200, 'quality' => 82]);
        }

        $post = BlogPost::create($data);
        $post->categories()->sync($request->input('categories', []));

        return redirect()->route('crm.blog.index')->with('success', 'تم نشر المقالة');
    }

    public function edit(BlogPost $blog)
    {
        $categories = BlogCategory::activeOrdered()->get();

        return view('crm.blog.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, BlogPost $blog)
    {
        $data = $request->validate([
            'title' => 'required|array',
            'title.ar' => 'required|string|max:255',
            'title.en' => 'nullable|string|max:255',
            'excerpt' => 'nullable|array',
            'excerpt.ar' => 'nullable|string|max:500',
            'excerpt.en' => 'nullable|string|max:500',
            'content' => 'required|array',
            'content.ar' => 'required|string',
            'content.en' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:5120',
            'slug' => 'nullable|string|max:191',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:blog_categories,id',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'tag' => 'nullable|string|in:popular,exclusive,new,limited',
        ]);

        $data = $this->fillMissingEnglish($data);
        unset($data['slug']);
        if ($request->filled('slug') && self::slugify($request->input('slug')) !== $blog->slug) {
            $data['slug'] = $this->uniqueSlug($request->input('slug'), $blog->id);
        }

        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured'] = $request->boolean('is_featured');
        if ($data['is_published'] && ! $blog->published_at) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('thumbnail')) {
            if ($blog->thumbnail) {
                Storage::disk('public')->delete($blog->thumbnail);
            }
            $data['thumbnail'] = $this->imageOptimizer->storeAndOptimize($request->file('thumbnail'), 'blog', ['maxWidth' => 1200, 'quality' => 82]);
        }

        $blog->update($data);
        $blog->categories()->sync($request->input('categories', []));

        return redirect()->route('crm.blog.index')->with('success', 'تم تحديث المقالة');
    }

    public function destroy(BlogPost $blog)
    {
        if ($blog->thumbnail) {
            Storage::disk('public')->delete($blog->thumbnail);
        }
        $blog->delete();

        return redirect()->route('crm.blog.index')->with('success', 'تم حذف المقالة');
    }

    public function toggleFeatured(BlogPost $blog)
    {
        $blog->update(['is_featured' => ! $blog->is_featured]);

        return redirect()->back()->with('success', 'تم تعديل حالة تمييز المقالة بنجاح');
    }

    /** Arabic-friendly URL slug: letters/digits in any script, words joined by "-". */
    private static function slugify(string $text): string
    {
        $slug = preg_replace('/[^\p{L}\p{N}]+/u', '-', mb_strtolower(trim($text), 'UTF-8'));

        return trim((string) preg_replace('/-+/', '-', (string) $slug), '-');
    }

    private function uniqueSlug(string $text, ?int $ignoreId = null): string
    {
        $base = self::slugify($text) ?: 'post';
        $slug = $base;
        $counter = 2;

        while (BlogPost::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }

    /** The English fields are optional; fall back to the Arabic text so the English view is never empty. */
    private function fillMissingEnglish(array $data): array
    {
        foreach (['title', 'excerpt', 'content'] as $field) {
            if (isset($data[$field]) && is_array($data[$field]) && blank($data[$field]['en'] ?? null)) {
                $data[$field]['en'] = $data[$field]['ar'] ?? null;
            }
        }

        return $data;
    }
}
