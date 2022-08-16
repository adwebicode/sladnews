@extends('backend.admin-master')
@section('style')
    <x-media.css/>
    <link rel="stylesheet" href="{{asset('assets/backend/css/summernote-bs4.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/bootstrap-tagsinput.css')}}">
    <style>
        #slug_edit .form-control {
            height: 30px;
            width: 100%;
        }

        .slug_edit_button {
            line-height: 0px;
            margin: 0;
            padding: 8px 8px;
        }

        .slug_update_button {
            line-height: 0px;
            margin: 0;
            padding: 12px;
        }

        .meta .flex-column{
            background-color: #f2f2f2;
        }

        .meta .flex-column a{
            color: #0c0c0c;
        }


    </style>
@endsection
@section('site-title')
    {{__('Edit Page')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-msg.success/>
                <x-msg.error/>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrap d-flex justify-content-between">
                            <div class="left-content">
                                <h4 class="header-title">{{__('Edit Page')}}   </h4>
                            </div>
                            <div class="header-title d-flex">
                                <div class="btn-wrapper-inner">
                                    <a href="{{ route('admin.page') }}" class="btn btn-primary">{{__('All Pages')}}</a>
                                </div>
                            </div>
                        </div>
                        <form action="{{route('admin.page.update',$page_post->id)}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="lang" value="{{$default_lang}}">
                            <div class="tab-content margin-top-40">

                                <div class="form-group">
                                    <label for="title">{{__('Title')}}</label>
                                    <input type="text" class="form-control" name="title" value="{{$page_post->getTranslation('title',$default_lang)}}" id="title">
                                </div>

                                <div class="form-group permalink_label">
                                    <label class="text-dark">{{__('Permalink * : ')}}
                                        <span id="slug_show" class="display-inline"></span>
                                        <span id="slug_edit" class="display-inline">
                                         <button class="btn btn-warning btn-sm slug_edit_button"> <i class="fas fa-edit"></i> </button>
                                          <input type="text" name="slug" value="{{$page_post->slug}}" class="form-control blog_slug mt-2" style="display: none">
                                          <button class="btn btn-info btn-sm slug_update_button mt-2" style="display: none">{{__('Update')}}</button>
                                    </span>
                                    </label>
                                </div>

                                <div class="form-group classic-editor-wrapper @if(!empty($page_post->page_builder_status)) d-none @endif ">
                                    <label>{{__('Content')}}</label>
                                    <input type="hidden" name="page_content" value="{{$page_post->getTranslation('page_content',$default_lang)}}">
                                    <div class="summernote" data-content="{{ $page_post->getTranslation('page_content',$default_lang) }}"></div>
                                </div>

                            </div>

                            <div class="row mt-5">
                                <x-backend.page-meta-data-edit
                                        :sidebarHeading="'Page Meta'"
                                        :pagepost="$page_post"
                                />
                            </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <h4 class="header-title">{{__('Navbar Variant')}}</h4>
                        <div class="form-group">
                            <input type="hidden" class="form-control" id="navbar_variant" value="{{$page_post->navbar_variant}}" name="navbar_variant">
                        </div>
                        <div class="row">
                            @for($i = 1; $i < 6; $i++)
                                <div class="col-lg-12 col-md-12">
                                    <div class="img-select img-select-nav @if($page_post->navbar_variant == $i ) selected @endif">
                                        <div class="img-wrap">
                                            <img src="{{asset('assets/frontend/navbar-variant/'.$i.'.png')}}" data-nav_id="0{{$i}}" alt="">
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <h4 class="header-title">{{__('Footer Variant')}}</h4>
                        <div class="form-group">
                            <input type="hidden" class="form-control" id="footer_variant" value="{{$page_post->footer_variant}}" name="footer_variant">
                        </div>
                        <div class="row">
                            @for($i = 1; $i < 3; $i++)
                                <div class="col-lg-12 col-md-12">
                                    <div class="img-select img-select-footer @if($page_post->footer_variant == $i ) selected @endif">
                                        <div class="img-wrap">
                                            <img src="{{asset('assets/frontend/footer-variant/'.$i.'.png')}}" data-foot_id="0{{$i}}" alt="">
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body meta">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="page_builder_status"><strong>{{__('Page Builder Enable/Disable')}}</strong></label>
                                <label class="switch">
                                    <input type="checkbox" name="page_builder_status"  @if(!empty($page_post->page_builder_status)) checked @endif >
                                    <span class="slider onff"></span>
                                </label>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="page_builder_status"><strong>{{__('Breadcrumb Show/Hide')}}</strong></label>
                                <label class="switch">
                                    <input type="checkbox" name="breadcrumb_status"  @if(!empty($page_post->breadcrumb_status)) checked @endif >
                                    <span class="slider onff"></span>
                                </label>
                            </div>

                            <div class="form-group">
                                <div class="btn-wrapper page-builder-btn-wrapper @if(empty($page_post->page_builder_status)) d-none @endif ">
                                    <a href="{{route('admin.dynamic.page.builder',['type' =>'dynamic-page','id' => $page_post->id])}}" target="_blank" class="btn btn-primary"> <i class="fas fa-external-link-alt"></i> {{__('Open Page Builder')}}</a>
                                </div>
                            </div>

                            <div class="form-group col-md-12 layout d-none">
                                <label>{{__('Page Layout')}}</label>
                                <select name="layout" class="form-control">
                                    <option value="normal_layout" @if($page_post->layout == 'normal_layout') selected @endif>{{__('Normal Layout')}}</option>
                                    <option value="home_page_layout" @if($page_post->layout == 'home_page_layout')selected  @endif>{{__('Home Page')}}</option>
                                    <option value="home_page_layout_two" @if($page_post->layout == 'home_page_layout_two')selected  @endif>{{__('Home Page Layout Two')}}</option>
                                    <option value="home_page_layout_three" @if($page_post->layout == 'home_page_layout_three')selected  @endif>{{__('Home Page Layout Three')}}</option>
                                    <option value="sidebar_layout" @if($page_post->layout == 'sidebar_layout')selected  @endif>{{__('Sidebar Layout')}}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12 page_class d-none">
                                <label>{{__('Page Class')}}</label>
                                <select name="page_class" class="form-control">
                                    <option >{{__('Default')}}</option>
                                    <option value="mixed-area-wrapper index-04"@if($page_post->page_class == 'mixed-area-wrapper index-04') selected @endif >{{__('Default Wrapper')}}</option>
                                    <option value="mixed-area-wrapper-01 index-05"@if($page_post->page_class == 'mixed-area-wrapper-01 index-05') selected @endif >{{__(' Wrapper Two')}}</option>
                                    <option value="blog-grid-wrapper video"@if($page_post->page_class == 'blog-grid-wrapper video') selected @endif>{{__(' Wrapper Three')}}</option>
                                    <option value="blog-list-wrapper sports-blog-list-wrapper"@if($page_post->page_class == 'blog-list-wrapper sports-blog-list-wrapper') selected @endif>{{__(' Wrapper Four')}}</option>
                                    <option value="blog-list-wrapper blog-standard-wrapper"@if($page_post->page_class == 'blog-list-wrapper blog-standard-wrapper') selected @endif>{{__(' Wrapper Five')}}</option>
                                    <option value="blog-grid-wrapper video"@if($page_post->page_class == 'blog-grid-wrapper video') selected @endif>{{__(' Wrapper Six')}}</option>
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label>{{__('Breadcrumb Class')}}</label>
                                <select name="widget_style" class="form-control">
                                    <option value="">{{__('Default')}}</option>
                                    <option value="container-two"@if($page_post->widget_style == 'container-two') selected @endif>{{__('Container Two')}}</option>
                                </select>
                            </div>


                            <div class="form-group col-md-12">
                                <label>{{__('Sidebar Layout')}}</label>
                                <select name="sidebar_layout" class="form-control">
                                    <option selected disabled>{{__('Select Sidebar')}}</option>
                                    <option value="none"@if($page_post->sidebar_layout == 'none') selected @endif>{{__('None')}}</option>
                                    <option value="footer"@if($page_post->sidebar_layout == 'footer') selected @endif>{{__('Footer Widget Area')}}</option>
                                    <option value="sidebar_01"@if($page_post->sidebar_layout == 'sidebar_01') selected @endif>{{__('Page Sidebar 01 Area')}}</option>
                                    <option value="sidebar_02"@if($page_post->sidebar_layout == 'sidebar_02') selected @endif>{{__('Page Sidebar 02 Area')}}</option>
                                    <option value="sidebar_03"@if($page_post->sidebar_layout == 'sidebar_03') selected @endif>{{__('Page Sidebar 03 Area')}}</option>
                                    <option value="sidebar_04"@if($page_post->sidebar_layout == 'sidebar_04') selected @endif>{{__('Page Sidebar 04 Area')}}</option>
                                    <option value="sidebar_05"@if($page_post->sidebar_layout == 'sidebar_05') selected @endif>{{__('Page Sidebar 05 Area')}}</option>
                                    <option value="sidebar_06"@if($page_post->sidebar_layout == 'sidebar_06') selected @endif>{{__('Page Sidebar 06 Area')}}</option>
                                    <option value="blogs_tags"@if($page_post->sidebar_layout == 'blogs_tags') selected @endif>{{__(' Blogs Tags Area')}}</option>
                                    <option value="blogs_tags_two"@if($page_post->sidebar_layout == 'blogs_tags_two') selected @endif>{{__(' Blogs Tags Two Area')}}</option>
                                    <option value="newsletter_banner"@if($page_post->sidebar_layout == 'newsletter_banner') selected @endif>{{__(' Newsletter Banner Area')}}</option>
                                    <option value="banner_newsletter_tags"@if($page_post->sidebar_layout == 'banner_newsletter_tags') selected @endif>{{__('Banner Newsletter Tags')}}</option>
                                    <option value="details_page_sidebar"@if($page_post->sidebar_layout == 'details_page_sidebar') selected @endif>{{__('Details Page Sidebar')}}</option>

                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="page_builder_status"><strong>{{__('Sidebar Layout Two Enable/Disable')}}</strong></label>
                                <label class="switch">
                                    <input type="checkbox" name="sidebar_layout_two_status"  @if(!empty($page_post->sidebar_layout_two_status)) checked @endif >
                                    <span class="slider onff"></span>
                                </label>
                            </div>

                            <div class="form-group col-md-12 sidebar_layout_two d-none">
                                <label>{{__('Sidebar Layout Two')}}</label>
                                <select name="sidebar_layout_two" class="form-control">
                                    <option selected disabled>{{__('Select Sidebar Two')}}</option>
                                    <option value="footer"@if($page_post->sidebar_layout_two == 'footer') selected @endif>{{__('Footer Widget Area')}}</option>
                                    <option value="sidebar_01"@if($page_post->sidebar_layout_two == 'sidebar_01') selected @endif>{{__('Page Sidebar 01 Area')}}</option>
                                    <option value="sidebar_02"@if($page_post->sidebar_layout_two == 'sidebar_02') selected @endif>{{__('Page Sidebar 02 Area')}}</option>
                                    <option value="sidebar_03"@if($page_post->sidebar_layout_two == 'sidebar_03') selected @endif>{{__('Page Sidebar 03 Area')}}</option>
                                    <option value="sidebar_04"@if($page_post->sidebar_layout_two == 'sidebar_04') selected @endif>{{__('Page Sidebar 04 Area')}}</option>
                                    <option value="sidebar_05"@if($page_post->sidebar_layout_two == 'sidebar_05') selected @endif>{{__('Page Sidebar 05 Area')}}</option>
                                    <option value="sidebar_06"@if($page_post->sidebar_layout_two == 'sidebar_06') selected @endif>{{__('Page Sidebar 06 Area')}}</option>
                                    <option value="blogs_tags"@if($page_post->sidebar_layout_two == 'blogs_tags') selected @endif>{{__(' Blogs Tags Area')}}</option>
                                    <option value="blogs_tags_two"@if($page_post->sidebar_layout_two == 'blogs_tags_two') selected @endif>{{__(' Blogs Tags Two Area')}}</option>
                                    <option value="newsletter_banner"@if($page_post->sidebar_layout_two == 'newsletter_banner') selected @endif>{{__(' Newsletter Banner Area')}}</option>
                                    <option value="banner_newsletter_tags"@if($page_post->sidebar_layout_two == 'banner_newsletter_tags') selected @endif>{{__('Banner Newsletter Tags')}}</option>
                                </select>
                            </div>


                            <div class="form-group col-md-12">
                                <label>{{__('Visibility')}}</label>
                                <select name="visibility" class="form-control">
                                    <option value="all" @if($page_post->visibility == 'all')selected  @endif>{{__('All')}}</option>
                                    <option value="user" @if($page_post->visibility == 'user')selected  @endif>{{__('Only Logged In User')}}</option>
                                </select>
                            </div>
                        </div>
                        <x-fields.status :name="'status'" :title="__('Status')"/>
                        <button type="submit" id="submit" class="btn btn-info mt-4 pr-4 pl-4">{{__('Update')}}</button>
                    </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    </div>
    <x-media.markup/>
@endsection
@section('script')
    <x-media.js/>
    <script src="{{asset('assets/backend/js/bootstrap-tagsinput.js')}}"></script>
    <script src="{{asset('assets/backend/js/summernote-bs4.js')}}"></script>
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                let builder_status = '{{$page_post->page_builder_status == "on"}}';
                if(builder_status){
                    $('.layout').removeClass('d-none');
                    $('.page_class').removeClass('d-none');
                }
                $(document).on('change','input[name="page_builder_status"]',function(){
                    if($(this).is(':checked')){
                        $('.classic-editor-wrapper').addClass('d-none');
                        $('.page-builder-btn-wrapper').removeClass('d-none');
                        $('.layout').removeClass('d-none');
                        $('.page_class').removeClass('d-none');
                    }else {
                        $('.classic-editor-wrapper').removeClass('d-none');
                        $('.page-builder-btn-wrapper').addClass('d-none');
                        $('.layout').addClass('d-none');
                        $('.page_class').addClass('d-none');
                    }
                });

                let sidebar_layout_two_status = '{{$page_post->sidebar_layout_two_status == "on"}}';
                if(sidebar_layout_two_status){
                    $('.sidebar_layout_two').removeClass('d-none');
                }

                $(document).on('change','input[name="sidebar_layout_two_status"]',function(){
                    if($(this).is(':checked')){
                        $('.sidebar_layout_two').removeClass('d-none');
                    }else{
                        $('.sidebar_layout_two').addClass('d-none');
                    }
                });


                <x-btn.submit/>
                //Permalink Code
                var sl =  $('.blog_slug').val();
                var url = `{{url('/')}}/` + sl;
                var data = $('#slug_show').text(url).css('color', 'blue');

                var form = $('#blog_new_form');

                $(document).on('keyup', '#title', function (e) {
                    var slug = $(this).val().trim().toLowerCase().split(' ').join('-');
                    var url = `{{url('/')}}/` + slug;
                    $('.permalink_label').show();
                    var data = $('#slug_show').text(url).css('color', 'blue');
                    $('.blog_slug').val(slug);

                });

                //Slug Edit Code
                $(document).on('click', '.slug_edit_button', function (e) {
                    e.preventDefault();
                    $('.blog_slug').show();
                    $(this).hide();
                    $('.slug_update_button').show();
                });

                //Slug Update Code
                $(document).on('click', '.slug_update_button', function (e) {
                    e.preventDefault();
                    $(this).hide();
                    $('.slug_edit_button').show();
                    var update_input = $('.blog_slug').val();
                    var slug = update_input.trim().toLowerCase().split(' ').join('-');
                    var url = `{{url('/')}}/` + slug;
                    $('#slug_show').text(url);
                    $('.blog_slug').hide();
                });


                $(document).on('change','#langchange',function(e){
                    $('#langauge_change_select_get_form').trigger('submit');
                });

                $('.summernote').summernote({
                    height: 400,   //set editable area's height
                    codemirror: { // codemirror options
                        theme: 'monokai'
                    },
                    callbacks: {
                        onChange: function (contents, $editable) {
                            $(this).prev('input').val(contents);
                        },
                        onPaste: function (e) {
                            var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                            e.preventDefault();
                            document.execCommand('insertText', false, bufferText);
                        }
                    }
                });
                if ($('.summernote').length > 0) {
                    $('.summernote').each(function (index, value) {
                        $(this).summernote('code', $(this).data('content'));
                    });
                }
            });

            //For Navbar
            var imgSelect1 = $('.img-select-nav');
            var id = $('#navbar_variant').val();
            imgSelect1.removeClass('selected');
            $('img[data-nav_id="'+id+'"]').parent().parent().addClass('selected');
            $(document).on('click','.img-select-nav img',function (e) {
                e.preventDefault();
                imgSelect1.removeClass('selected');
                $(this).parent().parent().addClass('selected').siblings();
                $('#navbar_variant').val($(this).data('nav_id'));
            })

            //For Footer
            var imgSelect2 = $('.img-select-footer');
            var id = $('#footer_variant').val();
            imgSelect2.removeClass('selected');
            $('img[data-foot_id="'+id+'"]').parent().parent().addClass('selected');
            $(document).on('click','.img-select-footer img',function (e) {
                e.preventDefault();
                imgSelect2.removeClass('selected');
                $(this).parent().parent().addClass('selected').siblings();
                $('#footer_variant').val($(this).data('foot_id'));
            })

        })(jQuery);
    </script>
@endsection

