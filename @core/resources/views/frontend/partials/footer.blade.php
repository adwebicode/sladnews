
@include('frontend.partials.pages-portion.footers.footer-'.get_footer_style())


<div class="scroll-to-top">
    <i class="las la-chevron-up"></i>
</div>

@if(preg_match('/(xgenious)/',url('/')))
<div class="buy-now-wrap">
    <!--<ul class="buy-list">-->
    <!--    <li><a target="_blank" href="https://bytesed.com/docs-category/intoday-new-magazine-php-laravel-scripts/" data-container="body" data-toggle="popover" data-placement="left" data-content="Documentation"><i class="las la-file-alt"></i></a></li>-->
    <!--    <li><a target="_blank" href="https://codecanyon.net/checkout/from_item/35217402?license=regular&aid=byteseed&aso=xgenius-website&aca=purchase-btn-click"><i class="las la-shopping-cart"></i></a></li>-->
    <!--    <li><a target="_blank" href="https://xgenious51.freshdesk.com/"><i class="las la-headset"></i></a></li>-->
    <!--</ul>-->
</div>
@endif

<script src="{{asset('assets/frontend/js/popper.min.js')}}"></script>
<script src="{{asset('assets/frontend/js/bootstrap.min-v4.6.0.js')}}"></script>
<script src="{{asset('assets/frontend/js/dynamic-script.js')}}"></script>
<!--<script src="{{asset('assets/frontend/js/compress.js')}}"></script>-->
<script src="{{asset('assets/frontend/js/slick.min.js')}}"></script>
<script src="{{asset('assets/frontend/js/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset('assets/common/js/sweetalert2.js')}}"></script>
<script src="{{asset('assets/frontend/js/jquery.fancybox.min.js')}}"></script>
<script src="{{ asset('assets/frontend/js/lazy.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/lazy.plugin.js') }}"></script>
<script src="{{asset('assets/frontend/js/main.js')}}"></script>



@if(!empty(get_static_option('site_google_captcha_v3_site_key')))
    <script rel="preload" src="https://www.google.com/recaptcha/api.js?render={{get_static_option('site_google_captcha_v3_site_key')}}"></script>
    <script>
        (function() {
            "use strict";
            grecaptcha.ready(function () {
                grecaptcha.execute("{{get_static_option('site_google_captcha_v3_site_key')}}", {action: 'homepage'}).then(function (token) {
                    if(document.getElementById('gcaptcha_token') != null){
                        document.getElementById('gcaptcha_token').value = token;
                    }
                });
            });
        })(jQuery);

    </script>
@endif

    @php $twak__api = get_static_option('site_third_party_tracking_code'); @endphp
    @if(!empty($twak__api))
        {!! $twak__api !!}
    @endif

<script>

    //RTL RIGHT INner Bar
    var enable_rtl = "{{get_user_lang_direction() === 'rtl'}}";
    if(enable_rtl){
        document.getElementById("mySidebar").style.transform = "translateX(100%)";

        function w3_close() {
            document.getElementById("mySidebar").style.transform = "translateX(100%)";
        }
    }

</script>

    @include('frontend.partials.inline-scripts')
@stack('scripts')


</body>
</html>
