@include('frontend.partials.header')

@php
    $custom_class = request()->routeIs('frontend.blog.single') ? 'container-two' : '';
    $breadcrumb_logic =  request()->routeIs('frontend.dynamic.page') || request()->routeIs('frontend.blog.single')
    || request()->routeIs('frontend.blog.category') || request()->routeIs('frontend.blog.tags.page')
    || request()->routeIs('frontend.user.created.blog')  || request()->routeIs('frontend.author.profile')
    || request()->routeIs('frontend.blog.search') || request()->routeIs('frontend.blog.get.search') ? '' : 'custom-container-01';

    $grid_full_page_condition = request()->is('blog-grid-full') ? 'custom-container-01' : '';
@endphp

<div class="breadcrumb-area
@if(
    (in_array(request()->route()->getName(),['homepage','frontend.dynamic.page'])
    && empty($page_post->breadcrumb_status) )
    &&  request()->path() !== get_page_slug(get_static_option('blog_page'),'blog')
    )
        d-none
@endif
">

<div class="container {{$breadcrumb_logic}} {{$grid_full_page_condition}}">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-inner">
                    <div class="content">
                       @hasSection('custom-page-title')
                             <h3 class="title">{!! $page_post->title ?? ''!!} @yield('custom-page-title') </h3>
                        @endif
                        <ul class="page-list">
                            <li class="list-item"><a href="{{url('/')}}">{{ __('Home') }}</a></li>
                            @if(Route::currentRouteName() === 'frontend.dynamic.page' &&  request()->path() !== get_page_slug(get_static_option('blog_page'),'blog'))
                                <li class="list-item"><a href="#">{!! $page_post->title ?? '' !!}</a></li>
                            @else
                                @yield('page-title')
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>



@yield('content')
@include('frontend.partials.footer')

