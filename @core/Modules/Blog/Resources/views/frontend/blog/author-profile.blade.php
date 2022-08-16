@extends('frontend.frontend-page-master')

@section('site-title')
    {{$author_info->name}}
@endsection

@section('page-title')

    <li class="list-item"><a href="#">{{__('Author')}}</a></li>
    <li class="list-item"><a href="#">{{$author_info->name}}</a></li>
@endsection

@section('custom-page-title')
    {{$author_info->name}}
@endsection

@section('page-meta-data')
    {!! render_site_meta() !!}
    {!! render_site_title($author_info->name) !!}
@endsection

@section('content')

    <div class="author-profile-area-wrapper" data-padding-top="100" data-padding-bottom="90">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="author-profile">
                        <div class="row">
                            <div class="col-lg-4">
                                @php
                                    $img = get_attachment_image_by_id($author_info->image);
                                @endphp
                                <div class="img-box">
                                    {!! render_image_markup_by_attachment_id($author_info->image,'','grid') ?? '' !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="content">
                                    <h4 class="title">{{$author_info->name}}</h4>
                                    <p class="designation">{{$author_info->designation}}</p>

                                    <p class="info">{!! $author_info->description !!}</p>


                                    <div class="widget-area-wrapper">
                                        <div class="widget">
                                            <div class="social-link style-04 v-02">
                                                <ul class="widget-social-link-list">
                                                    <li class="single-item">
                                                        <a href="{{$author_info->facebook_url}}" class="left-content">
                                                            <span class="icon facebook">
                                                                <i class="lab la-facebook-f"></i>
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="single-item">
                                                        <a href="{{$author_info->twitter_url}}" class="left-content">
                                                            <span class="icon twitter">
                                                                <i class="lab la-twitter"></i>
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="single-item">
                                                        <a href="{{$author_info->instagram_url}}" class="left-content">
                                                            <span class="icon youtube">
                                                                <i class="lab la-youtube"></i>
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="single-item">
                                                        <a href="{{$author_info->linkedin_url}}" class="left-content">
                                                            <span class="icon instagram">
                                                                <i class="lab la-instagram"></i>
                                                            </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="author-post-area-wrapper" data-padding-top="0" data-padding-bottom="100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="author-post-title"> {{__('Author Post')}} </h4>
                </div>
            </div>

            <div class="row three-column">
                @if(count($all_blogs) < 1)
                    <div class="col-md-12">
                         <span class="text-dark">{!! __('No post found related to : ').' '. '<span class="text-warning"> '.$author_info->name.' </span>'!!}</span>
                    </div>
               </div>
                @else

              @foreach($all_blogs as $data)
                    <div class="col-md-6 col-lg-4">
                        <div class="blog-grid-style-01">
                            <div class="img-box">
                                {!! render_image_markup_by_attachment_id($data->image, '', 'grid') !!}
                            </div>
                            <div class="content">
                                <div class="post-meta">
                                    <ul class="post-meta-list">
                                        <li class="post-meta-item">
                                            @foreach($data->category_id as $key => $cat)
                                                <a href="{{route('frontend.blog.category',['id'=> $cat->id,'any'=> Str::slug($cat->title)])}}">  <i class="las la-tag icon"></i><span class="text">{{$cat->getTranslation('title',$user_select_lang_slug) ?? __('Uncategorized')}}</span></a>
                                            @endforeach
                                        </li>

                                        <li class="post-meta-item date">
                                            <i class="lar la-clock icon"></i>
                                            <span class="text">{{date('d M Y',strtotime($data->created_at))}} </span>
                                        </li>
                                    </ul>
                                </div>
                                <h4 class="title">
                                    <a href="{{route('frontend.blog.single',$data->slug)}}">{{Str::words($data->getTranslation('title',$user_select_lang_slug) ?? '',10)}}</a>
                                </h4>
                            </div>
                        </div>
                    </div>
               @endforeach

            </div>
            <div class="row">
                <div class="col-lg-12 text-center mt-5">
                    <div class="pagination-wrapper text-center" aria-label="Page navigation" data-padding-bottom="0">
                        {{$all_blogs->links()}}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

@endsection
