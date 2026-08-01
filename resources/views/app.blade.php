<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Zed Capital</title>
    @if (file_exists(public_path('hot')))
        @vite('resources/react/main.tsx')
    @else
        @php
            $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
            $entry = $manifest['resources/react/main.tsx'] ?? null;
        @endphp
        @if ($entry)
            @if (!empty($entry['css']))
                @foreach ($entry['css'] as $css)
                    <link rel="stylesheet" href="/build/{{ $css }}" />
                @endforeach
            @endif
            <script type="module" src="/build/{{ $entry['file'] }}"></script>
        @endif
    @endif
</head>
<body>
    <div id="root"></div>
    <script src="https://cdn.jsdelivr.net/npm/lazysizes@5.3.2/lazysizes.min.js" async></script>
</body>
</html>
