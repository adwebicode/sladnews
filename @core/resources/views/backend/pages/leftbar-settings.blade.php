@extends('backend.admin-master')


@section('site-title')
    {{__('Leftbar Settings')}}
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <x-msg.success/>
                <x-msg.error/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__("Leftbar Settings")}}</h4>
                        <form action="{{route('admin.leftbar.settings')}}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="site_loader_animation"><strong>{{__('Leftbar Show/Hide')}}</strong></label>
                                <label class="switch yes">
                                    <input type="checkbox" name="leftbar_show_hide"  @if(!empty(get_static_option('leftbar_show_hide'))) checked @endif id="leftbar_show_hide">
                                    <span class="slider-enable-disable"></span>
                                </label>
                            </div>

                            <x-lang-nav/>
                            <div class="tab-content margin-top-30" id="nav-tabContent">
                                @foreach($all_languages as $key => $lang)
                                    <div class="tab-pane fade @if($key == 0) show active @endif" id="nav-home-{{$lang->slug}}" role="tabpanel" aria-labelledby="nav-home-tab">

                                        <div class="form-group">
                                            <label for="site_{{$lang->slug}}_title">{{__('Left Bar Blog Title')}}</label>
                                            <input type="text" name="leftbar_blog_{{$lang->slug}}_title"  class="form-control" value="{{get_static_option('leftbar_blog_'.$lang->slug.'_title')}}">
                                        </div>

                                        <div class="form-group">
                                            <label for="site_{{$lang->slug}}_tag_line">{{__('Left Bar Tag Title')}}</label>
                                            <input type="text" name="leftbar_tag_{{$lang->slug}}_title"  class="form-control" value="{{get_static_option('leftbar_tag_'.$lang->slug.'_title')}}" >
                                        </div>

                                        <div class="form-group">
                                            <label for="site_{{$lang->slug}}_title">{{__('Left Bar Social Title')}}</label>
                                            <input type="text" name="leftbar_social_{{$lang->slug}}_title"  class="form-control" value="{{get_static_option('leftbar_social_'.$lang->slug.'_title')}}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-group">
                                <label >{{__('Blog Item Show')}}</label>
                                <input type="number" name="leftbar_blog_item_show"  class="form-control" value="{{get_static_option('leftbar_blog_item_show')}}">
                            </div>

                            <div class="form-group">
                                <label >{{__('Tag Item Show')}}</label>
                                <input type="number" name="leftbar_tag_item_show"  class="form-control" value="{{get_static_option('leftbar_tag_item_show')}}">
                            </div>

                            <div class="form-group">
                                <label >{{__('Social Item Show')}}</label>
                                <input type="number" name="leftbar_social_item_show"  class="form-control" value="{{get_static_option('leftbar_social_item_show')}}">
                            </div>






                            <button type="submit" id="update" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        (function($){
            "use strict";
            $(document).ready(function(){
                <x-icon-picker/>
                <x-btn.update/>


            });
        }(jQuery));
    </script>
@endsection
