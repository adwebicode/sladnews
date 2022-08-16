<?php $title = get_static_option('blog_single_page_login_title_'.$user_select_lang_slug.'_text'); ?>
<div class="login-form">
    <p class="mb-4"><?php echo e($title); ?></p>


    <div class="login-form">
        <form action="<?php echo e(route('user.login')); ?>" method="post" enctype="multipart/form-data" class="account-form" id="login_form_order_page">
            <?php echo csrf_field(); ?>
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
            <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="<?php echo e(__('Username')); ?>">
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="<?php echo e(__('Password')); ?>">
            </div>
            <div class="form-group btn-wrapper">
                <button type="submit" id="login_btn" class="submit-btn btn-default"><?php echo e(get_static_option('blog_single_page_login_button_'.$user_select_lang_slug.'_text')); ?></button>
            </div>

            <div class="row mb-4 rmber-area ajax-partial-login-form">
                    <div class="col-6">
                        <div class="custom-control custom-checkbox mr-sm-2 text-left">
                            <input type="checkbox" name="remember" class="custom-control-input" id="remember">
                            <label class="custom-control-label int" for="remember"><?php echo e(__('Remember Me')); ?></label>
                        </div>
                    </div>
                    <div class="col-6 text-right">
                        <a class="d-block int" href="<?php echo e(route('user.register')); ?>"><?php echo e(__('Create New account?')); ?></a>
                        <a href="<?php echo e(route('user.forget.password')); ?>" class="int"><?php echo e(__('Forgot Password?')); ?></a>
                    </div>

                <div class="col-lg-12">
                    <div class="social-login-wrap">
                        <?php if(get_static_option('enable_facebook_login')): ?>
                            <a href="<?php echo e(route('login.facebook.redirect')); ?>" class="facebook"><i class="lab la-facebook-f"></i> <?php echo e(__('Login With Facebook')); ?></a>
                        <?php endif; ?>
                        <?php if(get_static_option('enable_google_login')): ?>
                            <a href="<?php echo e(route('login.google.redirect')); ?>" class="google"><i class="lab la-google"></i> <?php echo e(__('Login With Google')); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div><?php /**PATH /home/sohan/public_html/katerio/@core/resources/views/frontend/partials/ajax-user-login-markup.blade.php ENDPATH**/ ?>