{{-- Plain HTML version of the page, rendered inside #root before React mounts
     (React replaces it). Visually hidden, so visitors see no change; crawlers
     that don't run JavaScript (AI search bots) read it. --}}
<div class="ssr-page" dir="rtl" lang="ar">
    <style>
        /* Not shown to visitors (the app replaces #root on mount); kept in the HTML for crawlers. */
        .ssr-page{position:absolute;width:1px;height:1px;margin:-1px;padding:0;overflow:hidden;clip:rect(0,0,0,0);clip-path:inset(50%);border:0}
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
