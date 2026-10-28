<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Api\Contracts\ApiResponse;
use App\Http\Api\Response\Builder\ApiResponseBuilder;
use App\Http\Controllers\Api\ApiBaseController;
use App\Services\Seo\SeoEntryResolver;
use Illuminate\Http\Request;

final class SeoEntryController extends ApiBaseController
{
    public function __construct(
        private readonly SeoEntryResolver $resolver,
    ) {
        parent::__construct(app(ApiResponseBuilder::class));
    }

    public function show(Request $request): ApiResponse
    {
        $entry = $this->resolver->resolve((string) $request->query('path', '/'));

        return $this->respondSuccess(
            $entry ? [
                'meta_title' => $entry['meta_title'],
                'meta_description' => $entry['meta_description'],
                'intro' => $entry['intro'],
                'faq' => $entry['faq'],
                'image_alt' => $entry['image_alt'],
            ] : null,
            'SEO entry retrieved successfully'
        );
    }
}
