
@if(count($all_related_blog) > 0)
<div class="related-post-area" data-padding-top="100">
    <div class="row">
        <div class="col-lg-12">
            <div class="section-title-style-02">
                <h3 class="title">{{__('Related Post')}} </h3>
                <div class="appendarow"></div>
            </div>
        </div>
        @foreach($all_related_blog as $data)
        <div class="col-lg-6">
            <div class="blog-grid-style-03 small-02">
                <div class="img-box">
                    {!! render_image_markup_by_attachment_id($data->image) !!}
                </div>
                <div class="content">
                    <div class="post-meta">
                        <ul class="post-meta-list style-02">
                            @php
                                if ($data->created_by === 'user') {
                                  $user_id = $data->user_id;
                                  } else {
                                      $user_id = $data->admin_id;
                                  }

                                $created_by_url = !is_null($user_id) ?  route('frontend.user.created.blog', ['user' => $data->created_by, 'id' => $user_id]) : route('frontend.blog.single',$data->slug);
                            @endphp
                            <li class="post-meta-item">
                                <a href="{{$created_by_url}}">
                                    <span class="text author">{{$data->author ?? ''}}</span>
                                </a>
                            </li>
                            <li class="post-meta-item date">
                                <span class="text">{{date('D m, Y',strtotime($data->created_at))}}</span>
                            </li>
                        </ul>
                    </div>
                    <h4 class="title font-size-24 font-weight-600">
                        <a href="{{route('frontend.blog.single',$data->slug)}}">{{ Str::words($data->title,15) ?? ''}}</a>
                    </h4>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif