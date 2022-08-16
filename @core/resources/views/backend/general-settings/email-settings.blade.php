@extends('backend.admin-master')
@section('site-title')
    {{__('Email Settings')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                @include('backend.partials.message')
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__("Email Settings")}}</h4>
                        @if($errors->any())
                            @foreach($errors->all() as $error)
                                <div class="alert alert-danger">{{$error}}</div>
                             @endforeach
                        @endif
                        <form action="{{route('admin.general.email.settings')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    @foreach($all_languages as $key => $lang)
                                        <a class="nav-item nav-link @if($key == 0) active @endif" id="nav-home-tab" data-toggle="tab" href="#nav-home-{{$lang->slug}}" role="tab" aria-controls="nav-home" aria-selected="true">{{$lang->name}}</a>
                                    @endforeach
                                </div>
                            </nav>
                            <div class="tab-content margin-top-30" id="nav-tabContent">
                                @foreach($all_languages as $key => $lang)
                                    <div class="tab-pane fade @if($key == 0) show active @endif" id="nav-home-{{$lang->slug}}" role="tabpanel" aria-labelledby="nav-home-tab">
                                        <div class="form-group">
                                            <label for="order_mail_{{$lang->slug}}_success_message">{{__('Order Mail Success Message')}}</label>
                                            <input type="text" name="order_mail_{{$lang->slug}}_success_message"  class="form-control" value="{{get_static_option('order_mail_'.$lang->slug.'_success_message')}}" >
                                            <small class="form-text text-muted">{{__('this message will show when any one place order.')}}</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="contact_mail_{{$lang->slug}}_success_message">{{__('Contact Mail Success Message')}}</label>
                                            <input type="text" name="contact_mail_{{$lang->slug}}_success_message"  class="form-control" value="{{get_static_option('contact_mail_'.$lang->slug.'_success_message')}}" >
                                            <small class="form-text text-muted">{{__('this message will show when any one contact you via contact page form.')}}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button id="update" type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        <x-btn.update/>
    </script>
@endsection
