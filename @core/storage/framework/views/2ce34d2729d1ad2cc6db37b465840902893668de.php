
<?php
    $post_img = null;
    $blog_image = get_attachment_image_by_id($blog_post->image,"full",false);
    $post_img = !empty($blog_image) ? $blog_image['img_url'] : '';
    $colors = ['bg-color-e','bg-color-a','bg-color-b','bg-color-g','bg-color-c'];
    $session_user_given_password_get = \Illuminate\Support\Facades\Session::get('user_given_password');

      $author = NULL;
       if(!is_null($blog_post->user_id)){
               $author = optional($blog_post->user);
           }else if(!is_null($blog_post->admin_id)){
               $author = optional($blog_post->admin);
           }else{
               $author = optional($blog_post->admin);
           }

    //Author image
    $user_image = render_image_markup_by_attachment_id(optional($author)->image, 'image');
    $avatar_image = render_image_markup_by_attachment_id(get_static_option('single_blog_page_comment_avatar_image'),'image');
    $created_by_image = $user_image ? $user_image : $avatar_image;

    $created_by = $blog_post->author ?? __('Anonymous');

          if ($blog_post->created_by === 'user') {
                $user_id = $blog_post->user_id;
            } else {
                $user_id = $blog_post->admin_id;
            }

    $created_by_url = !is_null($user_id) ?  route('frontend.user.created.blog', ['user' => $blog_post->created_by, 'id' => $user_id]) : route('frontend.blog.single',$blog_post->slug);
    $date = date('M d, Y',strtotime($blog_post->created_at));
    $updated_at = date('M d, Y',strtotime($blog_post->updated_at));
?>

<?php $__env->startSection('page-title'); ?>
    <?php $page_slug = get_blog_slug_by_page_id(get_static_option('blog_page')) ?>
    <li class="list-item"><a href="<?php echo e(route('frontend.dynamic.page',$page_slug)); ?>"><?php echo e(__('Blog')); ?></a></li>
    <li class="list-item"><a href="#"><?php echo e($blog_post->getTranslation('title',$user_select_lang_slug)); ?></a></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-meta-data'); ?>
    <?php echo render_site_title($blog_post->getTranslation('title',$user_select_lang_slug)); ?>

    <?php echo render_page_meta_data($blog_post); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>

    <style>
        .header {
            position: fixed;
            top: 0;
            z-index: 1;
            width: 100%;
            background-color: #f1f1f1;
        }

        .progress-container {
            width: 100%;
            height: 4px;
            background: #ccc;
        }

        .progress-bar {
            height: 4px;
            background: var(--main-color-one);
            width: 0%;
        }

    </style>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<div class="blog-details-area-wrapper" data-padding-top="100" data-padding-bottom="100">
       <div class="header">
        <div class="progress-container">
            <div class="progress-bar" id="myBar"></div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <?php if($blog_post->visibility == 'public' ): ?>
            <div class="col-lg-8">
                <div class="blog-details-inner-area">
                    <?php echo $__env->make('frontend.pages.blog-single-one-portions.image-and-gallery', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php echo $__env->make('frontend.pages.blog-single-one-portions.title-others', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php echo $__env->make('frontend.pages.blog-single-one-portions.related-blogs', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php echo $__env->make('frontend.pages.blog-single-one-portions.comment-area', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>
            <?php elseif($blog_post->visibility == 'logged_user' ): ?>

                 <?php if(auth()->guard('web')->check()): ?>
                    <div class="col-lg-8">
                        <div class="blog-details-inner-area">
                            <?php echo $__env->make('frontend.pages.blog-single-one-portions.image-and-gallery', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php echo $__env->make('frontend.pages.blog-single-one-portions.title-others', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php echo $__env->make('frontend.pages.blog-single-one-portions.related-blogs', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php echo $__env->make('frontend.pages.blog-single-one-portions.comment-area', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                    </div>

                  <?php else: ?>
                    <div class="col-xl-8">
                        <div class="alert alert-warning">
                            <h3><?php echo e(__('Login to see the blog details')); ?></h3>
                        </div>
                    </div>
                 <?php endif; ?>

            <?php elseif($blog_post->visibility == 'password' && $blog_post->password != $session_user_given_password_get ): ?>
                <div class="col-xl-8">
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.msg.error','data' => []]); ?>
<?php $component->withName('msg.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.msg.success','data' => []]); ?>
<?php $component->withName('msg.success'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                    <form action="<?php echo e(route('frontend.user.blog.password')); ?>" method="get">
                        <label for=""><?php echo e(__('Please Provide Blog Password to see this blog details (If you have no password then contact to the admin..!)')); ?></label>
                        <input class="form-control" type="password" name="user_blog_password" style="height: 50px">
                        <input type="hidden" name="original_password" value="<?php echo e($blog_post->password); ?>">
                        <input type="hidden" name="password_form_id" value="<?php echo e($blog_post->id); ?>">

                        <button class="btn btn-primary btn-md mt-3" type="submit"><?php echo e(__('Submit')); ?></button>
                    </form>
                </div>

            <?php elseif($blog_post->visibility == 'password' && (!is_null($blog_post->password) == $session_user_given_password_get)): ?>

                <div class="col-lg-8">
                    <div class="blog-details-inner-area">
                        <?php echo $__env->make('frontend.pages.blog-single-one-portions.image-and-gallery', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('frontend.pages.blog-single-one-portions.title-others', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('frontend.pages.blog-single-one-portions.related-blogs', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('frontend.pages.blog-single-one-portions.comment-area', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>

            <?php else: ?>
                <div class="col-xl-8">
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.msg.error','data' => []]); ?>
<?php $component->withName('msg.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.msg.success','data' => []]); ?>
<?php $component->withName('msg.success'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                    <form action="<?php echo e(route('frontend.user.blog.password')); ?>" method="get">
                        <label for=""><?php echo e(__('Please Provide Blog Password to see this blog details (If you have no password then contact to the admin..!)')); ?></label>
                        <input class="form-control" type="password" name="user_blog_password" style="height: 50px">
                        <input type="hidden" name="original_password" value="<?php echo e($blog_post->password); ?>">
                        <input type="hidden" name="password_form_id" value="<?php echo e($blog_post->id); ?>">

                        <button class="btn btn-primary btn-md mt-3" type="submit"><?php echo e(__('Submit')); ?></button>
                    </form>
                </div>
            <?php endif; ?>

            <div class="col-sm-7 col-md-6 col-lg-4">
                <div class="widget-area-wrapper">
                    <?php echo render_frontend_sidebar('details_page_sidebar',['column' => false]); ?>

                </div>
            </div>

        </div>
    </div>
</div>




<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        (function($){
            "use strict";

            $(document).ready(function(){
                //Blog Comment Insert
                $(document).on('click', '#submitComment', function (e) {
                    e.preventDefault();
                    var erContainer = $(".error-message");
                    var el = $(this);
                    var form = $('#blog-comment-form');
                    var user_id = $('#user_id').val();
                    var blog_id = $('#blog_id').val();
                    var commented_by = $('#commented_by').val();
                    var comment_content = $('#comment_content').val();
                    let comment_id = $('#blog-comment-form input[name=comment_id]').val();
                    el.text('<?php echo e(__('Submitting')); ?>...');

                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: {
                            _token: "<?php echo e(csrf_token()); ?>",
                            user_id: user_id,
                            blog_id: blog_id,
                            commented_by: commented_by,
                            comment_id: comment_id,
                            comment_content: comment_content,
                        },
                        success: function (data){
                            $('#comment_content').val('');
                            // erContainer.html('<div class="alert alert- '+data.msg+'"></div>');
                            load_comment_data('<?php echo e($blog_post->id); ?>');
                            $('#blog-comment-form input[name=comment_id]').val('');
                             location.reload();
                        },
                        error: function (data) {
                            var errors = data.responseJSON;
                            console.log(errors)
                            erContainer.html('<div class="alert alert-danger"></div>');
                            $.each(errors.errors, function (index, value) {
                                erContainer.find('.alert.alert-danger').append('<p>' + value + '</p>');
                            });
                            el.text('<?php echo e(__('Comment')); ?>');
                        },

                    });
                });

                //Blog Replay
                $(document).on('click', '.btn-replay', function (e) {
                    e.preventDefault();
                    $(this).hide();
                    let comment_id = $(this).data('comment_id');
                    let parent_name = $(this).parent().parent().find('.title').data('parent_name');

                    $('#blog-comment-form input[name=comment_id]').val(comment_id);

                    $('#comment_content').attr('placeholder','Replying to '+ parent_name + '..');

                });

                function load_comment_data(id) {
                    var commentData = $('#comment_data');
                    var items = commentData.attr('data-items');
                    $.ajax({
                        url: "<?php echo e(route('frontend.load.blog.comment.data')); ?>",
                        method: "POST",
                        data: {id: id, _token: "<?php echo e(csrf_token()); ?>", items: items},
                        success: function (data) {

                            commentData.attr('data-items',parseInt(items) + 5);

                            $('#comment_data').append(data.markup);
                            $('#load_more_comment_button').text('<?php echo e(__('Load More')); ?>');


                            if (data.blogComments.length === 0) {
                                $('#load_more_comment_button').text('<?php echo e(__('No Comment Found')); ?>');
                            }

                        }
                    })
                }

                $(document).on('click', '#load_more_comment_button', function () {
                    $(this).text('<?php echo e(__('Loading...')); ?>');
                    load_comment_data('<?php echo e($blog_post->id); ?>');

                });

            });
        })(jQuery);
    </script>
    
        <script>
        // When the user scrolls the page, execute myFunction
        window.onscroll = function() {myFunction()};
        function myFunction() {
            var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            var scrolled = (winScroll / height) * 100;
            document.getElementById("myBar").style.width = scrolled + "%";
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('frontend.frontend-page-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sohan/public_html/katerio/@core/Modules/Blog/Resources/views/frontend/blog/blog-single-variant/details-01.blade.php ENDPATH**/ ?>