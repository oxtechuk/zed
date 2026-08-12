<?php

declare(strict_types=1);

namespace App\Services\Api\Store;

use App\Models\BlogPost;
use App\Services\Cache\BlogCacheService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final class BlogApiService
{
    public function __construct(
        private readonly BlogCacheService $cache,
    ) {}

    public function list(int $page = 1, int $perPage = 10, ?string $categorySlug = null, ?string $tag = null): array
    {
        $hero = $this->cache->rememberBlogHero();

        if ($categorySlug || ($tag && $tag !== 'all')) {
            $featuredPosts = BlogPost::published()
                ->with(['categories', 'employee'])
                ->when($categorySlug, fn ($q) => $q->whereHas('categories', fn ($cq) => $cq->where('slug', $categorySlug)))
                ->when($tag && $tag !== 'all', fn ($q) => $q->where('tag', $tag))
                ->where('is_featured', true)
                ->latest('published_at')
                ->take(3)
                ->get();

            $featuredIds = $featuredPosts->pluck('id');

            $posts = BlogPost::published()
                ->with(['categories', 'employee'])
                ->when($categorySlug, fn ($q) => $q->whereHas('categories', fn ($cq) => $cq->where('slug', $categorySlug)))
                ->when($tag && $tag !== 'all', fn ($q) => $q->where('tag', $tag))
                ->when($featuredIds->isNotEmpty(), fn ($q) => $q->whereNotIn('id', $featuredIds))
                ->latest('published_at')
                ->paginate($perPage);

            return [
                'hero' => $hero,
                'featured_posts' => $featuredPosts,
                'posts' => $posts,
                'meta' => $posts instanceof LengthAwarePaginator ? [
                    'current_page' => $posts->currentPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total(),
                    'last_page' => $posts->lastPage(),
                ] : null,
            ];
        }

        $data = $this->cache->rememberBlogIndex($page, $perPage);

        $featuredPosts = $data['featuredPosts'] ?? collect();
        $posts = $data['posts'] ?? collect();

        return [
            'hero' => $hero,
            'featured_posts' => $featuredPosts,
            'posts' => $posts,
            'meta' => $posts instanceof LengthAwarePaginator ? [
                'current_page' => $posts->currentPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
                'last_page' => $posts->lastPage(),
            ] : null,
        ];
    }

    public function findBySlug(string $slug): array
    {
        $post = BlogPost::published()
            ->with('categories', 'employee')
            ->where('slug', $slug)
            ->firstOrFail();

        $related = BlogPost::published()
            ->with('categories', 'employee')
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return compact('post', 'related');
    }

    public function categories(): Collection
    {
        return $this->cache->rememberBlogCategories();
    }
}
