<!DOCTYPE html>
<html lang="{{get_user_lang()}}" dir="{{get_user_lang_direction()}}">
<head>

   @if(!empty(get_static_option('site_google_analytics')))
        {!! get_static_option('site_google_analytics') !!}
    @endif
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('feed::links')



    {!! render_favicon_by_id(get_static_option('site_favicon')) !!}
    {!! load_google_fonts() !!}
        <link rel="preload" href="{{asset('assets/frontend/fonts/la-brands-400.woff2')}}" as="font" type="font/woff2" crossorigin> 
        <link rel="preload" href="{{asset('assets/frontend/fonts/la-solid-900.woff2')}}" as="font" type="font/woff2" crossorigin> 
        <link rel="preload" href="{{asset('assets/frontend/fonts/la-regular-400.woff2')}}" as="font" type="font/woff2" crossorigin> 
       <link rel="stylesheet" href="{{asset('assets/frontend/css/compress.min.css')}}">
       <link rel="stylesheet" href="{{asset('assets/frontend/css/dynamic-style.css')}}">


    {{-- Dark Mode--}}
    @if(get_static_option('site_frontend_dark_mode') === 'on')
           <link rel="stylesheet" href="{{asset('assets/frontend/css/dark.css')}}">
    @endif
   @if(!empty(get_static_option('site_rtl_enabled')) || get_user_lang_direction() === 'rtl')
       <link rel="stylesheet" href="{{asset('assets/frontend/css/rtl.css')}}">
   @endif

    <link rel="canonical" href="{{request()->url()}}" />
    <script rel="preload" src="{{asset('assets/common/js/jquery-3.6.0.min.js')}}"></script>
    <script rel="preload" src="{{asset('assets/common/js/jquery-migrate-3.3.2.min.js')}}"></script>

    {{--Google Add Sense Script--}}
       @if(get_static_option('google_adsense_publisher_id'))
        <script rel="preload" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{get_static_option('google_adsense_publisher_id')}}" crossorigin="anonymous"></script>
       @endif


    @include('frontend.partials.root-style')
    @yield('style')


      @if(request()->routeIs('homepage') || request()->is('/') )
        <title>{{get_static_option('site_'.$user_select_lang_slug.'_title')}} - {{get_static_option('site_'.$user_select_lang_slug.'_tag_line')}}</title>
           {!! render_site_meta() !!}

       @elseif( request()->routeIs('frontend.dynamic.page') && isset($page_post))
           {!! render_site_title($page_post->title) !!}
           {!! render_site_meta() !!}

        @else
            @yield('page-meta-data')
           <title> @yield('site-title') - {{get_static_option('site_'.$user_select_lang_slug.'_tag_line')}} </title>
        @endif


</head>
@php
    $class = '';
    if(request()->routeIs('homepage')){
        $class = 'dark index-01-dark';
    }elseif (request()->is('home-01')){
        $class = 'dark index-01-dark';

    }elseif (request()->is('home-02-6')){
        $class = 'dark index-02-dark';

    }elseif (request()->is('home-03')){
        $class = 'dark index-03-dark';

    }elseif (request()->is('home-04')){
        $class = 'dark index-04-dark';

    }elseif (request()->is('home-05')){
        $class = 'dark index-05-dark';
    }else{
        $class = 'dark';
    }

$dark_mode_on = get_static_option('site_frontend_dark_mode') === 'on';
$condition = $dark_mode_on ? $class : '';
@endphp
<body class="black-theme {{$condition}}">

@if(get_static_option('site_loader_animation'))
    <div class="preloader-inner">
        <div class="preloader-main-gif">
            <img src="{{asset('assets/frontend/img/preloader/fidget-spinner.gif')}}" alt="">
        </div>
    </div>
@endif

@include('frontend.partials.navbar')

