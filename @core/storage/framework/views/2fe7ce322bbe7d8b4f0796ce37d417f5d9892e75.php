

<?php $__env->startSection('site-title'); ?>
    <?php echo e($author_info->name); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-title'); ?>

    <li class="list-item"><a href="#"><?php echo e(__('Author')); ?></a></li>
    <li class="list-item"><a href="#"><?php echo e($author_info->name); ?></a></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom-page-title'); ?>
    <?php echo e($author_info->name); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-meta-data'); ?>
    <?php echo render_site_meta(); ?>

    <?php echo render_site_title($author_info->name); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="author-profile-area-wrapper" data-padding-top="100" data-padding-bottom="90">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="author-profile">
                        <div class="row">
                            <div class="col-lg-4">
                                <?php
                                    $img = get_attachment_image_by_id($author_info->image);
                                ?>
                                <div class="img-box">
                                    <?php echo render_image_markup_by_attachment_id($author_info->image,'','grid') ?? ''; ?>

                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="content">
                                    <h4 class="title"><?php echo e($author_info->name); ?></h4>
                                    <p class="designation"><?php echo e($author_info->designation); ?></p>

                                    <p class="info"><?php echo $author_info->description; ?></p>


                                    <div class="widget-area-wrapper">
                                        <div class="widget">
                                            <div class="social-link style-04 v-02">
                                                <ul class="widget-social-link-list">
                                                    <li class="single-item">
                                                        <a href="<?php echo e($author_info->facebook_url); ?>" class="left-content">
                                                            <span class="icon facebook">
                                                                <i class="lab la-facebook-f"></i>
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="single-item">
                                                        <a href="<?php echo e($author_info->twitter_url); ?>" class="left-content">
                                                            <span class="icon twitter">
                                                                <i class="lab la-twitter"></i>
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="single-item">
                                                        <a href="<?php echo e($author_info->instagram_url); ?>" class="left-content">
                                                            <span class="icon youtube">
                                                                <i class="lab la-youtube"></i>
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="single-item">
                                                        <a href="<?php echo e($author_info->linkedin_url); ?>" class="left-content">
                                                            <span class="icon instagram">
                                                                <i class="lab la-instagram"></i>
                                                            </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="author-post-area-wrapper" data-padding-top="0" data-padding-bottom="100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="author-post-title"> <?php echo e(__('Author Post')); ?> </h4>
                </div>
            </div>

            <div class="row three-column">
                <?php if(count($all_blogs) < 1): ?>
                    <div class="col-md-12">
                         <span class="text-dark"><?php echo __('No post found related to : ').' '. '<span class="text-warning"> '.$author_info->name.' </span>'; ?></span>
                    </div>
               </div>
                <?php else: ?>

              <?php $__currentLoopData = $all_blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="blog-grid-style-01">
                            <div class="img-box">
                                <?php echo render_image_markup_by_attachment_id($data->image, '', 'grid'); ?>

                            </div>
                            <div class="content">
                                <div class="post-meta">
                                    <ul class="post-meta-list">
                                        <li class="post-meta-item">
                                            <?php $__currentLoopData = $data->category_id; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <a href="<?php echo e(route('frontend.blog.category',['id'=> $cat->id,'any'=> Str::slug($cat->title)])); ?>">  <i class="las la-tag icon"></i><span class="text"><?php echo e($cat->getTranslation('title',$user_select_lang_slug) ?? __('Uncategorized')); ?></span></a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </li>

                                        <li class="post-meta-item date">
                                            <i class="lar la-clock icon"></i>
                                            <span class="text"><?php echo e(date('d M Y',strtotime($data->created_at))); ?> </span>
                                        </li>
                                    </ul>
                                </div>
                                <h4 class="title">
                                    <a href="<?php echo e(route('frontend.blog.single',$data->slug)); ?>"><?php echo e(Str::words($data->getTranslation('title',$user_select_lang_slug) ?? '',10)); ?></a>
                                </h4>
                            </div>
                        </div>
                    </div>
               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>
            <div class="row">
                <div class="col-lg-12 text-center mt-5">
                    <div class="pagination-wrapper text-center" aria-label="Page navigation" data-padding-bottom="0">
                        <?php echo e($all_blogs->links()); ?>

                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.frontend-page-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/xgenxchi/public_html/laravel/katerio/beta/@core/Modules/Blog/Resources/views/frontend/blog/author-profile.blade.php ENDPATH**/ ?>