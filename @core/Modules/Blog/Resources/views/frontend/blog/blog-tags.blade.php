@extends('frontend.frontend-page-master')

@section('page-title')
    <li class="list-item"><a href="#">{{__('Tags')}}</a></li>
    <li class="list-item"><a href="#">{{$tag_name}}</a></li>
@endsection

@section('page-meta-data')
    {!! render_site_meta() !!}
    {!! render_site_title($tag_name) !!}
@endsection

@section('site-title')
    {{$tag_name}}
@endsection

@section('custom-page-title')
    {{$tag_name}}
@endsection

@section('content')


    <div class="blog-list-wrapper sports-blog-list-wrapper" data-padding-top="100" data-padding-bottom="100">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="one-column">
                        <div class="row">
                            @if(count($all_blogs) <= 0)
                                <div class="col-lg-12 mt-5">
                                    <div class="alert alert-warning alert-block col-md-12 ">
                                        <strong><div class="error-message "><span>{{__('No Post Available In - ').$tag_name.__(' : Tags')}}</span></div></strong>
                                    </div>
                                </div>
                            @endif

                            @foreach($all_blogs as $data)
                            <div class="col-lg-12">
                                <div class="blog-list-style-02">
                                    <div class="img-box">
                                        {!! render_image_markup_by_attachment_id($data->image, '', 'grid') !!}
                                    </div>
                                    <div class="content">
                                        <div class="post-meta">
                                            <ul class="post-meta-list style-02">
                                                @if($data->created_by == 'user')
                                                    @php $user = $data->user; @endphp
                                                @else
                                                    @php $user = $data->admin; @endphp
                                                @endif
                                                <li class="post-meta-item">
                                                    <a @if(!empty($user->id)) href="{{route('frontend.user.created.blog', ['user'=> $data->created_by, 'id'=>$user->id])}}"  @endif>
                                                        <span class="text author">{{$data->author ?? __('Anonymous')}}</span>
                                                    </a>
                                                </li>
                                                <li class="post-meta-item date">
                                                    <span class="text">{{date('d M Y',strtotime($data->created_at))}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <h4 class="title">
                                            <a href="{{route('frontend.blog.single',$data->slug)}}">{{$data->getTranslation('title',$user_select_lang_slug) ?? ' '}}</a>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                           @endforeach
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="pagination justify-content-center" data-padding-top="50">
                                    <div class="pagination-wrapper">
                                        {{$all_blogs->links()}}

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-7 col-md-6 col-lg-4">
                    <div class="widget-area-wrapper">
                        {!! render_frontend_sidebar('details_page_sidebar',['column' => false]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
