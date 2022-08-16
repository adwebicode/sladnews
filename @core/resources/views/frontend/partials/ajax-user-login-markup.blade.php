@php $title = get_static_option('blog_single_page_login_title_'.$user_select_lang_slug.'_text'); @endphp
<div class="login-form">
    <p class="mb-4">{{$title}}</p>


    <div class="login-form">
        <form action="{{route('user.login')}}" method="post" enctype="multipart/form-data" class="account-form" id="login_form_order_page">
            @csrf
            <x-msg.error/>
            <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="{{__('Username')}}">
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="{{__('Password')}}">
            </div>
            <div class="form-group btn-wrapper">
                <button type="submit" id="login_btn" class="submit-btn btn-default">{{ get_static_option('blog_single_page_login_button_'.$user_select_lang_slug.'_text') }}</button>
            </div>

            <div class="row mb-4 rmber-area ajax-partial-login-form">
                    <div class="col-6">
                        <div class="custom-control custom-checkbox mr-sm-2 text-left">
                            <input type="checkbox" name="remember" class="custom-control-input" id="remember">
                            <label class="custom-control-label int" for="remember">{{__('Remember Me')}}</label>
                        </div>
                    </div>
                    <div class="col-6 text-right">
                        <a class="d-block int" href="{{route('user.register')}}">{{__('Create New account?')}}</a>
                        <a href="{{route('user.forget.password')}}" class="int">{{__('Forgot Password?')}}</a>
                    </div>

                <div class="col-lg-12">
                    <div class="social-login-wrap">
                        @if(get_static_option('enable_facebook_login'))
                            <a href="{{route('login.facebook.redirect')}}" class="facebook"><i class="lab la-facebook-f"></i> {{__('Login With Facebook')}}</a>
                        @endif
                        @if(get_static_option('enable_google_login'))
                            <a href="{{route('login.google.redirect')}}" class="google"><i class="lab la-google"></i> {{__('Login With Google')}}</a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>