@extends('backend.admin-master')

@section('site-title')
    {{__('404 Error Page Settings')}}
@endsection

@section('style')
<x-media.css/>
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                @include('backend/partials/message')
                @include('backend/partials/error')
            </div>
            <div class="col-lg-12 mt-t">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__('404 Error Page Settings')}}</h4>
                        <form action="{{route('admin.404.page.settings')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    @foreach( get_all_language() as $key => $value)
                                    <a class="nav-item nav-link @if($key == 0) active @endif"  data-toggle="tab" href="#nav_{{$key}}" role="tab" aria-selected="true">{{$value->name}}</a>
                                    @endforeach
                                </div>
                            </nav>
                            <div class="tab-content margin-top-20" id="nav-tabContent">
                                @foreach( get_all_language() as $key => $value)
                                <div class="tab-pane fade @if($key == 0) show active @endif" id="nav_{{$key}}" role="tabpanel" >
                            
                                    <div class="form-group">
                                        <label for="error_404_page_{{$value->slug}}_subtitle">{{__('Title')}}</label>
                                        <input type="text" name="error_404_page_{{$value->slug}}_subtitle" class="form-control" value="{{get_static_option('error_404_page_'.$value->slug.'_subtitle')}}" id="error_404_page_{{$value->slug}}_subtitle">
                                    </div>
                              
                                    <div class="form-group">
                                        <label for="error_404_page_{{$value->slug}}_button_text">{{__('Button Text')}}</label>
                                        <input type="text" name="error_404_page_{{$value->slug}}_button_text" class="form-control" value="{{get_static_option('error_404_page_'.$value->slug.'_button_text')}}" id="error_404_page_{{$value->slug}}_button_text">
                                    </div>
                                </div>
                                @endforeach
                                  <x-image :title="__('Error Image')" :name="'error_image'" :dimentions="'172x290'"/>
                            </div>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Settings')}}</button>
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
    <script>
        (function($){
            "use strict";

            $(document).ready(function () {

                var imgSelect = $('.img-select');
                var id = $('#header_type').val();
                imgSelect.removeClass('selected');
                $('img[data-header_type="'+id+'"]').parent().parent().addClass('selected');

                $(document).on('click','.img-select img',function (e) {
                    e.preventDefault();
                    imgSelect.removeClass('selected');
                    $(this).parent().parent().addClass('selected').siblings();
                    $('#header_type').val($(this).data('header_type'));
                });

            })

        })(jQuery);
    </script>
@endsection
