<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Api\Response\Builder\ApiResponseBuilder;
use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\Store\CarCategoryResource;
use App\Services\Api\Store\CarCategoryApiService;

final class CarCategoryController extends ApiBaseController
{
    public function __construct(
        private readonly CarCategoryApiService $categoryService,
    ) {
        parent::__construct(app(ApiResponseBuilder::class));
    }

    public function index()
    {
        return $this->respondSuccess(
            CarCategoryResource::collection($this->categoryService->list())->resolve(),
            'Car categories retrieved successfully'
        );
    }
}
