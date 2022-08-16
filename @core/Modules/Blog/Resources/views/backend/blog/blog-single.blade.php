@extends('backend.admin-master')
@section('site-title')
    {{__('News Single Page Settings')}}
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
                        <h4 class="header-title">{{__('Blog Single Page Settings')}}</h4>
                        <form action="{{route('admin.blog.single.settings')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    @foreach($all_languages as $key => $lang)
                                        <a class="nav-item nav-link @if($key == 0) active @endif"  data-toggle="tab" href="#nav-home-{{$lang->slug}}" role="tab" aria-selected="true">{{$lang->name}}</a>
                                    @endforeach
                                </div>
                            </nav>
                            <div class="tab-content margin-top-30" id="nav-tabContent">
                                @foreach($all_languages as $key => $lang)
                                    <div class="tab-pane fade @if($key == 0) show active @endif" id="nav-home-{{$lang->slug}}" role="tabpanel" >
                                        <div class="form-group">
                                            <label for="blog_single_page_{{$lang->slug}}_related_post_title">{{__('Related Post Title')}}</label>
                                            <input type="text" class="form-control" value="{{get_static_option('blog_single_page_'.$lang->slug.'_related_post_title')}}" name="blog_single_page_{{$lang->slug}}_related_post_title" placeholder="{{__('Related Post Title')}}">
                                        </div>

                                        <div class="form-group">
                                            <label for="blog_single_page_{{$lang->slug}}_share_title">{{__('Comments Text')}}</label>
                                            <input type="text" class="form-control" value="{{get_static_option('blog_single_page_comments_'.$lang->slug.'_text')}}" name="blog_single_page_comments_{{$lang->slug}}_text" placeholder="{{__('Comments Text')}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="blog_single_page_{{$lang->slug}}_share_title">{{__('Comments Title Text')}}</label>
                                            <input type="text" class="form-control" value="{{get_static_option('blog_single_page_comments_'.$lang->slug.'_title_text')}}" name="blog_single_page_comments_{{$lang->slug}}_title_text" placeholder="{{__('Comments Title Text')}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="blog_single_page_{{$lang->slug}}_category_title">{{__('Comments Button Text')}}</label>
                                            <input type="text" class="form-control" value="{{get_static_option('blog_single_page_comments_button_'.$lang->slug.'_text')}}" name="blog_single_page_comments_button_{{$lang->slug}}_text" placeholder="{{__('Comments Button Text')}}">
                                        </div>

                                        <div class="form-group">
                                            <label for="blog_single_page_{{$lang->slug}}_category_title">{{__('Login Title Text')}}</label>
                                            <input type="text" class="form-control" value="{{get_static_option('blog_single_page_login_title_'.$lang->slug.'_text')}}" name="blog_single_page_login_title_{{$lang->slug}}_text" placeholder="{{__('Login Title')}}">
                                        </div>

                                        <div class="form-group">
                                            <label for="blog_single_page_{{$lang->slug}}_category_title">{{__('Login Button Text')}}</label>
                                            <input type="text" class="form-control" value="{{get_static_option('blog_single_page_login_button_'.$lang->slug.'_text')}}" name="blog_single_page_login_button_{{$lang->slug}}_text" placeholder="{{__('Login Button Text')}}">
                                        </div>


                                    </div>
                                @endforeach
                            </div>
                                  <x-image :title="'Avatar Image'" :name="'single_blog_page_comment_avatar_image'" :dimentions="'160x50'"/>

                            <button id="update" type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Blog Page Settings')}}</button>
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
