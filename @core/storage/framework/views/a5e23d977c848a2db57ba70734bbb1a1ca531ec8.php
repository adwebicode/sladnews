<?php echo $__env->make('frontend.partials.left-bar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<div class="topbar-area">
    <div class="container custom-container-01">
        <div class="row">
            <div class="col-lg-12">
                <div class="topbar-inner">
                    <div class="left-content">
                        <div class="topbar-item">
                            <div class="extra-menu">
                                <ul class="extra-menu-list">
                                    <?php $__currentLoopData = $all_topbar_infos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="link-item">
                                        <a href="<?php echo e($data->url); ?>">
                                          <?php echo e($data->title); ?>

                                        </a>
                                    </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="right-content">
                        <div class="topbar-item">
                            <div class="social-icon">
                                <ul class="social-link-list">
                                    <?php $__currentLoopData = $all_social_icons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="link-item">
                                            <a href="<?php echo e($data->url); ?>" class="facebook">
                                                <i class="<?php echo e($data->icon); ?> icon"></i>
                                            </a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </ul>
                            </div>
                        </div>

                        <div class="topbar-item">
                             <?php if(auth()->check()): ?>
                                  <?php
                                    $route = auth()->guest() == 'admin' ? route('admin.home') : route('user.home');
                                 ?>

                                    <a class="topbar-login-btn" href="<?php echo e($route); ?>"><?php echo e(__('Dashboard')); ?></a>
                                    <span><?php echo e(__('|')); ?></span>
                                    <a class="topbar-login-btn" href="<?php echo e(route('frontend.user.logout')); ?>"><?php echo e(__('Logout')); ?></a>

                                    <form id="userlogout-form" action="<?php echo e(route('user.logout')); ?>" method="POST" style="display: none;">
                                        <?php echo csrf_field(); ?>
                                        <input type="submit" value="aa" id="userlogout-form" class="d-none">
                                    </form>

                              <?php else: ?>


                                  <?php if(!empty(get_static_option('login_show_hide'))): ?>
                                    <a class="topbar-login-btn" href="<?php echo e(route('user.login')); ?>"><?php echo e(__('Login')); ?></a>
                                    <span><?php echo e(__('|')); ?></span>
                                   <?php endif; ?>

                                    <?php if(!empty(get_static_option('register_show_hide'))): ?>
                                    <a class="topbar-login-btn" href="<?php echo e(route('user.register')); ?>"><?php echo e(__('Register')); ?></a>
                                    <?php endif; ?>

                            <?php endif; ?>
                            </div>

                            <?php if(!empty(get_static_option('language_select_option'))): ?>
                                <div class="topbar-item">
                                    <select class="lang" id="langchange">
                                        <?php $__currentLoopData = $all_language; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $lang_name = explode('(',$lang->name);
                                                $data = array_shift($lang_name);
                                            ?>
                                            <option <?php if(get_user_lang() == $lang->slug): ?> selected <?php endif; ?> value="<?php echo e($lang->slug); ?>"><?php echo e($data); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            <?php endif; ?>

                        <?php if(!empty(get_static_option('dark_mode_show_hide'))): ?>
                            <?php
                                $condition = get_static_option('site_frontend_dark_mode') == 'on' ? 'dark night-symbol' : 'day-symbol';
                            ?>
                        <div class="topbar-item">
                            <label class="switch yes">
                                <input id="frontend_darkmode" type="checkbox" data-mode=<?php echo e(get_static_option('site_frontend_dark_mode')); ?> <?php if(get_static_option('site_frontend_dark_mode') == 'on'): ?> checked <?php else: ?> <?php endif; ?>>
                                <span class="slider-color-mode onff <?php echo e($condition); ?>"></span>
                            </label>
                        </div>
                        <?php endif; ?>
                         </div>

                     </div>
                    </div>
                </div>
            </div>
    </div>
</div>
<?php /**PATH /home/xgenxchi/public_html/laravel/katerio/beta/@core/resources/views/frontend/partials/support.blade.php ENDPATH**/ ?>