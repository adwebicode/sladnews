@extends('backend.admin-master')
@section('site-title')
    {{__('Miscellaneous Settings')}}
@endsection

@section('style')
   <x-media.css/>
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
              <x-msg.success/>
              <x-msg.error/>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__('Miscellaneous Settings')}}</h4>
                        <form action="{{route('admin.misc.settings')}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="row">

                                <div class="form-group col-lg-6">
                                    <label>{{__('Terms of Service URL')}}</label>
                                    <input type="text" class="form-control" value="{{get_static_option('terms_of_service_url')}}" name="terms_of_service_url" placeholder="{{__('Terms of Service URL')}}">
                                </div>

                                <div class="form-group col-lg-6">
                                    <label>{{__('Condition URL')}}</label>
                                    <input type="text" class="form-control" value="{{get_static_option('condition_url')}}" name="condition_url" placeholder="{{__('Condition URL')}}">
                                </div>

                                <div class="form-group col-lg-6">
                                    <label>{{__('Privacy Policy URL')}}</label>
                                    <input type="text" class="form-control" value="{{get_static_option('privacy_policy_url')}}" name="privacy_policy_url" placeholder="{{__('Privacy Policy URL')}}">
                                </div>
                            </div>


                            <button id="update" type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Misc Settings')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection

@section('script')
    <x-media.js/>
    <script>
        <x-btn.update/>
    </script>
@endsection
