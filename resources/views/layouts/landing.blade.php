<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-5P4CCK62');</script>
    <!-- End Google Tag Manager -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $__favicon      = \App\Models\Setting::get('site_favicon');
        $__seoTitle     = \App\Models\Setting::get('seo_title');
        $__seoDesc      = \App\Models\Setting::get('seo_description');
        $__seoOg        = \App\Models\Setting::get('seo_og_image');
        $__seoGa        = \App\Models\Setting::get('seo_google_analytics');
        $__seoGoogleVerify = \App\Models\Setting::get('seo_google_verification');
        $__siteName     = $__seoTitle ?: config('pw-config.server.name') ?: 'Perfect World';
        $__pageTitle    = trim(\Illuminate\Support\Facades\View::yieldContent('title'));
        $__metaTitle    = $__pageTitle ?: $__siteName;
        $__metaDesc     = trim(\Illuminate\Support\Facades\View::yieldContent('meta_description')) ?: ($__seoDesc ?: '');
    @endphp

    <title>{{ $__metaTitle }}</title>
    <meta name="description" content="{{ $__metaDesc }}">

    @if($__seoGoogleVerify)
    <meta name="google-site-verification" content="{{ $__seoGoogleVerify }}">
    @endif
    @if($__favicon)
    <link rel="icon" type="image/png" href="{{ Storage::url($__favicon) }}">
    @else
    <link rel="icon" href="/favicon.ico">
    @endif

    {{-- Open Graph --}}
    <meta property="og:type"        content="website">
    <meta property="og:site_name"   content="{{ $__siteName }}">
    <meta property="og:title"       content="{{ $__metaTitle }}">
    <meta property="og:description" content="{{ $__metaDesc }}">
    @if($__seoOg)
    <meta property="og:image"       content="{{ Storage::url($__seoOg) }}">
    @endif
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ $__metaTitle }}">
    <meta name="twitter:description" content="{{ $__metaDesc }}">
    @if($__seoOg)
    <meta name="twitter:image"       content="{{ Storage::url($__seoOg) }}">
    @endif

    {{-- Google Analytics --}}
    @if($__seoGa)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $__seoGa }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ $__seoGa }}');</script>
    @endif

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Exo+2:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Exo+2:wght@300;400;500;600;700&display=swap" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Exo+2:wght@300;400;500;600;700&display=swap"></noscript>

    {{-- Theme: prevent FOUC --}}
    <script>!function(){var t=localStorage.getItem('pw-theme')||'light';document.documentElement.setAttribute('data-theme',t);}()</script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="pw-body" style="padding-bottom:0;">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5P4CCK62"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    @yield('content')

    @include('components.confirm-dialog')
    @include('components.social-proof-widget')
    @stack('scripts')

</body>
</html>
