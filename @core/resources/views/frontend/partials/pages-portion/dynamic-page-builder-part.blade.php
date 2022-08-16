@php
    if(!isset($page_post)){
        return;
    }
@endphp

@if($page_post->layout === 'normal_layout' || $page_post->layout === 'home_page_layout' || $page_post->layout === 'home_page_layout_two' || $page_post->layout === 'home_page_layout_three')
{!! \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page',$page_post->id) !!}
@endif
@if($page_post->layout === 'home_page_layout')
    <div class="parent-area padding-top-70">
        <div class="container {{$page_post->page_class}}">
            <div class="row">
                <div class="col-lg-8">
                    {!! \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_with_sidebar',$page_post->id) !!}
                </div>

                <div class="col-sm-7 col-md-6 col-lg-4">
                    <div class="single-sidebar-item responsive-margin @if(get_static_option('site_frontend_dark_mode') === 'on')   dark-version  @endif">
                        {!! render_frontend_sidebar($page_post->sidebar_layout,['column' => false]) !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endif


@if($page_post->layout === 'home_page_layout_two')
    <div class="recent-stories-area-wrapper index-01" data-padding-top="90" >
            <div class="container custom-container-01">
                <div class="row">
                    <div class="col-xl-8">
                        {!! \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_with_sidebar',$page_post->id) !!}
                    </div>

                    <div class="col-sm-8 col-md-6 col-lg-5 col-xl-4">
                        <div class="widget-area-wrapper style-{{$page_post->widget_style}}">
                            {!! render_frontend_sidebar($page_post->sidebar_layout,['column' => false]) !!}
                        </div>
                    </div>

                </div>
            </div>
        <div class="container-fluid p-0 {{$page_post->page_class}}">
            <div class="col-lg-12">
                {!! \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_with_sidebar_two',$page_post->id) !!}
            </div>
        </div>
    </div>
@endif


@if($page_post->layout === 'home_page_layout_two')
    <div class="parent-area">
        <div class="container custom-container-01">
            <div class="row">
                <div class="col-xl-8">
                    {!! \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_with_sidebar_three',$page_post->id) !!}
                </div>

                <div class="col-sm-10 col-md-6 col-lg-5 col-xl-4" data-padding-bottom="100">
                    <div class="widget-area-wrapper style-{{$page_post->widget_style}}">
                        {!! render_frontend_sidebar($page_post->sidebar_layout_two,['column' => false]) !!}
                    </div>
                </div>

            </div>
        </div>
        <div class="container-fluid p-0 {{$page_post->page_class}}">
            <div class="col-lg-12">
                {!! \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_without_sidebar_two',$page_post->id) !!}
            </div>
        </div>
    </div>
@endif



@if($page_post->layout === 'home_page_layout_three')
    <div class="{{$page_post->page_class}}">
    <div class="recent-stories-area-wrapper index-04" data-padding-top="0" data-padding-bottom="100">
        <div class="container custom-container-01">
            <div class="row">
                <div class="col-xl-9">
                    {!! \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_with_sidebar',$page_post->id) !!}
                </div>

                <div class="col-sm-7 col-md-6 col-lg-6 col-xl-3">
                    <div class="widget-area-wrapper" data-padding-top="100" >
                        {!! render_frontend_sidebar($page_post->sidebar_layout,['column' => false]) !!}
                    </div>
                </div>

            </div>
        </div>
        <div class="container-fluid custom-container-01" data-padding-bottom="0">
            <div class="col-lg-12">
                {!! \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_with_sidebar_four',$page_post->id) !!}
            </div>
        </div>
    </div>
</div>
@endif


@if($page_post->layout === 'home_page_layout_three')
    <div class="mixed-area-wrapper index-04">
        <div class="container custom-container-01 d-none">
            <div class="row">
                <div class="col-lg-12 col-xl-9">
                    {!! \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_with_sidebar_five',$page_post->id) !!}
                </div>

                <div class="col-sm-7 col-md-6 col-lg-6 col-xl-3" data-padding-bottom="80" data-padding-top="50">
                    <div class="widget-area-wrapper">
                        {!! render_frontend_sidebar($page_post->sidebar_layout_two,['column' => false]) !!}
                    </div>
                </div>

            </div>
        </div>
        <div class="container-fluid custom-container-01" data-padding-bottom="0">
            <div class="col-lg-12">
                {!! \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_without_sidebar_six',$page_post->id) !!}
            </div>
        </div>
@endif


@if($page_post->layout === 'sidebar_layout')
    <div class="{{$page_post->page_class }}" data-padding-top="100" data-padding-bottom="100">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    {!! \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_with_sidebar',$page_post->id) !!}
                </div>
                <div class="col-sm-7 col-md-6 col-lg-4">
                    <div class="widget-area-wrapper">
                        {!! render_frontend_sidebar($page_post->sidebar_layout,['column' => false]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif


