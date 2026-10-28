{{-- Plain HTML version of the page, rendered inside #root before React mounts
     (React replaces it). Crawlers that don't run JavaScript read this. --}}
<div class="ssr-page" dir="rtl" lang="ar">
    <style>
        .ssr-page{font-family:Cairo,system-ui,sans-serif;color:#16254F;background:#F0F2F5;min-height:100vh;line-height:1.9}
        .ssr-page a{color:#163F8B}
        .ssr-wrap{max-width:960px;margin:0 auto;padding:16px}
        .ssr-head{display:flex;flex-wrap:wrap;gap:12px 20px;align-items:center;padding:12px 0;border-bottom:1px solid #D9DEE7}
        .ssr-head strong{font-size:20px}
        .ssr-crumbs{font-size:14px;margin:12px 0;color:#5B6475}
        .ssr-page h1{font-size:28px;margin:12px 0}
        .ssr-facts{display:grid;grid-template-columns:max-content 1fr;gap:4px 16px;margin:16px 0}
        .ssr-facts dt{font-weight:700}
        .ssr-facts dd{margin:0}
        .ssr-page img{max-width:100%;height:auto}
        .ssr-page li{margin:4px 0}
        .ssr-meta{color:#5B6475;font-size:14px}
        .ssr-foot{margin-top:32px;padding-top:12px;border-top:1px solid #D9DEE7;font-size:14px}
    </style>
    <div class="ssr-wrap">
        <header class="ssr-head">
            <strong><a href="https://zad-capital.sa/">زاد كابيتال</a></strong>
            <a href="https://zad-capital.sa/cars">السيارات</a>
            <a href="https://zad-capital.sa/offers">العروض</a>
            <a href="https://zad-capital.sa/finance-calculator">حاسبة التمويل</a>
            <a href="https://zad-capital.sa/blog">المدونة</a>
            <a href="https://zad-capital.sa/about">من نحن</a>
            <a href="https://zad-capital.sa/contact">تواصل معنا</a>
        </header>

        @if (! empty($seoContent['breadcrumb']))
            <nav class="ssr-crumbs" aria-label="مسار التنقل">
                @foreach ($seoContent['breadcrumb'] as $crumb)
                    @if ($crumb['url'])<a href="{{ $crumb['url'] }}">{{ $crumb['name'] }}</a> ›@else<span>{{ $crumb['name'] }}</span>@endif
                @endforeach
            </nav>
        @endif

        <main>
            <h1>{{ $seoContent['h1'] }}</h1>

            @if (! empty($seoContent['facts']))
                <dl class="ssr-facts">
                    @foreach ($seoContent['facts'] as [$label, $value])
                        <dt>{{ $label }}</dt><dd>{{ $value }}</dd>
                    @endforeach
                </dl>
            @endif

            @foreach ($seoContent['intro'] as $paragraph)
                <p>{{ $paragraph }}</p>
            @endforeach

            @if (! empty($seoContent['html']))
                <article>{!! $seoContent['html'] !!}</article>
            @endif

            @foreach ($seoContent['sections'] as $section)
                <section>
                    <h2>{{ $section['title'] }}</h2>
                    <ul>
                        @foreach ($section['items'] as $item)
                            <li>
                                <a href="{{ $item['url'] }}">{{ $item['text'] }}</a>
                                @if (! empty($item['meta']))<span class="ssr-meta"> · {{ $item['meta'] }}</span>@endif
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endforeach
        </main>

        <footer class="ssr-foot">
            <p>
                زاد كابيتال لتمويل السيارات بالتقسيط في السعودية
                · الهاتف الموحد: <a href="tel:{{ $seoContent['contact']['phone'] }}">{{ $seoContent['contact']['phone'] }}</a>
                @if (! empty($seoContent['contact']['whatsapp'])) · واتساب: {{ $seoContent['contact']['whatsapp'] }}@endif
                @if (! empty($seoContent['contact']['email'])) · <a href="mailto:{{ $seoContent['contact']['email'] }}">{{ $seoContent['contact']['email'] }}</a>@endif
            </p>
            @if (! empty($seoContent['contact']['address']))<p>{{ $seoContent['contact']['address'] }}</p>@endif
            <p><a href="https://zad-capital.sa/privacy">سياسة الخصوصية</a> · <a href="https://zad-capital.sa/terms">الشروط والأحكام</a></p>
        </footer>
    </div>
</div>
