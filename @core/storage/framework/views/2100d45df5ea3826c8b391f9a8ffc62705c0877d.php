<?php echo $__env->make('frontend.partials.support', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="supportbar-area-wrapper index-04">
    <div class="container custom-container-01">
        <div class="row">
            <div class="col-lg-3 col-xl-2">
                <div class="logo-wrapper">
                    <?php if(get_static_option('site_frontend_dark_mode') == 'on'): ?>
                    <a href="<?php echo e(url('/')); ?>">
                        <?php echo render_image_markup_by_attachment_id(get_static_option('site_white_logo')); ?>

                    </a>
                     <?php else: ?>
                        <a href="<?php echo e(url('/')); ?>">
                            <?php echo render_image_markup_by_attachment_id(get_static_option('site_logo_two')); ?>

                        </a>
                     <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-9 col-xl-10">
                <div class="content">
                    <div class="support-bar-search-box style-02">
                        <form action="<?php echo e(route('frontend.blog.search')); ?>">
                            <div class="form-group">
                                <input type="text" name="search" id="search" class="form-control" placeholder="Search">
                                <button type="submit" class="search-btn">
                                    <i class="las la-search icon"></i>
                                </button>
                            </div>

                                <?php echo $__env->make('frontend.partials.pages-portion.navbars.autocomplete-markup', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        </form>
                    </div>
                    <div class="add-box">
                        <a href="<?php echo e(get_static_option('home_page_four_banner_url')); ?>">
                           <?php echo render_image_markup_by_attachment_id(get_static_option('home_page_four_banner')); ?>

                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="nav-main-wrap-for-custom-style-01-v-02">
    <nav class="navbar navbar-area navbar-expand-lg has-topbar nav-style-01 custom-style-01 dark-bg-01 v-02">
        <div class="container nav-container custom-container-01">
            <div class="responsive-mobile-menu">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bizcoxx_main_menu"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="bizcoxx_main_menu">
                <!-- hamburger area start -->
                <div class="hamburger-menu-wrapper left-side">
                    <button class="w3-button w3-teal w3-xlarge" onclick="w3_open()">☰</button>
                </div>
                <!-- hamburger area end -->

                <ul class="navbar-nav">
                    <?php echo render_frontend_menu($primary_menu); ?>

                </ul>
            </div>
            <div class="nav-right-content v-02">
                <?php
                    $date = \Illuminate\Support\Carbon::now()->format('l, d M Y');
                ?>
                <div class="date-and-time">
                    <span class="day"><?php echo e($date); ?></span>
                </div>
            </div>
        </div>
    </nav>
</div><?php /**PATH /home/xgenxchi/public_html/laravel/katerio/beta/@core/resources/views/frontend/partials/pages-portion/navbars/navbar-04.blade.php ENDPATH**/ ?>