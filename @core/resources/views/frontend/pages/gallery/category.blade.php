@extends('frontend.frontend-page-master')
@section('page-title')
    {{__('Category : ' ).$image_category}}
@endsection
@section('site-title')
    {{$image_category}}
@endsection

@section('page-meta-data')
    {!! render_site_meta() !!}
@endsection

@section('content')
    <section class="blog-content-area padding-top-120 padding-bottom-25 ">
        <div class="container margin-bottom-120">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        @if(count($all_images) < 1)
                            <div class="col-lg-12">
                                <div class="alert alert-warning alert-block col-md-12">
                                    <strong><div class="error-message"><span>{{__('No Image Available In : ').$image_category.__(' Category')}}</span></div></strong>
                                </div>
                            </div>
                        @endif
                        @foreach($all_images as $data)
                            <div class="col-md-6 col-lg-6">
                                <div class="single-gallery-item" >
                                    @php
                                        $gallery_img = get_attachment_image_by_id($data->image,'full',false);
                                        $img_url = !empty($gallery_img) ? $gallery_img['img_url'] : '';
                                    @endphp
                                        <a href="{{$img_url}}" class="image-popup" data-toggle="tooltip" title="{{$data->title}}">
                                    <div class="img-box" >
                                          {!! render_image_markup_by_attachment_id($data->image,'','grid') !!}
                                    </div>
                                        </a>

                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-lg-12">
                        <nav class="pagination-wrapper" aria-label="Page navigation ">
                            {{$all_images->links()}}
                        </nav>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="widget-area-wrapper style-02 padding-reverse">
                        {!! render_frontend_sidebar('blog',['column' => false]) !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
