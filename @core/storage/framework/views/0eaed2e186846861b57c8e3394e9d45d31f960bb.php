
<ul class="comment-list">
 <?php $__currentLoopData = $blogComments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $author_image = render_image_markup_by_attachment_id(optional($data->user)->image);
        $avatar_image = render_image_markup_by_attachment_id(get_static_option('single_blog_page_comment_avatar_image'));
        $commented_user_image = $author_image ? $author_image : $avatar_image;
    ?>
    <li>
        <div class="single-comment-wrap">
            <div class="thumb">
                <?php echo $commented_user_image; ?>

            </div>
            <div class="content">
                <div class="content-top">
                    <div class="left">
                        <h4 class="title" data-parent_name="<?php echo e(optional($data->user)->name); ?>"><?php echo e(optional($data->user)->name ?? ''); ?></h4>
                        <ul class="post-meta">
                            <li class="meta-item comment-date">
                                <i class="lar la-calendar icon"></i>
                               <?php echo e(date('d F Y', strtotime($data->created_at ?? ''))); ?>

                            </li>
                        </ul>
                    </div>
                </div>
                <p class="comment common-para"><?php echo $data->comment_content ?? ''; ?>

                </p>

               <?php if(auth('web')->check() && auth('web')->id() != $data->user_id): ?>
                <div class="reply">
                     <a href="#" data-comment_id="<?php echo e($data->id); ?>" class="reply-btn btn-replay"><i class="las la-reply icon"></i><span class="text"><?php echo e(__('Reply')); ?></span></a>
                </div>
              <?php endif; ?>
            </div>
        </div>
    </li>

    <li class="has-children">
        <ul>
            <?php $__currentLoopData = $data->reply; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $repData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $child_author_image = render_image_markup_by_attachment_id(optional($repData->user)->image);
                    $avatar_image = render_image_markup_by_attachment_id(get_static_option('single_blog_page_comment_avatar_image'));
                    $commented_child_author_image = $child_author_image ? $child_author_image : $avatar_image;
                ?>
            <li>
                <div class="single-comment-wrap">
                    <div class="thumb">
                        <?php echo $commented_child_author_image; ?>

                    </div>
                    <div class="content">
                        <div class="content-top">
                            <div class="left">
                                <h4 class="title"><?php echo e(optional($repData->user)->name); ?></h4>
                                <ul class="post-meta">
                                    <li class="meta-item">
                                        <i class="lar la-calendar icon"></i>
                                        <?php echo e(date('d F Y', strtotime($repData->created_at ?? ''))); ?>

                                    </li>
                                </ul>
                            </div>
                        </div>
                        <p class="comment common-para"><?php echo $repData->comment_content ?? ''; ?></p>
                    </div>
                </div>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </li>
 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>

<?php /**PATH /home/sohan/public_html/katerio/@core/resources/views/frontend/partials/pages-portion/comment-show-data.blade.php ENDPATH**/ ?>