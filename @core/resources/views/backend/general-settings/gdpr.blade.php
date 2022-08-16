@extends('backend.admin-master')
@section('site-title')
    {{__('GDPR Compliant Cookie Settings')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                @include('backend.partials.message')
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__("GDPR Compliant Cookie Settings")}}</h4>
                        <form action="{{route('admin.general.gdpr.settings')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @include('backend.partials.languages-nav')
                            <div class="tab-content margin-top-30" id="nav-tabContent">
                                @foreach($all_languages as $key => $lang)
                                    <div class="tab-pane fade @if($key == 0) show active @endif" id="nav-home-{{$lang->slug}}" role="tabpanel" aria-labelledby="nav-home-tab">
                                        <div class="form-group">
                                            <label for="site_gdpr_cookie_{{$lang->slug}}_title">{{__('GDPR Title')}}</label>
                                            <input type="text" name="site_gdpr_cookie_{{$lang->slug}}_title"  class="form-control" value="{{get_static_option('site_gdpr_cookie_'.$lang->slug.'_title')}}" id="site_gdpr_cookie_{{$lang->slug}}_title">
                                        </div>
                                        <div class="form-group">
                                            <label for="site_gdpr_cookie_{{$lang->slug}}_message">{{__('GDPR Message')}}</label>
                                            <textarea name="site_gdpr_cookie_{{$lang->slug}}_message"  class="form-control" rows="5" id="site_gdpr_cookie_{{$lang->slug}}_message">{{get_static_option('site_gdpr_cookie_'.$lang->slug.'_message')}}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="site_gdpr_cookie_{{$lang->slug}}_more_info_label">{{__('GDPR More Info Link Label')}}</label>
                                            <input type="text" name="site_gdpr_cookie_{{$lang->slug}}_more_info_label"  class="form-control" value="{{get_static_option('site_gdpr_cookie_'.$lang->slug.'_more_info_label')}}" id="site_gdpr_cookie_{{$lang->slug}}_more_info_label">
                                        </div>
                                        <div class="form-group">
                                            <label for="site_gdpr_cookie_{{$lang->slug}}_more_info_link">{{__('GDPR More Info Link')}}</label>
                                            <input type="text" name="site_gdpr_cookie_{{$lang->slug}}_more_info_link"  class="form-control" value="{{get_static_option('site_gdpr_cookie_'.$lang->slug.'_more_info_link')}}" id="site_gdpr_cookie_{{$lang->slug}}_more_info_link">
                                            <small class="form-text text-muted">{{__('enter more info link user {url} to point the site address, example: {url}/about , it will be converted to www.yoursite.com/about')}}</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="site_gdpr_cookie_{{$lang->slug}}_accept_button_label">{{__('GDPR Cookie Accept Button Label')}}</label>
                                            <input type="text" name="site_gdpr_cookie_{{$lang->slug}}_accept_button_label"  class="form-control" value="{{get_static_option('site_gdpr_cookie_'.$lang->slug.'_accept_button_label')}}" id="site_gdpr_cookie_{{$lang->slug}}_accept_button_label">
                                        </div>
                                        <div class="form-group">
                                            <label for="site_gdpr_cookie_{{$lang->slug}}_decline_button_label">{{__('GDPR Cookie Decline Button Label')}}</label>
                                            <input type="text" name="site_gdpr_cookie_{{$lang->slug}}_decline_button_label"  class="form-control" value="{{get_static_option('site_gdpr_cookie_'.$lang->slug.'_decline_button_label')}}" id="site_gdpr_cookie_{{$lang->slug}}_decline_button_label">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label for="site_gdpr_cookie_enabled"><strong>{{__('GDPR Cookie Enable/Disable')}}</strong></label>
                                <label class="switch yes">
                                    <input type="checkbox" name="site_gdpr_cookie_enabled"  @if(!empty(get_static_option('site_gdpr_cookie_enabled'))) checked @endif id="site_gdpr_cookie_enabled">
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="site_gdpr_cookie_expire">{{__('Cookie Expire')}}</label>
                                <input type="text" name="site_gdpr_cookie_expire"  class="form-control" value="{{get_static_option('site_gdpr_cookie_expire')}}" id="site_gdpr_cookie_expire">
                                <small class="form-text text-muted">{{__('set cookie expire time, eg: 30, means 30days')}}</small>
                            </div>
                            <div class="form-group">
                                <label for="site_gdpr_cookie_delay">{{__('Show Delay')}}</label>
                                <input type="text" name="site_gdpr_cookie_delay"  class="form-control" value="{{get_static_option('site_gdpr_cookie_delay')}}" id="site_gdpr_cookie_delay">
                                <small class="form-text text-muted">{{__('set GDPR cookie delay time, it mean the notification will show after this time. number count as mili seconds. eg: 5000, means 5seconds')}}</small>
                            </div>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
