@include('frontend.partials.left-bar')


<div class="topbar-area">
    <div class="container custom-container-01">
        <div class="row">
            <div class="col-lg-12">
                <div class="topbar-inner">
                    <div class="left-content">
                        <div class="topbar-item">
                            <div class="extra-menu">
                                <ul class="extra-menu-list">
                                    @foreach($all_topbar_infos as  $data)
                                    <li class="link-item">
                                        <a href="{{$data->url}}">
                                          {{$data->title}}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="right-content">
                        <div class="topbar-item">
                            <div class="social-icon">
                                <ul class="social-link-list">
                                    @foreach($all_social_icons as $data)
                                        <li class="link-item">
                                            <a href="{{$data->url}}" class="facebook">
                                                <i class="{{$data->icon}} icon"></i>
                                            </a>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>

                        <div class="topbar-item">
                             @if(auth()->check())
                                  @php
                                    $route = auth()->guest() == 'admin' ? route('admin.home') : route('user.home');
                                 @endphp

                                    <a class="topbar-login-btn" href="{{$route}}">{{__('Dashboard')}}</a>
                                    <span>{{__('|')}}</span>
                                    <a class="topbar-login-btn" href="{{ route('frontend.user.logout') }}">{{ __('Logout') }}</a>

                                    <form id="userlogout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
                                        @csrf
                                        <input type="submit" value="aa" id="userlogout-form" class="d-none">
                                    </form>

                              @else


                                  @if(!empty(get_static_option('login_show_hide')))
                                    <a class="topbar-login-btn" href="{{route('user.login')}}">{{__('Login')}}</a>
                                    <span>{{__('|')}}</span>
                                   @endif

                                    @if(!empty(get_static_option('register_show_hide')))
                                    <a class="topbar-login-btn" href="{{route('user.register')}}">{{__('Register')}}</a>
                                    @endif

                            @endif
                            </div>

                            @if(!empty(get_static_option('language_select_option')))
                                <div class="topbar-item">
                                    <select class="lang" id="langchange">
                                        @foreach($all_language as $lang)
                                            @php
                                                $lang_name = explode('(',$lang->name);
                                                $data = array_shift($lang_name);
                                            @endphp
                                            <option @if(get_user_lang() == $lang->slug) selected @endif value="{{$lang->slug}}">{{$data}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                        @if(!empty(get_static_option('dark_mode_show_hide')))
                            @php
                                $condition = get_static_option('site_frontend_dark_mode') == 'on' ? 'dark night-symbol' : 'day-symbol';
                            @endphp
                        <div class="topbar-item">
                            <label class="switch yes">
                                <input id="frontend_darkmode" type="checkbox" data-mode={{ get_static_option('site_frontend_dark_mode') }} @if(get_static_option('site_frontend_dark_mode') == 'on') checked @else @endif>
                                <span class="slider-color-mode onff {{$condition}}"></span>
                            </label>
                        </div>
                        @endif
                         </div>

                     </div>
                    </div>
                </div>
            </div>
    </div>
</div>
