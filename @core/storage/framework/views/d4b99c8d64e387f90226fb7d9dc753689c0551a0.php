<section class="dynamic-page-content-area" data-padding-top="100" data-padding-bottom="70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php if(!auth()->guard('web')->check() && $page_post->visibility === 'all'): ?>
                    <div class="dynamic-page-content-wrap">
                        <?php echo $page_post->getTranslation('page_content',$user_select_lang_slug); ?>

                    </div>
                <?php elseif(auth()->guard('web')->check()): ?>
                    <div class="dynamic-page-content-wrap">
                        <?php echo $page_post->getTranslation('page_content',$user_select_lang_slug); ?>

                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <p><a class="text-primary" href="<?php echo e(route('user.login')); ?>"><?php echo e(__('Login')); ?></a> <?php echo e(__(' to see this page')); ?> </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section><?php /**PATH /Users/xgenious/Desktop/xgenious/localhost/katerio-last-update/@core/resources/views/frontend/partials/dynamic-content.blade.php ENDPATH**/ ?>