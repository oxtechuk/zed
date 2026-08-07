<?php

namespace Tests\Feature;

use Illuminate\Http\Exceptions\PostTooLargeException;
use Tests\TestCase;

class ExceptionHandlingTest extends TestCase
{
    public function test_post_too_large_exception_redirects_back_with_error_for_web_requests(): void
    {
        $this->app['router']->post('/test-post-too-large', function () {
            throw new PostTooLargeException;
        });

        $response = $this->from('/previous-page')
            ->post('/test-post-too-large');

        $response->assertRedirect('/previous-page');
        $response->assertSessionHas('error', 'حجم الملف المرفوع كبير جداً. يرجى رفع ملف بحجم أصغر.');
    }

    public function test_post_too_large_exception_redirects_back_with_english_error(): void
    {
        $this->app['router']->post('/test-post-too-large', function () {
            throw new PostTooLargeException;
        });

        $response = $this->from('/previous-page')
            ->withSession(['applocale' => 'en'])
            ->post('/test-post-too-large');

        $response->assertRedirect('/previous-page');
        $response->assertSessionHas('error', 'The uploaded file is too large. Please upload a smaller file.');
    }

    public function test_post_too_large_exception_returns_json_for_api_requests(): void
    {
        $this->app['router']->post('/api/test-post-too-large', function () {
            throw new PostTooLargeException;
        });

        $response = $this->postJson('/api/test-post-too-large', [], [
            'Accept-Language' => 'ar',
        ]);

        $response->assertStatus(413);
        $response->assertJson([
            'success' => false,
            'message' => 'حجم الملف المرفوع كبير جداً. يرجى رفع ملف بحجم أصغر.',
            'status' => 413,
        ]);
    }

    public function test_post_too_large_exception_returns_json_in_english(): void
    {
        $this->app['router']->post('/api/test-post-too-large', function () {
            throw new PostTooLargeException;
        });

        $response = $this->postJson('/api/test-post-too-large', [], [
            'Accept-Language' => 'en',
        ]);

        $response->assertStatus(413);
        $response->assertJson([
            'success' => false,
            'message' => 'The uploaded file is too large. Please upload a smaller file.',
            'status' => 413,
        ]);
    }
}
