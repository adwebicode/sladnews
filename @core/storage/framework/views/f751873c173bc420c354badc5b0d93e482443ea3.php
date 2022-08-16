<?php echo $__env->make('frontend.partials.support', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- nav area start -->
<div class="nav-main-wrap-for-custom-style-02-v-02">
    <nav class="navbar navbar-area navbar-expand-lg has-topbar nav-style-01 custom-style-02 v-02 dark-bg-01">
        <div class="container nav-container custom-container-01">
            <div class="responsive-mobile-menu">
                <div class="logo-wrapper">
                    <a href="<?php echo e(url('/')); ?>" class="logo">
                        <?php echo render_image_markup_by_attachment_id(get_static_option('site_white_logo')); ?>

                    </a>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bizcoxx_main_menu"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="bizcoxx_main_menu">
                <ul class="navbar-nav">
                    <?php echo render_frontend_menu($primary_menu); ?>

                </ul>
            </div>
            <div class="nav-right-content v-02">
                <div class="search position-relative">
                    <div class="search-open">
                        <i class="las la-search icon"></i>
                    </div>

                    <div class="search-bar">
                        <form class="menu-search-form" action="<?php echo e(route('frontend.blog.search')); ?>">
                            <div class="search-close"> <i class="las la-times"></i> </div>
                            <input class="item-search" name="search" id="search" type="text" placeholder="Search Here.....">
                            <button type="submit"> <?php echo e(__('Search')); ?></button>

                            <?php echo $__env->make('frontend.partials.pages-portion.navbars.autocomplete-markup', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </form>
                    </div>
                </div>
                <!-- hamburger area start -->
                <div class="hamburger-menu-wrapper right-side">
                    <button class="w3-button w3-teal w3-xlarge" onclick="w3_open()">☰</button>
                </div>
                <!-- hamburger area end -->
            </div>
        </div>
    </nav>
</div>
<!-- navbar area end -->
<?php /**PATH /home/xgenxchi/public_html/laravel/katerio/beta/@core/resources/views/frontend/partials/pages-portion/navbars/navbar-03.blade.php ENDPATH**/ ?>