
<?php $__env->startSection('page-meta-data'); ?>
    <meta name="description" content="<?php echo e($page_post->meta_description); ?>">
    <meta name="tags" content="<?php echo e($page_post->meta_tags); ?>">
    <meta name="og:title" content="<?php echo e($page_post->og_meta_title); ?>"/>
    <meta name="og:description" content="<?php echo e($page_post->og_meta_description); ?>"/>
    <?php echo render_og_meta_image_by_attachment_id($page_post->og_meta_image); ?>

    <?php echo render_site_title($page_post->meta_title ?? $page_post->title); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-title'); ?>
    <?php echo $page_post->title; ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('nav-style'); ?>
    <?php echo e($page_post->navbar_variant); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php if($page_post->page_builder_status === 'on'): ?>

        <?php if(!auth()->guard('web')->check() && $page_post->visibility === 'all'): ?>
            <?php echo $__env->make('frontend.partials.pages-portion.dynamic-page-builder-part',['page_post' => $page_post], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php elseif(auth()->guard('web')->check()): ?>
            <?php echo $__env->make('frontend.partials.pages-portion.dynamic-page-builder-part',['page_post' => $page_post], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php else: ?>
           <section class="padding-top-100 padding-bottom-100">
               <div class="container">
                   <div class="row">
                       <div class="col-lg-12">
                           <div class="alert alert-warning">
                               <p><a class="text-primary" href="<?php echo e(route('user.login')); ?>"><?php echo e(__('Login')); ?></a> <?php echo e(__(' to see this page')); ?> </p>
                           </div>
                       </div>
                   </div>
               </div>
           </section>
        <?php endif; ?>
    <?php else: ?>
        <?php echo $__env->make('frontend.partials.dynamic-content', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.frontend-page-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/xgenxchi/public_html/laravel/katerio/beta/@core/resources/views/frontend/pages/dynamic-single.blade.php ENDPATH**/ ?>