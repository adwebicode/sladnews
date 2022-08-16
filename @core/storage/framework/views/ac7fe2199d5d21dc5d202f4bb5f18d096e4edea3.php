<div class="comment-area-full-wrapper" data-padding-top="40">
    <!-- User comment area start -->
    <div class="user-comment-area" >
        <div class="comment-section-title section-title-style-03">

            <?php if($blogCommentCount > 0): ?>
                <h3 class="title"><span class="total">
                    <?php echo e(sprintf('%s %s ',
                    $blogCommentCount,
                       get_static_option( 'blog_single_page_comments_'.get_user_lang().'_text')
                    )); ?>


               </span> </h3>
            <?php endif; ?>
        </div>

        <div class="comments-inner">

            <div class="comments-flex-contents" id="comment_content_div">
                <?php echo e(csrf_field()); ?>

                <div id="comment_data" data-items="5">
                    <?php echo $__env->make('frontend.partials.pages-portion.comment-show-data', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>

                <?php if($blogComments->count()): ?>
                    <?php if($blogComments->count() > 4): ?>
                        <div class="load_more_div mt-4 btn-wrapper">
                            <button  class="load-more-btn btn-default d-block w-100 " id="load_more_comment_button"><?php echo e(__('Load More')); ?></button>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="custom-login" data-padding-top="50">
        <?php if(!auth()->guard('web')->check()): ?>
            <?php echo $__env->make('frontend.partials.ajax-user-login-markup',['title' => get_static_option('blog_single_page_login_title_'.$user_select_lang_slug.'_text')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
    </div>


    <?php if(auth()->guard('web')->check()): ?>
        <div class="comment-form-area" data-padding-top="0">
            <div class="comment-section-title section-title-style-03">
                <h3 class="title"><?php echo get_static_option('blog_single_page_comments_'.get_user_lang().'_title_text'); ?></h3>
            </div>

            <form action="<?php echo e(route('blog.comment.store')); ?>" class="comment-form" id="blog-comment-form">
                <?php echo csrf_field(); ?>
                <div class="error-message"></div>
                <div class="row">
                    <input type="hidden" name="comment_id"/>
                    <input type="hidden" name="blog_id" id="blog_id"
                           value="<?php echo e($blog_post->id); ?>">
                    <input type="hidden" name="user_id" id="user_id"
                           value="<?php echo e(auth()->guard('web')->user()->id); ?>">

                    <input type="hidden" name="commented_by" id="commented_by"
                           value="<?php echo e(auth()->guard('web')->user()->name); ?>">

                    <div class="col-lg-12">
                        <div class="form-group">
                            <textarea name="comment_content" id="comment_content" class="form-control" placeholder="Comments" cols="30" rows="10" ></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="btn-wrapper">
                            <button type="submit" class="btn-default transparent-btn" id="submitComment"><?php echo get_static_option('blog_single_page_comments_button_'.get_user_lang().'_text'); ?></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div><?php /**PATH /home/sohan/public_html/katerio/@core/resources/views/frontend/pages/blog-single-one-portions/comment-area.blade.php ENDPATH**/ ?>