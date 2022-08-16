@extends('backend.admin-master')
@section('style')
    <x-summernote.css/>
    <link rel="stylesheet" href="{{asset('assets/backend/css/dropzone.css')}}">
@endsection
@section('site-title')
    {{__('Send Mail To All Newsletter Subscriber')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                @include('backend/partials/message')
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__('Send Mail To All Newsletter Subscriber')}}</h4>
                        <form action="{{route('admin.newsletter.mail')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="edit_icon">{{__('Subject')}}</label>
                                <input type="text" class="form-control"  id="subject" name="subject" placeholder="{{__('Subject')}}">
                            </div>
                            <div class="form-group">
                                <label for="message">{{__('Message')}}</label>
                                <input type="hidden" name="message" >
                                <div class="summernote"></div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-lg btn-primary">{{__('Send Mail')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('assets/backend/js/summernote-bs4.js')}}"></script>
    <x-summernote.js/>
    <script src="{{asset('assets/backend/js/dropzone.js')}}"></script>

@endsection
