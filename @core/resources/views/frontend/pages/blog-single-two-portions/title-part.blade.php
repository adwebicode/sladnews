
<h3 class="main-title v-02">
    <a>{{ Str::words($blog_post->title,10) }}</a>
</h3>

<div class="tag-box v-02">
    @foreach($blog_post->category_id as $key => $cat)
        <a href="{{route('frontend.blog.category',['id'=> $cat->id,'any'=> Str::slug($cat->title)])}}"
           class="category-style-01 v-02 {{$colors[$key % count($colors)]}}">{{$cat->getTranslation('title',$user_select_lang_slug) ?? __('Uncategorized')}}</a>
    @endforeach
</div>

<div class="post-meta-main v-02">
    <div class="post-meta">
        <ul class="post-meta-list">
            <li class="post-meta-item">
                <a href="{{$created_by_url}}">
                    {!!  $created_by_image !!}
                    <span class="text">{{$created_by}}</span>
                </a>
            </li>
            <li class="post-meta-item date">
                <span class="text">{{$date}}</span>
            </li>
            <li class="post-meta-item">
                <a href="#">
                    <span class="text">{{$blogCommentCount}} {{__('Comments')}}</span>
                </a>
            </li>
               <li class="post-meta-item">
                <i class="lab la-readme icon"></i>
                    <span class="text">{{$read_duration. " min read"}}</span>
            </li>
            
             <li class="post-meta-item date">
                <span class="text">{{__('Last Updated At : ')}}{{$updated_at}}</span>
            </li>
        </ul>
    </div>
</div>