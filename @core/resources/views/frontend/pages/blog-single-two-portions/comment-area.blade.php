<div class="comment-area-full-wrapper" data-padding-top="40">
    <!-- User comment area start -->
    <div class="user-comment-area" >
        <div class="comment-section-title section-title-style-02">

            @if($blogCommentCount > 0)
                <h3 class="title"><span class="total">
                    {{sprintf('%s %s ',
                    $blogCommentCount,
                       get_static_option( 'blog_single_page_comments_'.get_user_lang().'_text')
                    )}}

               </span> </h3>
            @endif
        </div>

        <div class="comments-inner">

            <div class="comments-flex-contents" id="comment_content_div">
                {{ csrf_field() }}
                <div id="comment_data" data-items="5">
                    @include('frontend.partials.pages-portion.comment-show-data')
                </div>

                @if($blogComments->count())
                    @if($blogComments->count() > 4)
                        <div class="load_more_div mt-4 btn-wrapper">
                            <button  class="load-more-btn btn-default d-block w-100 " id="load_more_comment_button">{{__('Load More')}}</button>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

   <div class="custom-login">
       @if(!auth()->guard('web')->check())
           @include('frontend.partials.ajax-user-login-markup',['title' => get_static_option('blog_single_page_login_title_'.$user_select_lang_slug.'_text')])
       @endif
   </div>



    @if(auth()->guard('web')->check())
        <div class="comment-form-area" data-padding-top="70">
            <div class="comment-section-title section-title-style-02">
                <h3 class="title">{!! get_static_option('blog_single_page_comments_'.get_user_lang().'_title_text') !!}</h3>
            </div>

            <form action="{{route('blog.comment.store')}}" class="comment-form" id="blog-comment-form">
                @csrf
                <div class="error-message"></div>
                <div class="row">
                    <input type="hidden" name="comment_id"/>
                    <input type="hidden" name="blog_id" id="blog_id"
                           value="{{$blog_post->id}}">
                    <input type="hidden" name="user_id" id="user_id"
                           value="{{auth()->guard('web')->user()->id}}">

                    <input type="hidden" name="commented_by" id="commented_by"
                           value="{{auth()->guard('web')->user()->name}}">

                    <div class="col-lg-12">
                        <div class="form-group">
                            <textarea name="comment_content" id="comment_content" class="form-control" placeholder="Comments" cols="30" rows="10" ></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="btn-wrapper">
                            <button type="submit" class="btn-default transparent-btn" id="submitComment">{!! get_static_option('blog_single_page_comments_button_'.get_user_lang().'_text') !!}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif
</div>

</div>



