<section class="dynamic-page-content-area" data-padding-top="100" data-padding-bottom="70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @if(!auth()->guard('web')->check() && $page_post->visibility === 'all')
                    <div class="dynamic-page-content-wrap">
                        {!! $page_post->getTranslation('page_content',$user_select_lang_slug) !!}
                    </div>
                @elseif(auth()->guard('web')->check())
                    <div class="dynamic-page-content-wrap">
                        {!! $page_post->getTranslation('page_content',$user_select_lang_slug) !!}
                    </div>
                @else
                    <div class="alert alert-warning">
                        <p><a class="text-primary" href="{{route('user.login')}}">{{__('Login')}}</a> {{__(' to see this page')}} </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>