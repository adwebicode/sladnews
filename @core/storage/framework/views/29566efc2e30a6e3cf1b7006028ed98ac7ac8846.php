<div class="main-img-box ">
    <?php if(!empty($blog_post->image_gallery)): ?>
        <div class="global-slick-init slick-space-adjust " data-infinite="true" data-slidesToShow="1"
             data-slidesToScroll="1" data-speed="500" data-cssEase="linear" data-arrows="false" data-dots="false"
             data-prevArrow='<div class="prev-arrow"><i class="las la-arrow-left"></i></div>'
             data-nextArrow='<div class="prev-arrow"><i class="las la-arrow-left"></i></div>'
             data-autoplaySpeed="2000"
             data-responsive='[{"breakpoint": 768,"settings": { "arrows": false,"centerMode": true,"centerPadding": "40px", "slidesToShow": 1}},{"breakpoint": 480, "settings": { "arrows": false, "centerMode": true, "centerPadding": "0px","slidesToShow": 1} }]'
        >
            <?php
                $images = explode("|",$blog_post->image_gallery);
                $video_url = $blog_post->video_url;

            ?>

            <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="single-gallery-image single-featured">
                    <div class="tag-box">
                        <?php echo render_image_markup_by_attachment_id($img,'','large'); ?>

                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

    <?php else: ?>

        <div class="img-bg lazy" <?php echo render_background_image_markup_by_attachment_id($blog_post->image); ?>></div>
    <?php endif; ?>

</div>

<div class="tag-box">
    <?php $__currentLoopData = $blog_post->category_id; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('frontend.blog.category',['id'=> $cat->id,'any'=> Str::slug($cat->title)])); ?>"
           class="category-style-01 v-02 <?php echo e($colors[$key % count($colors)]); ?>"><?php echo e($cat->getTranslation('title',$user_select_lang_slug) ?? __('Uncategorized')); ?></a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div><?php /**PATH /home/sohan/public_html/katerio/@core/resources/views/frontend/pages/blog-single-one-portions/image-and-gallery.blade.php ENDPATH**/ ?>