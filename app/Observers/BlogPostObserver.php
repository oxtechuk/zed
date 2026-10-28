<?php

namespace App\Observers;

use App\Models\BlogPost;
use App\Services\Cache\BlogCacheService;
use App\Services\Cache\HomeCacheService;
use App\Services\Seo\IndexNowService;

class BlogPostObserver
{
    public function __construct(
        private HomeCacheService $homeCache,
        private BlogCacheService $blogCache,
    ) {}

    public function saved(BlogPost $blogPost): void
    {
        $this->homeCache->forgetHome();
        $this->blogCache->forgetBlog();

        if ($blogPost->is_published && $blogPost->slug) {
            IndexNowService::notifyLater('blog/'.$blogPost->slug);
        }
    }

    public function deleted(BlogPost $blogPost): void
    {
        $this->homeCache->forgetHome();
        $this->blogCache->forgetBlog();
    }

    public function forceDeleted(BlogPost $blogPost): void
    {
        $this->homeCache->forgetHome();
        $this->blogCache->forgetBlog();
    }
}
