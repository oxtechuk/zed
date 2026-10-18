{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($staticPages as $page)
    <url>
        <loc>{{ $page['loc'] }}</loc>
        <changefreq>{{ $page['changefreq'] }}</changefreq>
        <priority>{{ $page['priority'] }}</priority>
    </url>
@endforeach
@foreach ($cars as $car)
    @php
        $rawSlug = $car->getTranslation('slug', 'ar') ?: (is_array($car->slug) ? ($car->slug['ar'] ?? reset($car->slug)) : $car->slug);
    @endphp
    @if (! empty($rawSlug))
    <url>
        <loc>{{ $baseUrl }}/cars/{{ \App\Http\Controllers\Store\SitemapController::encodeSlug((string) $rawSlug) }}</loc>
        <lastmod>{{ optional($car->updated_at)->toAtomString() ?? now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endif
@endforeach
@foreach ($blogs as $blog)
    @php
        $rawBlogSlug = $blog->getTranslation('slug', 'ar') ?: (is_array($blog->slug) ? ($blog->slug['ar'] ?? reset($blog->slug)) : $blog->slug);
    @endphp
    @if (! empty($rawBlogSlug))
    <url>
        <loc>{{ $baseUrl }}/blog/{{ \App\Http\Controllers\Store\SitemapController::encodeSlug((string) $rawBlogSlug) }}</loc>
        <lastmod>{{ optional($blog->updated_at ?? $blog->published_at)->toAtomString() ?? now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    @endif
@endforeach
@foreach ($brands ?? [] as $brand)
    @php
        $rawBrandSlug = $brand->slug ?: (string) $brand->id;
    @endphp
    @if (! empty($rawBrandSlug))
    <url>
        <loc>{{ $baseUrl }}/brands/{{ \App\Http\Controllers\Store\SitemapController::encodeSlug((string) $rawBrandSlug) }}</loc>
        <lastmod>{{ optional($brand->updated_at)->toAtomString() ?? now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endif
@endforeach
</urlset>
