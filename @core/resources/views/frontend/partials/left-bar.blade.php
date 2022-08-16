@if(!empty(get_static_option('leftbar_show_hide')))

    <div class="hamburger-c w3-sidebar w3-bar-block w3-border-right" id="mySidebar">
        <button onclick="w3_close()" class="close-h w3-bar-item w3-large"> &times;</button>
        <div class="main-content">
            <div class="logo-wrapper">
                @if(get_static_option('site_frontend_dark_mode') == 'on')
                <a href="{{url('/')}}">
                    {!! render_image_markup_by_attachment_id(get_static_option('site_white_logo')) !!}
                </a>
                @else
                    <a href="{{url('/')}}">
                        {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
                    </a>
               @endif
            </div>

            <div class="widget-area-wrapper">
                <div class="widget">
                    <div class="recent-post style-01">
                        <h4 class="widget-title style-01">{{get_static_option('leftbar_blog_'.get_user_lang().'_title')}}</h4>
                        <ul class="news-headline-list style-01">
                            @foreach($blogs_for_leftbar as $data)
                            <li class="news-heading-item">
                                <h3 class="title">
                                    <a href="{{route('frontend.blog.single',$data->slug)}}">{{$data->title ?? ''}}</a>
                                </h3>
                                <div class="post-meta">
                                    <ul class="post-meta-list style-02">
                                        @if($data->created_by == 'user')
                                            @php $user = $data->user; @endphp
                                        @else
                                            @php $user = $data->admin; @endphp
                                        @endif
                                        <li class="post-meta-item">
                                            <a @if(!empty($user->id))  href="{{route('frontend.user.created.blog', ['user'=> $data->created_by, 'id'=>$user->id])}}" @endif>
                                                <span class="text author"> {{$data->author ?? __('Anonymous')}}</span>
                                            </a>
                                        </li>
                                        <li class="post-meta-item date">
                                            <span class="text">{{date('d M, Y',strtotime($data->created_at))}}</span>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="widget">
                    <div class="tag style-03">
                        <h4 class="widget-title style-01">{!! get_static_option('leftbar_tag_'.$user_select_lang_slug.'_title') !!}</h4>
                        <ul class="tag-list">
                            @php  $colors = ['color-a','color-b','color-c','color-d','color-e','color-f','color-g']; @endphp
                            @foreach($tags_for_leftbar as $key=> $tag)
                            <li class="single-tag-item">
                                <a href="{{route('frontend.blog.tags.page', ['any' => $tag->name])}}" class="{{ $colors[$key % count($colors)] }}">{{$tag->name}}</a>
                            </li>
                             @endforeach
                        </ul>
                    </div>
                </div>

                <div class="widget">
                    <div class="social-link style-04 v-02">
                        <h4 class="widget-title style-01">{!! get_static_option('leftbar_social_'.$user_select_lang_slug.'_title') !!}</h4>
                        <ul class="widget-social-link-list">
                            @php  $Socialcolors = ['facebook','twitter','youtube','instagram','pinterest','linkedin']; @endphp
                            @foreach($social_icons_for_leftbar as $key=>  $social)
                            <li class="single-item">
                                <a href="{{$social->details}}" class="left-content">
                                    <span class="icon {{$Socialcolors[$key % count($Socialcolors)]}}">
                                        <i class="{{$social->icon}}"></i>
                                    </span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif