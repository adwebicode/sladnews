<?php if(!empty(get_static_option('leftbar_show_hide'))): ?>

    <div class="hamburger-c w3-sidebar w3-bar-block w3-border-right" id="mySidebar">
        <button onclick="w3_close()" class="close-h w3-bar-item w3-large"> &times;</button>
        <div class="main-content">
            <div class="logo-wrapper">
                <?php if(get_static_option('site_frontend_dark_mode') == 'on'): ?>
                <a href="<?php echo e(url('/')); ?>">
                    <?php echo render_image_markup_by_attachment_id(get_static_option('site_white_logo')); ?>

                </a>
                <?php else: ?>
                    <a href="<?php echo e(url('/')); ?>">
                        <?php echo render_image_markup_by_attachment_id(get_static_option('site_logo')); ?>

                    </a>
               <?php endif; ?>
            </div>

            <div class="widget-area-wrapper">
                <div class="widget">
                    <div class="recent-post style-01">
                        <h4 class="widget-title style-01"><?php echo e(get_static_option('leftbar_blog_'.get_user_lang().'_title')); ?></h4>
                        <ul class="news-headline-list style-01">
                            <?php $__currentLoopData = $blogs_for_leftbar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="news-heading-item">
                                <h3 class="title">
                                    <a href="<?php echo e(route('frontend.blog.single',$data->slug)); ?>"><?php echo e($data->title ?? ''); ?></a>
                                </h3>
                                <div class="post-meta">
                                    <ul class="post-meta-list style-02">
                                        <?php if($data->created_by == 'user'): ?>
                                            <?php $user = $data->user; ?>
                                        <?php else: ?>
                                            <?php $user = $data->admin; ?>
                                        <?php endif; ?>
                                        <li class="post-meta-item">
                                            <a <?php if(!empty($user->id)): ?>  href="<?php echo e(route('frontend.user.created.blog', ['user'=> $data->created_by, 'id'=>$user->id])); ?>" <?php endif; ?>>
                                                <span class="text author"> <?php echo e($data->author ?? __('Anonymous')); ?></span>
                                            </a>
                                        </li>
                                        <li class="post-meta-item date">
                                            <span class="text"><?php echo e(date('d M, Y',strtotime($data->created_at))); ?></span>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>

                <div class="widget">
                    <div class="tag style-03">
                        <h4 class="widget-title style-01"><?php echo get_static_option('leftbar_tag_'.$user_select_lang_slug.'_title'); ?></h4>
                        <ul class="tag-list">
                            <?php  $colors = ['color-a','color-b','color-c','color-d','color-e','color-f','color-g']; ?>
                            <?php $__currentLoopData = $tags_for_leftbar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="single-tag-item">
                                <a href="<?php echo e(route('frontend.blog.tags.page', ['any' => $tag->name])); ?>" class="<?php echo e($colors[$key % count($colors)]); ?>"><?php echo e($tag->name); ?></a>
                            </li>
                             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>

                <div class="widget">
                    <div class="social-link style-04 v-02">
                        <h4 class="widget-title style-01"><?php echo get_static_option('leftbar_social_'.$user_select_lang_slug.'_title'); ?></h4>
                        <ul class="widget-social-link-list">
                            <?php  $Socialcolors = ['facebook','twitter','youtube','instagram','pinterest','linkedin']; ?>
                            <?php $__currentLoopData = $social_icons_for_leftbar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>  $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="single-item">
                                <a href="<?php echo e($social->details); ?>" class="left-content">
                                    <span class="icon <?php echo e($Socialcolors[$key % count($Socialcolors)]); ?>">
                                        <i class="<?php echo e($social->icon); ?>"></i>
                                    </span>
                                </a>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?><?php /**PATH /home/xgenxchi/public_html/laravel/katerio/beta/@core/resources/views/frontend/partials/left-bar.blade.php ENDPATH**/ ?>