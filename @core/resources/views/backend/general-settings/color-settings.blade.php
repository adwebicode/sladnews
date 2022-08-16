@extends('backend.admin-master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/backend/css/colorpicker.css')}}">
@endsection
@section('site-title')
    {{__('Color Settings')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <x-msg.success/>
                  <x-msg.error/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__("Color Settings")}}</h4>
                        <form action="{{route('admin.general.color.settings')}}" method="POST" enctype="multipart/form-data">@csrf
                            <div class="form-group">
                                <label for="site_main_color_one">{{__('Site Main Color One')}}</label>
                                <input type="text" name="site_main_color_one" style="background-color: {{get_static_option('site_main_color_one')}};" class="form-control"
                                       value="{{get_static_option('site_main_color_one')}}" id="site_main_color_one">
                                <small class="form-text text-muted">{{__('you can change -site main color- from here, it will replace the website main color')}}</small>
                            </div>

                            <div class="form-group">
                                <label for="site_main_color_two">{{__('Site Main Color Two')}}</label>
                                <input type="text" name="site_main_color_two" style="background-color: {{get_static_option('site_main_color_two')}};" class="form-control"
                                       value="{{get_static_option('site_main_color_two')}}" id="site_main_color_two">
                                <small class="form-text text-muted">{{__('you can change -site base color- from here, it will replace the website base color')}}</small>
                            </div>

                            <div class="form-group">
                                <label for="site_secondary_color">{{__('Site Secondary Color')}}</label>
                                <input type="text" name="site_secondary_color" style="background-color: {{get_static_option('site_secondary_color')}};" class="form-control"
                                       value="{{get_static_option('site_secondary_color')}}" id="site_secondary_color">
                                <small class="form-text text-muted">{{__('you can change -site Secondary color- from here, it will replace the website Secondary color')}}</small>
                            </div>

                            <div class="form-group">
                                <label for="site_main_color_two">{{__('Site Heading Color')}}</label>
                                <input type="text" name="site_heading_color" style="background-color: {{get_static_option('site_heading_color')}};" class="form-control"
                                       value="{{get_static_option('site_heading_color')}}" id="site_heading_color">
                                <small class="form-text text-muted">{{__('you can change -heading color- from here, it will replace the website heading color')}}</small>
                            </div>

                            <div class="form-group">
                                <label for="site_paragraph_color">{{__('Site Paragraph Color')}}</label>
                                <input type="text" name="site_paragraph_color" style="background-color: {{get_static_option('site_paragraph_color')}};" class="form-control"
                                       value="{{get_static_option('site_paragraph_color')}}" id="site_paragraph_color">
                                <small class="form-text text-muted">{{__('you can change -site paragraph color- from here, it will replace the website paragraph color')}}</small>
                            </div>

                            <div class="form-group">
                                <label for="site_bg_light_one">{{__('Site Background Light Color One')}}</label>
                                <input type="text" name="site_bg_light_one" style="background-color: {{get_static_option('site_bg_light_one')}};" class="form-control"
                                       value="{{get_static_option('site_bg_light_one')}}" id="site_bg_light_one">
                                <small class="form-text text-muted">{{__('you can change -site light color- from here, it will replace the light color')}}</small>
                            </div>

                            <div class="form-group">
                                <label for="site_bg_light_two">{{__('Site Background Light Color Two')}}</label>
                                <input type="text" name="site_bg_light_two" style="background-color: {{get_static_option('site_bg_light_two')}};" class="form-control"
                                       value="{{get_static_option('site_bg_light_two')}}" id="site_bg_light_two">
                                <small class="form-text text-muted">{{__('you can change - site light color two- from here, it will replace the website base color')}}</small>
                            </div>

                            <div class="form-group">
                                <label for="site_bg_dark_one">{{__('Site Background Dark One')}}</label>
                                <input type="text" name="site_bg_dark_one" style="background-color: {{get_static_option('site_bg_dark_one')}};" class="form-control"
                                       value="{{get_static_option('site_bg_dark_one')}}" id="site_bg_dark_one">
                                <small class="form-text text-muted">{{__('you can change - site dark one - from here, it will replace the website site dark one')}}</small>
                            </div>

                            <div class="form-group">
                                <label for="site_bg_dark_two">{{__('Site Background Dark Two')}}</label>
                                <input type="text" name="site_bg_dark_two" style="background-color: {{get_static_option('site_bg_dark_two')}};" class="form-control"
                                       value="{{get_static_option('site_bg_dark_two')}}" id="site_bg_dark_one">
                                <small class="form-text text-muted">{{__('you can change - site dark two- from here, it will replace the website site dark two')}}</small>
                            </div>

                            <div class="form-group">
                                <label for="site_bg_dark_three">{{__('Site Background Dark Three')}}</label>
                                <input type="text" name="site_bg_dark_three" style="background-color: {{get_static_option('site_bg_dark_three')}};" class="form-control"
                                       value="{{get_static_option('site_bg_dark_three')}}" id="site_bg_dark_three">
                                <small class="form-text text-muted">{{__('you can change - site dark three - from here, it will replace the website site dark three')}}</small>
                            </div>

                            <div class="form-group">
                                <label for="site_bg_dark_four">{{__('Site Background Dark Four')}}</label>
                                <input type="text" name="site_bg_dark_four" style="background-color: {{get_static_option('site_bg_dark_four')}};" class="form-control"
                                       value="{{get_static_option('site_bg_dark_four')}}" id="site_bg_dark_four">
                                <small class="form-text text-muted">{{__('you can change - site dark four - from here, it will replace the website site dark four')}}</small>
                            </div>

                            <div class="form-group">
                                <label for="site_bg_dark_four">{{__('Site Background Dark Five')}}</label>
                                <input type="text" name="site_bg_dark_five" style="background-color: {{get_static_option('site_bg_dark_five')}};" class="form-control"
                                       value="{{get_static_option('site_bg_dark_five')}}" id="site_bg_dark_five">
                                <small class="form-text text-muted">{{__('you can change - site dark five - from here, it will replace the website site dark five')}}</small>
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
    <script src="{{asset('assets/backend/js/colorpicker.js')}}"></script>
    <script>
        (function($){
            "use strict";

            $(document).ready(function(){
                <x-icon-picker/>
                <x-btn.update/>
                initColorPicker('#site_main_color_one');
                initColorPicker('#site_main_color_two');
                initColorPicker('#site_secondary_color');
                initColorPicker('#site_heading_color');
                initColorPicker('#site_paragraph_color');
                initColorPicker('#site_bg_light_one');
                initColorPicker('#site_bg_light_two');
                initColorPicker('#site_bg_dark_one');
                initColorPicker('#site_bg_dark_two');
                initColorPicker('#site_bg_dark_three');
                initColorPicker('#site_bg_dark_four');
                initColorPicker('#site_bg_dark_five');

                function initColorPicker(selector){
                    $(selector).ColorPicker({
                        color: '#852aff',
                        onShow: function (colpkr) {
                            $(colpkr).fadeIn(500);
                            return false;
                        },
                        onHide: function (colpkr) {
                            $(colpkr).fadeOut(500);
                            return false;
                        },
                        onChange: function (hsb, hex, rgb) {
                            $(selector).css('background-color', '#' + hex);
                            $(selector).val('#' + hex);
                        }
                    });
                }
            });
        }(jQuery));
    </script>
@endsection
