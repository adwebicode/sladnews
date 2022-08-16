<h3 class="main-title">
    <a><?php echo e(Str::words($blog_post->title,8)); ?></a>
</h3>

<div class="post-meta-main">
    <div class="post-meta">
        <ul class="post-meta-list">
            <li class="post-meta-item">
                <a href="<?php echo e($created_by_url); ?>">
                    <?php echo $created_by_image; ?>

                    <span class="text"><?php echo e($created_by); ?></span>
                </a>
            </li>
            <li class="post-meta-item date">
                <i class="lar la-clock icon"></i>
                <span class="text"><?php echo e($date); ?></span>
            </li>
            <li class="post-meta-item">
                <a href="#">
                    <i class="lar la-comments icon"></i>
                    <span class="text"><?php echo e($blogCommentCount); ?></span>
                </a>
            </li>
               <li class="post-meta-item">
                     <i class="lab la-readme icon"></i>
                    <span class="text"><?php echo e($read_duration. " min read"); ?></span>
            </li>
            
            <li class="post-meta-item date">
                <i class="lar la-clock icon"></i>
                <span class="text"><?php echo e(__('Last Updated At :')); ?> <?php echo e($updated_at); ?></span>
            </li>
            
        </ul>
    </div>
</div>

<div class="details-one-page-para">
    <p class="info info-01"><?php echo $blog_post->blog_content ?? ''; ?> </p>
</div>

<?php
    $tags_arr = json_decode($blog_post->tag_id);
    $all_tags = is_array($tags_arr) ? implode(",", $tags_arr) : "";
?>



    <div class="tag-and-social-link">
        <?php if(!empty($tags_arr) && count($tags_arr) > 0): ?>
        <div class="tag-wrap">
            <ul>
                <?php if(!empty($tags_arr[0])): ?><li class="name"><?php echo e(__('Tags :')); ?></li><?php endif; ?>
                <?php $__currentLoopData = $tags_arr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php if(!empty($i)): ?>
                        <li><a class="tag-btn" href="<?php echo e(route('frontend.blog.tags.page', [ 'any'=> $i ?? 'u'])); ?>"><?php echo e($i); ?></a></li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>
        <div class="social-link-wrap">
            <div class="social-icon">
                <ul class="social-link-list">
                    <li class="name"><?php echo e(__('share :')); ?></li>
                     <?php echo single_post_share(route('frontend.blog.single',['id' => $blog_post->id, 'slug' => Str::slug($blog_post->title,'-')]),$blog_post->title,$blog_post->image); ?>

                </ul>
            </div>
        </div>
    </div>
<?php /**PATH /home/sohan/public_html/katerio/@core/resources/views/frontend/pages/blog-single-one-portions/title-others.blade.php ENDPATH**/ ?>