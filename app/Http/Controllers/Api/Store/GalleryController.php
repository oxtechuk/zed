<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Api\Response\Builder\ApiResponseBuilder;
use App\Http\Controllers\Api\ApiBaseController;
use App\Services\Api\Store\GalleryApiService;

final class GalleryController extends ApiBaseController
{
    public function __construct(
        private readonly GalleryApiService $galleryService,
    ) {
        parent::__construct(app(ApiResponseBuilder::class));
    }

    public function __invoke()
    {
        return $this->respondSuccess(
            $this->galleryService->gallery(),
            'Gallery retrieved successfully'
        );
    }
}
