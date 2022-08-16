@extends('frontend.frontend-page-master')
@section('site-title')
    {{__('Forget Password')}}
@endsection

@section('page-title')
    <li class="list-item"><a href="#">{{__('Forget Password')}}</a></li>
@endsection

@section('custom-page-title')
    {{__('Forget Password')}}
@endsection




@section('content')
    <section class="login-page-wrapper my-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="login-form-wrapper">
                        <h2 class="text-center margin-bottom-30">{{__('Forget Password ?')}}</h2>
                        @include('backend.partials.message')
                        @include('backend.partials.error')
                        <form action="{{route('user.forget.password')}}" method="post" enctype="multipart/form-data" class="contact-page-form style-01">
                            @csrf
                            <div class="form-group">
                                <input type="text" name="username" class="form-control" placeholder="{{__('Username')}}">
                            </div>
                            <div class="form-group btn-wrapper">
                                <button type="submit" id="send" class="boxed-btn btn-saas btn-block btn-default">{{__('Send Reset Mail')}}</button>
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
            <x-btn.custom :id="'send'" :title="__('Sending')"/>
        });
        })(jQuery);
    </script>
@endsection
