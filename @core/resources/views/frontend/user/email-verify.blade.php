@extends('frontend.frontend-page-master')
@section('site-title')
    {{__('Verify Your Account')}}
@endsection
@section('page-title')
    {{__('Verify Your Account')}}
@endsection
@section('content')
    <section class="login-page-wrapper my-5">
        <div class="container">
            <div class="row justify-content-center margin-bottom-90 margin-top-90">
                <div class="col-lg-6">
                    <div class="login-form-wrapper">
                        <h3 class="text-center margin-bottom-30">{{__('Verify Your Account')}}</h3>
                        @include('backend.partials.message')
                        <div class="alert alert-warning alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ __('Check Mail for Verification code.') }}</strong>
                        </div>
                        <x-msg.error/>
                        <form action="{{route('user.email.verify')}}" method="post" enctype="multipart/form-data" class="contact-page-form style-01">
                            @csrf
                            <div class="form-group mt-2">
                                <input type="text" name="verification_code" class="form-control" placeholder="{{__('Verify Code')}}">
                            </div>
                            <div class="form-group btn-wrapper my-3">
                                <button type="submit" id="verify" class="btn-default btn-block">{{__('Verify Email')}}</button>
                            </div>
                            <div class="row mb-4 rmber-area">
                                <div class="col-12 text-center">
                                    <a href="{{route('user.resend.verify.mail')}}" id="send">{{__('Send Verify Code Again?')}}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        (function($){
        "use strict";
        $(document).ready(function () {
            <x-btn.custom :id="'verify'" :title="__('Verifying')"/>
            <x-btn.custom :id="'send'" :title="__('Sending Verify Code')"/>
        });
        })(jQuery);
    </script>
@endsection