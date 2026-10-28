<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Api\Response\Builder\ApiResponseBuilder;
use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\Store\BlogCategoryResource;
use App\Http\Resources\Store\BlogPostDetailResource;
use App\Http\Resources\Store\BlogPostResource;
use App\Models\Car;
use App\Services\Api\Store\BlogApiService;
use App\Services\Api\Store\Helpers\SlugResolver;
use Illuminate\Http\Request;

final class BlogController extends ApiBaseController
{
    public function __construct(
        private readonly BlogApiService $blogService,
    ) {
        parent::__construct(app(ApiResponseBuilder::class));
    }

    public function index(Request $request)
    {
        $page = (int) $request->get('page', 1);
        $perPage = (int) $request->get('per_page', 10);
        $category = $request->get('category');
        $tag = $request->get('tag');
        $result = $this->blogService->list($page, $perPage, $category, $tag);

        $posts = BlogPostResource::collection($result['posts'])->resolve();
        $featuredPosts = BlogPostResource::collection($result['featured_posts'])->resolve();
        $categories = BlogCategoryResource::collection($this->blogService->categories())->resolve();

        $meta = $result['meta'] ?? [];
        $meta['hero'] = $result['hero'] ?? null;
        $meta['featured_posts'] = $featuredPosts;
        $meta['categories'] = $categories;

        return $this->responseBuilder
            ->success(true)
            ->message('Blog posts retrieved successfully')
            ->data($posts)
            ->meta($meta)
            ->build();
    }

    public function show(string $slug)
    {
        $result = $this->blogService->findBySlug($slug);
        $result['post']->setRelation('relatedPosts', $result['related']);

        return $this->respondSuccess(
            BlogPostDetailResource::make($result['post'])->resolve(),
            'Blog post retrieved successfully'
        );
    }

    /** Articles that link to a car page ("مقالات عن هذه السيارة"). */
    public function forCar(string $slug)
    {
        $query = Car::where('is_active', true);
        SlugResolver::applyCarSlug($query, urldecode($slug));
        $car = $query->first();

        $posts = $car ? $this->blogService->linkingToCar((string) $car->getTranslation('slug', 'ar')) : collect();

        return $this->respondSuccess(
            BlogPostResource::collection($posts)->resolve(),
            'Car articles retrieved successfully'
        );
    }

    public function categories()
    {
        return $this->respondSuccess(
            BlogCategoryResource::collection($this->blogService->categories())->resolve(),
            'Blog categories retrieved successfully'
        );
    }
}
