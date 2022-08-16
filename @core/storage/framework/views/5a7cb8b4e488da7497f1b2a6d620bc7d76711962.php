<?php if(count($all_related_blog) > 0): ?>
    <div class="related-post-area" data-padding-top="100">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-style-03">
                    <h3 class="title"> <?php echo e(__('Related Post')); ?> </h3>
                    <div class="appendarow"></div>
                </div>
            </div>

            <?php $__currentLoopData = $all_related_blog; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-6">
                    <div class="blog-grid-style-03 small-02">
                        <div class="img-box">
                            <?php echo render_image_markup_by_attachment_id($data->image); ?>

                        </div>
                        <div class="content">
                            <div class="post-meta">
                                <ul class="post-meta-list style-02">
                                    <?php

                                        if ($data->created_by === 'user') {
                                          $user_id = $data->user_id;
                                          } else {
                                              $user_id = $data->admin_id;
                                          }

                                        $created_by_url = !is_null($user_id) ?  route('frontend.user.created.blog', ['user' => $data->created_by, 'id' => $user_id]) : route('frontend.blog.single',$data->slug);
                                    ?>
                                    <li class="post-meta-item">
                                        <a href="<?php echo e($created_by_url); ?>">
                                            <span class="text author"><?php echo e($data->author ?? ''); ?></span>
                                        </a>
                                    </li>
                                    <li class="post-meta-item date">
                                        <span class="text"><?php echo e(date('D m, Y',strtotime($data->created_at))); ?></span>
                                    </li>
                                </ul>
                            </div>
                            <h4 class="title font-size-24 font-weight-600">
                                <a href="<?php echo e(route('frontend.blog.single',$data->slug)); ?>"><?php echo e(Str::words($data->title,10) ?? ''); ?></a>
                            </h4>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH /home/sohan/public_html/katerio/@core/resources/views/frontend/pages/blog-single-one-portions/related-blogs.blade.php ENDPATH**/ ?>