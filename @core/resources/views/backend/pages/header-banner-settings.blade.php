@extends('backend.admin-master')


@section('site-title')
    {{__('Header Banner Settings')}}
@endsection

@section('style')
<x-media.css/>
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <x-msg.success/>
                <x-msg.error/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__("Header Banner Settings")}}</h4>
                        <form action="{{route('admin.header.banner.settings')}}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <x-image :name="'home_page_one_banner'"  :dimentions="'800 X 100'" :title="'Home Page One Banner'"/>
                            <div class="form-goup mb-4">
                                <label for="">{{__('Home Page One Banner URL')}}</label>
                                <input type="text" class="form-control" name="home_page_one_banner_url" value="{{get_static_option('home_page_one_banner_url')}}">
                            </div>

                            <x-image :name="'home_page_four_banner'"  :dimentions="'530 X 100'" :title="'Home Page Four Banner'"/>
                            <div class="form-goup mb-4">
                                <label for="">{{__('Home Page Four Banner URL')}}</label>
                                <input type="text" class="form-control" name="home_page_four_banner_url" value="{{get_static_option('home_page_four_banner_url')}}">
                            </div>

                            <x-image :name="'home_page_five_banner'"  :dimentions="'800 X 100'" :title="'Home Page Five Banner'"/>
                            <div class="form-goup">
                                <label for="">{{__('Home Page Five Banner URL')}}</label>
                                <input type="text" class="form-control" name="home_page_five_banner_url" value="{{get_static_option('home_page_five_banner_url')}}">
                            </div>

                            <button type="submit" id="update" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
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
                <x-icon-picker/>
                <x-btn.update/>
            });
        }(jQuery));
    </script>
    <x-media.js/>
@endsection
