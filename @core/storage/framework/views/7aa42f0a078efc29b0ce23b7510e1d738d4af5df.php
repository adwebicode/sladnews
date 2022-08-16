<?php
    $id = $id ?? 'langchange';
    $name = $name ?? 'lang';
    $selected = $selected ?? '';
?>

<div class="form-group">
    <?php if(isset($label)): ?><label for="edit_language"><?php echo e(__('Languages')); ?></label> <?php endif; ?>
    <select name="<?php echo e($name); ?>" class="form-control lang-select" id="<?php echo e($id); ?>">
        <?php $__currentLoopData = App\Helpers\LanguageHelper::all_languages(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($lang->slug); ?>" <?php if($lang->slug === $selected): ?> selected <?php endif; ?> ><?php echo e($lang->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>
<?php /**PATH /home/xgenxchi/public_html/laravel/katerio/beta/@core/resources/views/components/lang/select.blade.php ENDPATH**/ ?>