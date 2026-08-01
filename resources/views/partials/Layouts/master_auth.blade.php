<!DOCTYPE html>
<html lang="en">

<meta charset="utf-8" />
@php
    $siteNameSetting = \App\Models\Setting::where('key', 'site_name')->first()?->value;
    $siteNameText = is_array($siteNameSetting) ? ($siteNameSetting[app()->getLocale()] ?? ($siteNameSetting['ar'] ?? 'زد كابيتال')) : ($siteNameSetting ?? 'زد كابيتال');
    $siteFavicon = \App\Models\Setting::where('key', 'site_favicon')->first()?->value;
@endphp
<title>@yield('title', ' | ' . $siteNameText . ' CRM')</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta content="{{ $siteNameText }} CRM" name="description" />
<meta content="{{ $siteNameText }}" name="author" />

<!-- layout setup -->
<script type="module" src="{{ asset('assets/js/layout-setup.js') }}"></script>

<!-- App favicon -->
@if($siteFavicon)
    <link rel="shortcut icon" href="{{ asset('storage/' . $siteFavicon) }}">
    <link rel="icon" type="image/png" href="{{ asset('storage/' . $siteFavicon) }}">
@else
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
@endif

@yield('css')
@include('partials.head-css') 

<body>

@yield('content')

@include('partials.vendor-scripts')  

@yield('js')

</body>

</html>