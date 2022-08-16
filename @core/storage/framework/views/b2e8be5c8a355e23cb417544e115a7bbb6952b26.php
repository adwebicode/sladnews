<?php
    if(!isset($page_post)){
        return;
    }
?>

<?php if($page_post->layout === 'normal_layout' || $page_post->layout === 'home_page_layout' || $page_post->layout === 'home_page_layout_two' || $page_post->layout === 'home_page_layout_three'): ?>
<?php echo \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page',$page_post->id); ?>

<?php endif; ?>
<?php if($page_post->layout === 'home_page_layout'): ?>
    <div class="parent-area padding-top-70">
        <div class="container <?php echo e($page_post->page_class); ?>">
            <div class="row">
                <div class="col-lg-8">
                    <?php echo \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_with_sidebar',$page_post->id); ?>

                </div>

                <div class="col-sm-7 col-md-6 col-lg-4">
                    <div class="single-sidebar-item responsive-margin <?php if(get_static_option('site_frontend_dark_mode') === 'on'): ?>   dark-version  <?php endif; ?>">
                        <?php echo render_frontend_sidebar($page_post->sidebar_layout,['column' => false]); ?>

                    </div>
                </div>

            </div>
        </div>
    </div>
<?php endif; ?>


<?php if($page_post->layout === 'home_page_layout_two'): ?>
    <div class="recent-stories-area-wrapper index-01" data-padding-top="90" >
            <div class="container custom-container-01">
                <div class="row">
                    <div class="col-xl-8">
                        <?php echo \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_with_sidebar',$page_post->id); ?>

                    </div>

                    <div class="col-sm-8 col-md-6 col-lg-5 col-xl-4">
                        <div class="widget-area-wrapper style-<?php echo e($page_post->widget_style); ?>">
                            <?php echo render_frontend_sidebar($page_post->sidebar_layout,['column' => false]); ?>

                        </div>
                    </div>

                </div>
            </div>
        <div class="container-fluid p-0 <?php echo e($page_post->page_class); ?>">
            <div class="col-lg-12">
                <?php echo \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_with_sidebar_two',$page_post->id); ?>

            </div>
        </div>
    </div>
<?php endif; ?>


<?php if($page_post->layout === 'home_page_layout_two'): ?>
    <div class="parent-area">
        <div class="container custom-container-01">
            <div class="row">
                <div class="col-xl-8">
                    <?php echo \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_with_sidebar_three',$page_post->id); ?>

                </div>

                <div class="col-sm-10 col-md-6 col-lg-5 col-xl-4" data-padding-bottom="100">
                    <div class="widget-area-wrapper style-<?php echo e($page_post->widget_style); ?>">
                        <?php echo render_frontend_sidebar($page_post->sidebar_layout_two,['column' => false]); ?>

                    </div>
                </div>

            </div>
        </div>
        <div class="container-fluid p-0 <?php echo e($page_post->page_class); ?>">
            <div class="col-lg-12">
                <?php echo \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_without_sidebar_two',$page_post->id); ?>

            </div>
        </div>
    </div>
<?php endif; ?>



<?php if($page_post->layout === 'home_page_layout_three'): ?>
    <div class="<?php echo e($page_post->page_class); ?>">
    <div class="recent-stories-area-wrapper index-04" data-padding-top="0" data-padding-bottom="100">
        <div class="container custom-container-01">
            <div class="row">
                <div class="col-xl-9">
                    <?php echo \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_with_sidebar',$page_post->id); ?>

                </div>

                <div class="col-sm-7 col-md-6 col-lg-6 col-xl-3">
                    <div class="widget-area-wrapper" data-padding-top="100" >
                        <?php echo render_frontend_sidebar($page_post->sidebar_layout,['column' => false]); ?>

                    </div>
                </div>

            </div>
        </div>
        <div class="container-fluid custom-container-01" data-padding-bottom="0">
            <div class="col-lg-12">
                <?php echo \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_with_sidebar_four',$page_post->id); ?>

            </div>
        </div>
    </div>
</div>
<?php endif; ?>


<?php if($page_post->layout === 'home_page_layout_three'): ?>
    <div class="mixed-area-wrapper index-04">
        <div class="container custom-container-01 d-none">
            <div class="row">
                <div class="col-lg-12 col-xl-9">
                    <?php echo \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_with_sidebar_five',$page_post->id); ?>

                </div>

                <div class="col-sm-7 col-md-6 col-lg-6 col-xl-3" data-padding-bottom="80" data-padding-top="50">
                    <div class="widget-area-wrapper">
                        <?php echo render_frontend_sidebar($page_post->sidebar_layout_two,['column' => false]); ?>

                    </div>
                </div>

            </div>
        </div>
        <div class="container-fluid custom-container-01" data-padding-bottom="0">
            <div class="col-lg-12">
                <?php echo \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_without_sidebar_six',$page_post->id); ?>

            </div>
        </div>
<?php endif; ?>


<?php if($page_post->layout === 'sidebar_layout'): ?>
    <div class="<?php echo e($page_post->page_class); ?>" data-padding-top="100" data-padding-bottom="100">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <?php echo \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_with_sidebar',$page_post->id); ?>

                </div>
                <div class="col-sm-7 col-md-6 col-lg-4">
                    <div class="widget-area-wrapper">
                        <?php echo render_frontend_sidebar($page_post->sidebar_layout,['column' => false]); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>


<?php /**PATH /home/xgenxchi/public_html/laravel/katerio/beta/@core/resources/views/frontend/partials/pages-portion/dynamic-page-builder-part.blade.php ENDPATH**/ ?>