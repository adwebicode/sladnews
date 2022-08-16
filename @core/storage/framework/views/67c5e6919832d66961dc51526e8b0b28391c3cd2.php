<?php $__env->startSection('site-title'); ?>
    <?php echo e(__('Cache Settings')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
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
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title"><?php echo e(__("Cache Settings")); ?></h4>
                        <form action="<?php echo e(route('admin.general.cache.settings')); ?>" method="POST" id="cache_settings_form" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="cache_type" id="cache_type" class="form-control">
                             <button class="btn btn-primary mt-4 pr-4 pl-4 clear-cache-submit-btn" id="view" data-value="view"><?php echo e(__('Clear View Cache')); ?></button><br>
                             <button class="btn btn-primary mt-4 pr-4 pl-4 clear-cache-submit-btn" id="route" data-value="route"><?php echo e(__('Clear Route Cache')); ?></button><br>
                             <button class="btn btn-primary mt-4 pr-4 pl-4 clear-cache-submit-btn" id="config" data-value="config"><?php echo e(__('Clear Configure Cache')); ?></button><br>
                             <button class="btn btn-primary mt-4 pr-4 pl-4 clear-cache-submit-btn" id="clear" data-value="cache"><?php echo e(__('Clear Cache')); ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    (function($){
        "use strict";
        $(document).ready(function(){
            $(document).on('click','.clear-cache-submit-btn',function(e){
                e.preventDefault();
                $(this).addClass("disabled")
                $(this).html('<i class="fas fa-spinner fa-spin mr-1"></i> <?php echo e(__("Cleaning Cache")); ?>');
                $('#cache_type').val($(this).data('value'));
                $('#cache_settings_form').trigger('submit');
            });
        });
    })(jQuery);
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.admin-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sohan/public_html/katerio/@core/resources/views/backend/general-settings/cache-settings.blade.php ENDPATH**/ ?>