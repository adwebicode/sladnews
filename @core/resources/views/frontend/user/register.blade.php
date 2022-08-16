@extends('frontend.frontend-page-master')
@section('site-title')
    {{__('Register')}}
@endsection
@section('page-title')
    <li class="list-item"><a href="#">{{__('User Register')}}</a></li>
@endsection

@section('custom-page-title')
    {{__('User Register')}}
@endsection

@section('content')

    <div class="sign-in-area-wrapper" data-padding-top="95" data-padding-bottom="95">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="sign-up register">
                        <h4 class="title">{{__('sign up')}}</h4>
                        <div class="form-wrapper">
                            @include('backend.partials.message')
                            @include('backend.partials.error')
                            <form action="{{route('user.register')}}" method="post" enctype="multipart/form-data" class="contact-page-form style-01">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 col-lg-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{__('Name')}}<span
                                                        class="required">*</span></label>
                                            <input type="text" class="form-control" name="name" id="exampleInputEmail1"
                                                   placeholder="Type your Name" value="{{old('name')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{__('Username')}}<span
                                                        class="required">*</span></label>
                                            <input type="text" class="form-control" name="username" id="exampleInputEmail1"
                                                   placeholder="Type Last Name" value="{{old('username')}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">{{__('Email Address')}}<span class="required">*</span></label>
                                    <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Type your mail">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">{{__('Country')}}<span class="required">*</span></label>
                                   {!! get_country_field('country','country','form-control') !!}
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">{{__('City')}}<span class="required">*</span></label>
                                    <input type="text" name="city" class="form-control" id="exampleInputEmail1" placeholder="Type your city" value="{{old('city')}}">
                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-lg-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{__('Password')}}<span
                                                        class="required">*</span></label>
                                            <input type="password" name="password" class="form-control" id="exampleInputPassword1"
                                                   placeholder="Password">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{__('Confirmed Password')}}<span
                                                        class="required">*</span></label>
                                            <input type="password" name="password_confirmation" class="form-control" id="exampleInputPassword1"
                                                   placeholder="Confirmed Password">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-check">
                                    <div class="box-wrap sign-up">
                                        <div class="left w-100">
                                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                            <label class="form-check-label" for="exampleCheck1"> {{__('By creating an account')}},
                                                {{__('you agree to the')}} <a href="{{get_static_option('terms_of_service_url')}}">{{__('terms of service')}}</a> {{__('and')}}
                                                <a href="{{get_static_option('condition_url')}}">{{__('Conditions')}}</a>{{__(',  and')}} <a href="{{get_static_option('privacy_policy_url')}}">{{__('privacy policy')}}</a> </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="btn-wrapper">
                                    <button type="submit" class="btn-default rounded-btn">{{__('sign up')}}</button>
                                </div>
                            </form>
                            <p class="info">{{__('Already have an Account?')}} <a href="{{route('user.login')}}" class="active">{{__('Sign in')}}</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
    <script>
        (function($){
        "use strict";
        $(document).ready(function () {
            <x-btn.custom :id="'register'" :title="__('Please Wait..')"/>
        });
        })(jQuery);
    </script>
@endsection
