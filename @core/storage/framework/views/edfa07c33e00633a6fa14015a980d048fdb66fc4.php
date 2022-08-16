<?php $user_select_lang_slug = isset($user_select_lang_slug ) ?: get_user_lang(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo e(get_static_option('site_'.get_user_lang().'_title')); ?> - <?php echo e(get_static_option('site_'.get_user_lang().'_tag_line')); ?></title>
           <?php echo render_site_meta(); ?>


    <!-- favicon -->
    <?php echo render_favicon_by_id(get_static_option('site_favicon')); ?>

    <!-- bootstrap -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/main-style.css')); ?>">
     <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/helpers.css')); ?>">

</head>

<body>

<div class="error-area-wrapper" data-padding-top="100" data-padding-bottom="100">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="img-box">
                    <h4 class="title"><?php echo e(get_static_option('error_404_page_'.get_user_lang().'_subtitle')); ?></h4>
                  <?php echo render_image_markup_by_attachment_id(get_static_option('error_image'),'','',false); ?>

                </div>
                <div class="content">
                    <div class="btn-wrapper">
                        <a href="<?php echo e(route('homepage')); ?>" class="btn-default"><?php echo e(get_static_option('error_404_page_'.get_user_lang().'_button_text')); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html><?php /**PATH /home/xgenxchi/public_html/laravel/katerio/beta/@core/resources/views/frontend/pages/404.blade.php ENDPATH**/ ?>