@extends('frontend.user.dashboard.user-master')
@section('style')
    <x-summernote.css/>
    <link rel="stylesheet" href="{{asset('assets/backend/css/bootstrap-tagsinput.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/custom-dashboard.css')}}">
    <x-media.css/>
    <x-blog-inline-css/>
@endsection
@section('site-title')
    {{__('Edit Blog Post')}}
@endsection

@section('page-title')
    <li class="list-item"><a href="#">{{__('User Dashboard')}}</a></li>
    <li class="list-item"><a href="#">{{__('Edit Blog Post')}}</a></li>
@endsection

@section('section')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrap d-flex justify-content-between">
                            <div class="left-content">
                                <h3 class="header-title">{{__('Edit Blog Item')}}   </h3>
                            </div>
                            <div class="header-title d-flex">
                                <div class="btn-wrapper-inner">

                                    <a href="{{ route('user.blog') }}"
                                       class="btn btn-primary">{{__('All Blog Post')}}
                                    </a>
                                    <a href="{{ route('user.blog.new') }}"
                                       class="btn btn-info">{{__('Create New')}}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <form action="{{route('user.blog.update',$blog_post->id)}}" method="post" enctype="multipart/form-data"
                              id="blog_new_form">
                            @csrf
                            <input type="hidden" name="lang" value="{{$default_lang}}">
                            <div class="form-group">
                                <label for="title">{{__('Title')}}</label>
                                <input type="text" class="form-control" name="title" id="title"
                                       value="{{$blog_post->getTranslation('title',$default_lang)}}">
                            </div>

                            <div class="form-group permalink_label">
                                <label class="text-dark">{{__('Permalink * : ')}}
                                    <span id="slug_show" class="display-inline"></span>
                                    <span id="slug_edit" class="display-inline">
                                         <button class="btn btn-warning btn-sm slug_edit_button"> <i class="las la-edit"></i> </button>
                                          <input type="text" name="slug" value="{{$blog_post->slug}}" class="form-control blog_slug mt-2" style="display: none">
                                          <button class="btn btn-info btn-sm slug_update_button mt-2" style="display: none">{{__('Update')}}</button>
                                    </span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label>{{__('Blog Content')}}</label>
                                <input type="hidden" name="blog_content" value="{{$blog_post->getTranslation('blog_content',$default_lang)}}">
                                <div class="summernote" data-content="{{$blog_post->getTranslation('blog_content',$default_lang)}}"></div>
                            </div>

                            <div class="form-group">
                                <label for="title">{{__('Excerpt')}}</label>
                                <textarea name="excerpt" id="excerpt" class="form-control max-height-150" cols="30"
                                          rows="10">{{$blog_post->getTranslation('excerpt',$default_lang)}}</textarea>
                            </div>
                    </div>
                    <div class="video_section" style="display: none">
                        <div class="card mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="slug">{{__('Video Url')}}</label>
                                            <input type="text" class="form-control" name="video_url" value=" {!! $blog_post->video_url ?? '' !!}">
                                        </div>

                                        <div class="form-group">
                                            <label for="slug">{{__('Video Duration')}}</label>
                                            <input type="text" class="form-control" name="video_duration" value=" {!! $blog_post->video_duration ?? '' !!}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="post_type_radio">
                            <h6>{{__('Post Type')}}</h6>
                            <div class="form-check form-check-inline d-block">
                                @php
                                    $check = $blog_post->video_url || $blog_post->embed_code || $blog_post->video_thumbnail ? 'checked' : ''
                                @endphp
                                <input class="form-check-input post_type" type="radio"
                                       name="inlineRadioOptions" checked
                                       id="radio_general" value="option1"

                                >
                                <i class="ti-settings"></i>
                                <label class="form-check-label" for="inlineRadio1">{{__('General')}}</label>
                            </div>
                            <div class="form-check form-check-inline d-block">

                                <input class="form-check-input post_type" type="radio" name="inlineRadioOptions"
                                       id="radio_video" value="option2" {{$check}}>
                                <i class="ti-video-camera"></i>
                                <label class="form-check-label" for="inlineRadio2">{{__('Video')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="featured"><strong>{{__('Select Categories')}}</strong></label>
                                    <div class="category-section">
                                        <ul>
                                            @foreach($all_category as $category)
                                                <li>
                                                    <input type="checkbox" name="category_id[]"
                                                           id="exampleCheck1" value="{{$category->id}}"

                                                    @foreach($blog_post->category_id as $cat)
                                                        {{ $cat->id === $category->id ? 'checked' : '' }}
                                                            @endforeach
                                                    >
                                                    <label class="ml-1">
                                                        {{purify_html($category->getTranslation('title',$default_lang))}}
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <div class="form-group " id="blog_tag_list">
                                    <label for="title">{{__('Blog Tag')}}</label>
                                    @php
                                        $tags_arr = json_decode($blog_post->tag_id);
                                        $tags = is_array($tags_arr) ? implode(",", $tags_arr) : "";
                                    @endphp
                                    <input type="text" class="form-control tags_filed" data-role="tagsinput"
                                           name="tag_id[]"  value=" {{$tags }}">

                                    <div id="show-autocomplete-dashboard" style="display: none;">
                                        <ul class="autocomplete-warp-dashboard" ></ul>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="og_meta_image">{{__('Blog Image')}}</label>
                                    <div class="media-upload-btn-wrapper">
                                        <div class="img-wrap">
                                            {!! render_attachment_preview_for_admin($blog_post->image ?? '') !!}
                                        </div>
                                        <input type="hidden" id="image" name="image"
                                               value="{{$blog_post->image}}">
                                        <button type="button" class="btn btn-info media_upload_form_btn"
                                                data-btntitle="{{__('Select Image')}}"
                                                data-modaltitle="{{__('Upload Image')}}" data-toggle="modal"
                                                data-target="#media_upload_modal">
                                            {{'Change Image'}}
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">{{__('allowed image format: jpg,jpeg,png')}}</small>
                                </div>

                                <div class="form-group">
                                    <label for="og_meta_image">{{__('Blog Image Gallery')}}</label>
                                    <div class="media-upload-btn-wrapper">
                                        <div class="img-wrap">
                                            {!! render_gallery_image_attachment_preview($blog_post->image_gallery ?? '') !!}
                                        </div>
                                        <input type="hidden" id="og_meta_image" name="image_gallery"
                                               value="{{$blog_post->image_gallery ?? ''}}">
                                        <button type="button" class="btn btn-info media_upload_form_btn"
                                                data-btntitle="{{__('Select Image')}}"
                                                data-modaltitle="{{__('Upload Image')}}" data-toggle="modal"
                                                data-mulitple="true"
                                                data-target="#media_upload_modal">
                                            {{'Change Image'}}
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">{{__('allowed image format: jpg,jpeg,png')}}</small>
                                </div>

                                <div class="card">
                                    <div class="card-body meta">
                                        <h5 class="header-title my-3">{{__('Meta Section')}}</h5>
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="nav flex-column nav-pills" id="v-pills-tab"
                                                     role="tablist" aria-orientation="vertical">
                                                    <a class="nav-link active" id="v-pills-home-tab"
                                                       data-toggle="pill" href="#v-pills-home" role="tab"
                                                       aria-controls="v-pills-home"
                                                       aria-selected="true">{{__('Blog Meta')}}</a>
                                                    <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill"
                                                       href="#v-pills-profile" role="tab"
                                                       aria-controls="v-pills-profile"
                                                       aria-selected="false">{{__('Facebook Meta')}}</a>
                                                    <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill"
                                                       href="#v-pills-messages" role="tab"
                                                       aria-controls="v-pills-messages"
                                                       aria-selected="false">{{__('Twitter Meta')}}</a>
                                                </div>
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="tab-content" id="v-pills-tabContent">

                                                    <div class="tab-pane fade show active" id="v-pills-home"
                                                         role="tabpanel" aria-labelledby="v-pills-home-tab">
                                                        <div class="form-group">
                                                            <label for="title">{{__('Meta Title')}}</label>
                                                            <input type="text" class="form-control" name="meta_title"
                                                                   value="{{$blog_post->meta_data->meta_title ?? ''}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="slug">{{__('Meta Tags')}}</label>
                                                            <input type="text" class="form-control"  data-role="tagsinput" name="meta_tags"
                                                                   value="{{$blog_post->meta_data->meta_tags ?? ''}}">
                                                        </div>

                                                        <div class="row">
                                                            <div class="form-group col-md-12">
                                                                <label for="title">{{__('Meta Description')}}</label>
                                                                <textarea name="meta_description"
                                                                          class="form-control max-height-140"
                                                                          cols="20"
                                                                          rows="4">{!! $blog_post->meta_data->meta_description ?? '' !!}</textarea>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel"
                                                         aria-labelledby="v-pills-profile-tab">
                                                        <div class="form-group">
                                                            <label for="title">{{__('Facebook Meta Tag')}}</label>
                                                            <input type="text" class="form-control" data-role="tagsinput"
                                                                   name="facebook_meta_tags" value="{{$blog_post->meta_data->facebook_meta_tags ?? ''}}">
                                                        </div>

                                                        <div class="row">
                                                            <div class="form-group col-md-12">
                                                                <label for="title">{{__('Facebook Meta Description')}}</label>
                                                                <textarea name="facebook_meta_description"
                                                                          class="form-control max-height-140 meta-desc"
                                                                          cols="20"
                                                                          rows="4">{!! $blog_post->meta_data->facebook_meta_description ?? '' !!}</textarea>
                                                            </div>
                                                        </div>

                                                        <div class="form-group ">
                                                            <label for="og_meta_image">{{__('Facebook Meta Image')}}</label>
                                                            <div class="media-upload-btn-wrapper">
                                                                <div class="img-wrap">
                                                                    {!! render_attachment_preview_for_admin($blog_post->meta_data->facebook_meta_image ?? '') !!}
                                                                </div>
                                                                <input type="hidden" id="facebook_meta_image" name="facebook_meta_image"
                                                                       value="{{$blog_post->meta_data->facebook_meta_image ?? ''}}">
                                                                <button type="button" class="btn btn-info media_upload_form_btn"
                                                                        data-btntitle="{{__('Select Image')}}"
                                                                        data-modaltitle="{{__('Upload Image')}}" data-toggle="modal"
                                                                        data-target="#media_upload_modal">
                                                                    {{'Change Image'}}
                                                                </button>
                                                            </div>
                                                            <small class="form-text text-muted">{{__('allowed image format: jpg,jpeg,png')}}</small>
                                                        </div>
                                                    </div>

                                                    <div class="tab-pane fade" id="v-pills-messages" role="tabpanel"
                                                         aria-labelledby="v-pills-messages-tab">
                                                        <div class="form-group">
                                                            <label for="title">{{__('Twitter Meta Tag')}}</label>
                                                            <input type="text" class="form-control" data-role="tagsinput"
                                                                   name="twitter_meta_tags" value=" {{$blog_post->meta_data->twitter_meta_tags ?? ''}}">
                                                        </div>

                                                        <div class="row">
                                                            <div class="form-group col-md-12">
                                                                <label for="title">{{__('Twitter Meta Description')}}</label>
                                                                <textarea name="twitter_meta_description"
                                                                          class="form-control max-height-140 meta-desc"
                                                                          cols="20"
                                                                          rows="4">{!! $blog_post->meta_data->twitter_meta_description ?? '' !!}</textarea>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="og_meta_image">{{__('Twitter Meta Image')}}</label>
                                                            <div class="media-upload-btn-wrapper">
                                                                <div class="img-wrap">
                                                                    {!! render_attachment_preview_for_admin($blog_post->meta_data->twitter_meta_image ?? '') !!}
                                                                </div>
                                                                <input type="hidden" id="twitter_meta_image" name="twitter_meta_image"
                                                                       value="{{$blog_post->meta_data->twitter_meta_image ?? ''}}">
                                                                <button type="button" class="btn btn-info media_upload_form_btn"
                                                                        data-btntitle="{{__('Select Image')}}"
                                                                        data-modaltitle="{{__('Upload Image')}}" data-toggle="modal"
                                                                        data-target="#media_upload_modal">
                                                                    {{'Change Image'}}
                                                                </button>
                                                            </div>
                                                            <small class="form-text text-muted">{{__('allowed image format: jpg,jpeg,png')}}</small>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="submit_btn mt-5">
                                    <button type="submit"
                                            class="btn btn-success">{{__('Save Post ')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <x-media.markup :type="'web'"/>
@endsection

@push('scripts')
    <script src="{{asset('assets/backend/js/bootstrap-tagsinput.js')}}"></script>
    <x-summernote.js/>
    <x-media.js :type="'web'"/>

    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {

                var blogTagInput = $('#blog_tag_list .tags_filed');
                var oldTag = '';
                blogTagInput.tagsinput();
                //For Tags
                $(document).on('keyup','#blog_tag_list .bootstrap-tagsinput input[type="text"]',function (e) {
                    e.preventDefault();
                    var el = $(this);
                    var inputValue = $(this).val();
                    $.ajax({
                        type: 'get',
                        url :  "{{ route('frontend.get.tags.by.ajax') }}",
                        async: false,
                        data: {
                            query: inputValue
                        },

                        success: function (data){
                            oldTag = inputValue;
                            let html = '';
                            var showAutocomplete = '';
                            $('#show-autocomplete-dashboard').html('<ul class="autocomplete-warp-dashboard"></ul>');
                            if(el.val() != '' && data.markup != ''){


                                data.result.map(function (tag, key) {
                                    html += '<li class="tag_option" data-id="'+key+'" data-val="'+tag+'">' + tag + '</li>'
                                })

                                $('#show-autocomplete-dashboard ul').html(html);
                                $('#show-autocomplete-dashboard').show();


                            } else {
                                $('#show-autocomplete-dashboard').hide();
                                oldTag = '';
                            }

                        },
                        error: function (res){

                        }
                    });
                });

                $(document).on('click', '.tag_option', function(e) {
                    e.preventDefault();

                    let id = $(this).data('id');
                    let tag = $(this).data('val');
                    blogTagInput.tagsinput('add', tag);
                    $(this).parent().remove();
                    blogTagInput.tagsinput('remove', oldTag);
                });




          //Status Code
            if($('#status').val() == 'schedule') {
                $('.date').show();
                $('.date').focus();
            }
                $(document).on('change','#status',function(e){
                    e.preventDefault();
                    if ($(this).val() == 'schedule') {
                        $('.date').show();
                        $('.date').focus();
                    } else {
                        $('.date').hide();
                    }
                });

                //Permalink Code
                var sl =  $('.blog_slug').val();
                var url = `{{url('/blog/')}}/` + sl;
                var data = $('#slug_show').text(url).css('color', 'blue');

                var form = $('#blog_new_form');

                $(document).on('keyup', '#title', function (e) {
                    var slug = $(this).val().trim().toLowerCase().split(' ').join('-');
                    var url = `{{url('/'.get_blog_slug_by_page_id(get_static_option('blog_page')).'/')}}/` + slug;
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
                    var url = `{{url('/blog/')}}/` + slug;
                    $('#slug_show').text(url);
                    $('.blog_slug').hide();
                });

                $(document).on('change','#status',function(e){
                    e.preventDefault();
                    if ($(this).val() == 'schedule') {
                        $('.date').show();
                        $('.date').focus();
                    } else {
                        $('.date').hide();
                    }
                });


                <x-btn.submit/>
                $(document).on('change', '#langchange', function (e) {
                    $('#langauge_change_select_get_form').trigger('submit');
                });

                $(document).on('change', '.post_type', function () {
                    var val = $(this).val();
                    if (val === 'option2') {
                        $('.video_section').show();
                    } else {
                        $('.video_section').hide();
                    }
                })

            });

            if('{{$check}}'){
                $('.video_section').show();
            }


            $('.summernote').summernote({
                height: 400,   //set editable area's height
                codemirror: { // codemirror options
                    theme: 'monokai'
                },
                callbacks: {
                    onChange: function (contents, $editable) {
                        $(this).prev('input').val(contents);
                    }
                }
            });
            if ($('.summernote').length > 0) {
                $('.summernote').each(function (index, value) {
                    $(this).summernote('code', $(this).data('content'));
                });
            }
        })(jQuery)
    </script>


    <script>
        $(document).ready(function(){
            $(document).on('click','.mobile-nav-click', function (e){
                e.preventDefault()

                // $('.nav-pills-close').toggleClass('active');
                $('.nav-pills-open').toggleClass('active');
            });
        });
    </script>
@endpush
