@extends('backend.admin-master')

@section('site-title')
    {{__('Blog Details Variant Settings')}}
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <x-msg.success/>
                <x-msg.error/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__("Blog Details Variant Settings")}}</h4>
                        <form action="{{route('admin.blog.details.variant.settings')}}" method="POST" enctype="multipart/form-data">@csrf
                            @csrf
                            <div class="card mt-5">
                                <div class="card-body">
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" id="blog_details_variant" value="{{ get_static_option('blog_details_variant') }}" name="blog_details_variant">
                                    </div>
                                    <div class="row">
                                        @for($i = 1; $i < 3; $i++)
                                            <div class="col-lg-6 col-md-6">
                                                <div class="img-select selected">
                                                    <div class="img-wrap">
                                                        <img src="{{asset('assets/frontend/details-variant/'.$i.'.png')}}" data-home_id="0{{$i}}" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>

                            <button type="submit" id="update" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        (function($){
            "use strict";
            $(document).ready(function(){
                <x-icon-picker/>
                <x-btn.update/>

                //For Footer
                var imgSelect = $('.img-select');
                var id = $('#blog_details_variant').val();
                imgSelect.removeClass('selected');
                $('img[data-home_id="'+id+'"]').parent().parent().addClass('selected');
                $(document).on('click','.img-select img',function (e) {
                    e.preventDefault();
                    imgSelect.removeClass('selected');
                    $(this).parent().parent().addClass('selected').siblings();
                    $('#blog_details_variant').val($(this).data('home_id'));
                })

            });
        }(jQuery));
    </script>
@endsection
