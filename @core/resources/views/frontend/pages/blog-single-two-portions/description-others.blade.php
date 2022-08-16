<div class="details-one-page-para">
    <p class="info info-01">{!!   $blog_post->blog_content ?? '' !!} </p>
</div>

@php
    $tags_arr = json_decode($blog_post->tag_id);
    $all_tags = is_array($tags_arr) ? implode(",", $tags_arr) : "";
@endphp


<div class="tag-and-social-link">
    <div class="social-link-wrap">
        <div class="social-icon">
            <ul class="widget-social-link-list">
                @if(!empty($tags_arr[0]))  <li class="name">{{__('Share:')}}</li>@endif
                {!! single_post_share_two(route('frontend.blog.single',['id' => $blog_post->id, 'slug' => Str::slug($blog_post->title,'-')]),$blog_post->title,$blog_post->image) !!}
            </ul>
        </div>
    </div>
@if(!is_null($tags_arr) && count($tags_arr) > 0)
    <div class="tag-wrap v-02">
        <ul>
            <li class="name">{{__('Tags :')}}</li>
            @foreach($tags_arr as $i)
                @if(!empty($i))
                    <li><a class="tag-btn" href="{{ route('frontend.blog.tags.page', [ 'any'=> $i ?? 'u']) }}">{{$i}}</a></li>
                @endif
            @endforeach

        </ul>
    </div>
@endif
</div>