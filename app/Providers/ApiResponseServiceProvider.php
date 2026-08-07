<?php

namespace App\Providers;

use App\Http\Api\Response\Builder\ApiResponseBuilder;
use App\Http\Api\Response\Builder\ErrorResponseBuilder;
use App\Http\Api\Response\Builder\SuccessResponseBuilder;
use Illuminate\Support\ServiceProvider;

class ApiResponseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ApiResponseBuilder::class);
        $this->app->singleton(SuccessResponseBuilder::class);
        $this->app->singleton(ErrorResponseBuilder::class);
    }
}
