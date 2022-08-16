@extends('backend.admin-master')


@section('site-title')
    {{__('Basic Settings')}}
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <x-msg.success/>
                <x-msg.error/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__("Basic Settings")}}</h4>
                        <form action="{{route('admin.general.basic.settings')}}" method="POST" enctype="multipart/form-data">@csrf

                            <x-lang-nav/>
                            <div class="tab-content margin-top-30" id="nav-tabContent">
                                @foreach($all_languages as $key => $lang)
                                    <div class="tab-pane fade @if($key == 0) show active @endif" id="nav-home-{{$lang->slug}}" role="tabpanel" aria-labelledby="nav-home-tab">
                                        <div class="form-group">
                                            <label for="site_{{$lang->slug}}_title">{{__('Site Title')}}</label>
                                            <input type="text" name="site_{{$lang->slug}}_title"  class="form-control" value="{{get_static_option('site_'.$lang->slug.'_title')}}" id="site_{{$lang->slug}}_title">
                                        </div>
                                        <div class="form-group">
                                            <label for="site_{{$lang->slug}}_tag_line">{{__('Site Tag Line')}}</label>
                                            <input type="text" name="site_{{$lang->slug}}_tag_line"  class="form-control" value="{{get_static_option('site_'.$lang->slug.'_tag_line')}}" id="site_{{$lang->slug}}_tag_line">
                                        </div>
                                        <div class="form-group">
                                            <label for="site_{{$lang->slug}}_footer_copyright">{{__('Footer Copyright')}}</label>
                                            <input type="text" name="site_{{$lang->slug}}_footer_copyright"  class="form-control" value="{{get_static_option('site_'.$lang->slug.'_footer_copyright')}}" id="site_{{$lang->slug}}_footer_copyright">
                                            <small class="form-text text-muted">{{__('{copy} will replace by ©; and {year} will be replaced by current year.')}}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-group">
                                @php
                                    $list = DateTimeZone::listIdentifiers();
                                @endphp
                                <label for="timezone">{{__('Select Timezone')}}</label>
                                <select class="form-control" name="timezone" id="timezone">
                                    @foreach($list as $zone)
                                        <option value="{{$zone}}" {{$zone == get_static_option('timezone') ? 'selected' : ''}}>{{$zone}}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted"></small>
                            </div>


                            <div class="form-group">
                                <label for="language_select_option"><strong>{{__('Language Select Show or Hide')}}</strong></label>
                                <label class="switch yes">
                                    <input type="checkbox" name="language_select_option"  @if(!empty(get_static_option('language_select_option'))) checked @endif id="language_select_option">
                                    <span class="slider onoff"></span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label for="language_select_option"><strong>{{__('Login Show or Hide')}}</strong></label>
                                <label class="switch yes">
                                    <input type="checkbox" name="login_show_hide"  @if(!empty(get_static_option('login_show_hide'))) checked @endif id="login_show_hide">
                                    <span class="slider onoff"></span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label for="language_select_option"><strong>{{__('Register Show or Hide')}}</strong></label>
                                <label class="switch yes">
                                    <input type="checkbox" name="register_show_hide"  @if(!empty(get_static_option('register_show_hide'))) checked @endif id="register_show_hide">
                                    <span class="slider onoff"></span>
                                </label>
                            </div>


                            <div class="form-group">
                                <label for="language_select_option"><strong>{{__('Dark Mode Show or Hide')}}</strong></label>
                                <label class="switch yes">
                                    <input type="checkbox" name="dark_mode_show_hide"  @if(!empty(get_static_option('dark_mode_show_hide'))) checked @endif id="dark_mode_show_hide">
                                    <span class="slider onoff"></span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label for="disable_user_email_verify"><strong>{{__('User Email Verify')}}</strong></label>
                                <label class="switch">
                                    <input type="checkbox" name="disable_user_email_verify"  @if(!empty(get_static_option('disable_user_email_verify'))) checked @endif id="disable_user_email_verify">
                                    <span class="slider-enable-disable"></span>
                                </label>
                                <small class="form-text text-muted">{{__('Disable, means user must have to verify their email account in order to access his/her dashboard.')}}</small>
                            </div>
                            <div class="form-group">
                                <label for="site_maintenance_mode"><strong>{{__('Maintenance Mode')}}</strong></label>
                                <label class="switch yes">
                                    <input type="checkbox" name="site_maintenance_mode"  @if(!empty(get_static_option('site_maintenance_mode'))) checked @endif id="site_maintenance_mode">
                                    <span class="slider-enable-disable"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="site_force_ssl_redirection"><strong>{{__('Enable Force SSL Redirection')}}</strong></label>
                                <label class="switch yes">
                                    <input type="checkbox" name="site_force_ssl_redirection"  @if(!empty(get_static_option('site_force_ssl_redirection'))) checked @endif>
                                    <span class="slider-enable-disable"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="admin_loader_animation"><strong>{{__('Admin Preloader Animation')}}</strong></label>
                                <label class="switch yes">
                                    <input type="checkbox" name="admin_loader_animation"  @if(!empty(get_static_option('admin_loader_animation'))) checked @endif id="admin_loader_animation">
                                    <span class="slider-enable-disable"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="site_loader_animation"><strong>{{__('Site Preloader Animation')}}</strong></label>
                                <label class="switch yes">
                                    <input type="checkbox" name="site_loader_animation"  @if(!empty(get_static_option('site_loader_animation'))) checked @endif id="site_loader_animation">
                                    <span class="slider-enable-disable"></span>
                                </label>
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
