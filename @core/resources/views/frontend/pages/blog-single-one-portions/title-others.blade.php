<h3 class="main-title">
    <a>{{ Str::words($blog_post->title,8) }}</a>
</h3>

<div class="post-meta-main">
    <div class="post-meta">
        <ul class="post-meta-list">
            <li class="post-meta-item">
                <a href="{{$created_by_url}}">
                    {!!  $created_by_image !!}
                    <span class="text">{{$created_by}}</span>
                </a>
            </li>
            <li class="post-meta-item date">
                <i class="lar la-clock icon"></i>
                <span class="text">{{$date}}</span>
            </li>
            <li class="post-meta-item">
                <a href="#">
                    <i class="lar la-comments icon"></i>
                    <span class="text">{{$blogCommentCount}}</span>
                </a>
            </li>
               <li class="post-meta-item">
                     <i class="lab la-readme icon"></i>
                    <span class="text">{{$read_duration. " min read"}}</span>
            </li>
            
            <li class="post-meta-item date">
                <i class="lar la-clock icon"></i>
                <span class="text">{{ __('Last Updated At :')}} {{$updated_at}}</span>
            </li>
            
        </ul>
    </div>
</div>

<div class="details-one-page-para">
    <p class="info info-01">{!!   $blog_post->blog_content ?? '' !!} </p>
</div>

@php
    $tags_arr = json_decode($blog_post->tag_id);
    $all_tags = is_array($tags_arr) ? implode(",", $tags_arr) : "";
@endphp



    <div class="tag-and-social-link">
        @if(!empty($tags_arr) && count($tags_arr) > 0)
        <div class="tag-wrap">
            <ul>
                @if(!empty($tags_arr[0]))<li class="name">{{__('Tags :')}}</li>@endif
                @foreach($tags_arr as $i)

                    @if(!empty($i))
                        <li><a class="tag-btn" href="{{ route('frontend.blog.tags.page', [ 'any'=> $i ?? 'u']) }}">{{$i}}</a></li>
                    @endif
                @endforeach
            </ul>
        </div>
        @endif
        <div class="social-link-wrap">
            <div class="social-icon">
                <ul class="social-link-list">
                    <li class="name">{{__('share :')}}</li>
                     {!! single_post_share(route('frontend.blog.single',['id' => $blog_post->id, 'slug' => Str::slug($blog_post->title,'-')]),$blog_post->title,$blog_post->image) !!}
                </ul>
            </div>
        </div>
    </div>
