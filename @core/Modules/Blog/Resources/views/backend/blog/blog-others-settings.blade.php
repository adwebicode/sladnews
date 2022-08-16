@extends('backend.admin-master')
@section('site-title')
    {{__('Blog Others Settings')}}
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
              <x-msg.success/>
              <x-msg.error/>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__('Blog Others Settings')}}</h4>
                        <form action="{{route('admin.blog.others.settings')}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="site_loader_animation"><strong>{{__('Breaking News Show/Hide')}}</strong></label>
                                <label class="switch yes">
                                    <input type="checkbox" name="blog_breaking_news_show_hide_all"  @if(!empty(get_static_option('blog_breaking_news_show_hide_all'))) checked @endif id="blog_breaking_news_show_hide_all">
                                    <span class="slider-enable-disable"></span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label>{{__('Category Item Show On Category Wise Blog')}}</label>
                                <input type="text" class="form-control" value="{{get_static_option('blog_category_item_show')}}" name="blog_category_item_show" >
                            </div>

                            <div class="form-group">
                                <label>{{__('Tags Item Show On Tags Wise Blog')}}</label>
                                <input type="text" class="form-control" value="{{get_static_option('blog_tags_item_show')}}" name="blog_tags_item_show" >
                            </div>

                            <div class="form-group">
                                <label>{{__('Search Item Show On Search Wise Blog')}}</label>
                                <input type="text" class="form-control" value="{{get_static_option('blog_search_item_show')}}" name="blog_search_item_show" >
                            </div>

                            <button id="update" type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection

@section('script')
    <script>
        (function($){
            "use strict";
            $(document).ready(function(){
                <x-btn.update/>
            });
        }(jQuery));
    </script>
@endsection