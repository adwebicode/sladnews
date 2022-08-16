@extends('frontend.frontend-page-master')
@section('site-title')
    {{__('Reset Password')}}
@endsection
@section('page-title')
    {{__('Reset Password')}}
@endsection
@section('content')
    <section class="login-page-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="login-form-wrapper">
                        <h3 class="text-center margin-bottom-30">{{__('Reset Password')}}</h3>
                        @include('backend.partials.message')
                        @include('backend.partials.error')
                        <form action="{{route('user.reset.password.change')}}" method="post" enctype="multipart/form-data" class="contact-page-form style-01">
                            @csrf
                            <input type="hidden" name="token" value="{{$token}}">
                            <div class="form-group">
                                <input type="text" id="username" class="form-control" readonly value="{{$username}}" name="username">
                            </div>
                            <div class="form-group">
                                <input type="password" id="password" class="form-control" name="password" placeholder="Enter New Password">
                            </div>
                            <div class="form-group">
                                <input type="password" id="password_confirmation"  class="form-control" name="password_confirmation" placeholder="Confirm Password">
                            </div>
                            <div class="form-group btn-wrapper">
                                <button class="boxed-btn btn-saas btn-block" type="submit">{{__('Reset Password')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

