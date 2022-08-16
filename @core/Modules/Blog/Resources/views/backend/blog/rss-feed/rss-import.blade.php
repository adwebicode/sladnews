@extends('backend.admin-master')
@section('site-title')
    {{__('Rss Feed Import')}}
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

                        <div class="header-wrap d-flex justify-content-between">
                            <div class="left-content">
                                <h4 class="header-title">{{__('Rss Feed Import')}}</h4>
                            </div>
                            <div class="header-title d-flex">
                                <div class="btn-wrapper-inner">
                                    <form action="{{route('admin.blog.import.rss.feed')}}" method="get"
                                          id="langauge_change_select_get_form">
                                        <x-lang.select :name="'lang'" :selected="$default_lang" :id="'langchange'"/>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <form action="{{route('admin.blog.import.rss.feed')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="lang" value="{{$default_lang}}">
                            <div class="form-group">
                                <label>{{__('Rss Link')}}</label>
                                <input  type="text" class="form-control rss_link_file" name="rss_link_file" value="{{get_static_option('rss_link_file')}}"  >
                                <small> {{'All Rss Times of India (Demo) : '}} <span class="text-info"> <a href="https://timesofindia.indiatimes.com/rss.cms" target="_blank"> {{__( 'https://timesofindia.indiatimes.com/rss.cms')}}</a></span> </small><br>
                                <small> {{'This is a demo link : '}} <span class="text-info"> <a href="https://timesofindia.indiatimes.com/rssfeeds/296589292.cms" target="_blank"> {{__( 'https://timesofindia.indiatimes.com/rssfeeds/296589292.cms')}}</a></span> </small><br>
                                <small class="text-danger">{{__('Allowed feed image formats : jpg, jpeg, png , gif')}} </small>
                            </div>

                            <div class="form-group">
                                <label>{{__('Import Item')}}</label>
                                <input  type="number" class="form-control" name="rss_item_import" value="{{get_static_option('rss_item_import')}}"  >
                            </div>

                            <button id="import_btn" type="submit" class="btn btn-primary mt-4 pr-4 pl-4 import">{{__('Import')}}</button>
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
                <x-btn.import/>
                $(document).on('change', '#langchange', function (e) {
                    $('#langauge_change_select_get_form').trigger('submit');
                });

            });
        }(jQuery));
    </script>
@endsection