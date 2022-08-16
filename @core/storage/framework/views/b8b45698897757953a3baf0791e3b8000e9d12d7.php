<div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <a href="<?php echo e(route('admin.home')); ?>">
                <?php if(get_static_option('site_admin_dark_mode') == 'off'): ?>
                <?php echo render_image_markup_by_attachment_id(get_static_option('site_logo'),'','',false); ?>

                <?php else: ?>
                <?php echo render_image_markup_by_attachment_id(get_static_option('site_white_logo'),'','',false); ?>

                <?php endif; ?>
            </a>
        </div>
    </div>

    <div class="main-menu">
        <div class="menu-inner">
            <nav>
                <ul class="metismenu" id="menu">
                    <li class="<?php echo e(active_menu('admin-home')); ?>">
                        <a href="<?php echo e(route('admin.home')); ?>"
                           aria-expanded="true">
                            <i class="ti-dashboard"></i>
                            <span><?php echo app('translator')->get('dashboard'); ?></span>
                        </a>
                    </li>

                  <?php if(auth()->guard('admin')->user()->hasRole('Super Admin')): ?>
                    <li class="
                        <?php echo e(active_menu('admin-home/admin/new')); ?>

                    <?php echo e(active_menu('admin-home/admin/role')); ?>

                    <?php echo e(active_menu('admin-home/admin/all')); ?>">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-user"></i>
                            <span><?php echo e(__('Admin Role Manage')); ?></span></a>
                        <ul class="collapse">
                            <li class="<?php echo e(active_menu('admin-home/admin/all')); ?>"><a
                                        href="<?php echo e(route('admin.all.user')); ?>"><?php echo e(__('All Admin')); ?></a></li>
                            <li class="<?php echo e(active_menu('admin-home/admin/new')); ?>"><a
                                        href="<?php echo e(route('admin.new.user')); ?>"><?php echo e(__('Add New Admin')); ?></a></li>
                            <li class="<?php echo e(active_menu('admin-home/admin/role')); ?> "><a
                                        href="<?php echo e(route('admin.all.admin.role')); ?>"><?php echo e(__('All Admin Role')); ?></a></li>
                        </ul>
                    </li>
                   <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['user-list','user-create'])): ?>
                        <li
                        class="main_dropdown
                        <?php if(request()->is(['admin-home/frontend/new-user','admin-home/frontend/all-user','admin-home/frontend/all-user/role'])): ?> active <?php endif; ?>
                        ">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-user"></i>
                            <span><?php echo e(__('Users Manage')); ?></span></a>
                        <ul class="collapse">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-list')): ?>
                            <li class="<?php echo e(active_menu('admin-home/frontend/all-user')); ?>"><a
                                    href="<?php echo e(route('admin.all.frontend.user')); ?>"><?php echo e(__('All Users')); ?></a></li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-create')): ?>
                            <li class="<?php echo e(active_menu('admin-home/frontend/new-user')); ?>"><a
                                    href="<?php echo e(route('admin.frontend.new.user')); ?>"><?php echo e(__('Add New User')); ?></a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['blog-list','blog-category-list','blog-tag-list','blog-create','blog-trashed-list','blog-single-settings'])): ?>
                          <li class=" <?php echo e(active_menu('admin-home/blog')); ?>

                            <?php if(request()->is(['admin-home/blog/*','admin-home/blog-category','admin-home/blog-tags'])): ?> active <?php endif; ?>">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-comment-alt"></i>
                            <span><?php echo e(__('Blogs')); ?></span></a>
                        <ul class="collapse">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('blog-list')): ?>
                            <li class="<?php echo e(active_menu('admin-home/blog')); ?> <?php if(request()->is('admin-home/blog-edit/*')): ?> active <?php endif; ?>"><a
                                        href="<?php echo e(route('admin.blog')); ?>"><?php echo e(__('All Blogs')); ?></a></li>
                            <?php endif; ?>

                             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('blog-category-list')): ?>
                             <li class="<?php echo e(active_menu('admin-home/blog-category')); ?>"><a
                                        href="<?php echo e(route('admin.blog.category')); ?>"><?php echo e(__('Category')); ?></a></li>
                             <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('blog-tag-list')): ?>
                                <li class="<?php echo e(active_menu('admin-home/blog-tags')); ?>"><a
                                            href="<?php echo e(route('admin.blog.tags')); ?>"><?php echo e(__('Tags')); ?></a></li>
                                <?php endif; ?>

                           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('blog-create')): ?>
                            <li class="<?php echo e(active_menu('admin-home/blog/new')); ?>"><a
                                        href="<?php echo e(route('admin.blog.new')); ?>"><?php echo e(__('Add New Post')); ?></a></li>
                            <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('blog-trashed-list')): ?>
                                    <li class="<?php echo e(active_menu('admin-home/blog/trashed')); ?>"><a
                                                href="<?php echo e(route('admin.blog.trashed')); ?>"><?php echo e(__('All Trashed Items')); ?></a></li>
                                <?php endif; ?>

                             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('blog-single-settings')): ?>
                            <li class="<?php echo e(active_menu('admin-home/blog/single-settings')); ?>"><a
                                        href="<?php echo e(route('admin.blog.single.settings')); ?>"><?php echo e(__('Blog Single Settings')); ?></a></li>

                                    <li class="<?php echo e(active_menu('admin-home/blog/others-settings')); ?>"><a
                                         href="<?php echo e(route('admin.blog.others.settings')); ?>"><?php echo e(__('Blog Others Settings')); ?></a></li>

                                    <li class="<?php echo e(active_menu('admin-home/blog/details-variant-settings')); ?>"><a
                                                href="<?php echo e(route('admin.blog.details.variant.settings')); ?>"><?php echo e(__('Blog Details Variant')); ?></a></li>
                             <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <li class="main_dropdown
                        <?php if(request()->is(['admin-home/rss/feed-all-info','admin-home/rss/import/rss-feed','admin-home/rss/feed/automation'])): ?> active <?php endif; ?>
                        ">
                            <a href="javascript:void(0)" aria-expanded="true"><i class="ti-user"></i>
                                <span><?php echo e(__('RSS Feed Manage')); ?></span></a>
                            <ul class="collapse">

                                <li class="<?php echo e(active_menu('admin-home/rss/feed-all-info')); ?>">
                                    <a href="<?php echo e(route('admin.blog.rss.feed.all.info')); ?>"><?php echo e(__('All Rss Feed')); ?></a></li>







                            </ul>
                        </li>


                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('faq-list')): ?>
                    <li class="main_dropdown <?php echo e(active_menu('admin-home/faq')); ?>">
                        <a href="<?php echo e(route('admin.faq')); ?>" aria-expanded="true"><i class="ti-control-forward"></i>
                            <span><?php echo e(__('FAQ')); ?></span></a>
                    </li>
                    <?php endif; ?>


                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['pages-list','pages-create'])): ?>
                    <li class="<?php echo e(active_menu('admin-home/dynamic-page')); ?>

                        <?php echo e(active_menu('admin-home/dynamic-page/new')); ?>

                        <?php if(request()->is('admin-home/page-edit/*')): ?> active <?php endif; ?>">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-write"></i>
                            <span><?php echo e(__('Pages')); ?></span></a>
                        <ul class="collapse">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('pages-list')): ?>
                            <li class="<?php echo e(active_menu('admin-home/dynamic-page')); ?> <?php if(request()->is('admin-home/dynamic-page/edit/*')): ?> active <?php endif; ?>"><a
                                        href="<?php echo e(route('admin.page')); ?>"><?php echo e(__('All Pages')); ?></a></li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('pages-create')): ?>
                            <li class="<?php echo e(active_menu('admin-home/dynamic-page/new')); ?>"><a
                                        href="<?php echo e(route('admin.page.new')); ?>"><?php echo e(__('Add New Page')); ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('testimonial-list')): ?>
                        <li class="main_dropdown <?php echo e(active_menu('admin-home/testimonial/all')); ?>">
                            <a href="<?php echo e(route('admin.testimonial')); ?>" aria-expanded="true"><i
                                        class="ti-control-forward"></i>
                                <span><?php echo e(__('Testimonial')); ?></span></a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('partner-list')): ?>
                        <li class="main_dropdown <?php echo e(active_menu('admin-home/partner')); ?>">
                            <a href="<?php echo e(route('admin.partner')); ?>" aria-expanded="true"><i class="ti-control-forward"></i>
                                <span><?php echo e(__('Partners')); ?></span></a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['pages-list','pages-create'])): ?>
                    <li class="<?php echo e(active_menu('admin-home/advertisement')); ?>

                           <?php echo e(active_menu('admin-home/advertisement/new')); ?>

                    <?php if(request()->is('admin-home/advertisement/edit/*')): ?> active <?php endif; ?>">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-announcement"></i>
                            <span><?php echo e(__('Advertisements')); ?></span></a>
                        <ul class="collapse">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('advertisement-list')): ?>
                            <li class="<?php echo e(active_menu('admin-home/advertisement')); ?> <?php if(request()->is('admin-home/advertisement/edit/*')): ?> active <?php endif; ?>"><a
                                        href="<?php echo e(route('admin.advertisement')); ?>"><?php echo e(__('All Advertisements')); ?></a></li>
                            <?php endif; ?>

                           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('advertisement-create')): ?>
                            <li class="<?php echo e(active_menu('admin-home/advertisement/new')); ?>"><a
                                        href="<?php echo e(route('admin.advertisement.new')); ?>"><?php echo e(__('Add New Advertisement')); ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>


                 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['pages-list','pages-create'])): ?>
                    <li class="<?php echo e(active_menu('admin-home/polls')); ?>

                    <?php if(request()->is('admin-home/polls/*')): ?> active <?php endif; ?>">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-layout-grid2"></i>
                            <span><?php echo e(__('Polls')); ?></span></a>
                        <ul class="collapse">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('poll-list')): ?>
                                <li class="<?php echo e(active_menu('admin-home/polls')); ?> <?php if(request()->is('admin-home/polls/edit/*')): ?> active <?php endif; ?>"><a
                                            href="<?php echo e(route('admin.polls')); ?>"><?php echo e(__('All Polls')); ?></a></li>
                                 <?php endif; ?>
                                 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('poll-create')): ?>
                                <li class="<?php echo e(active_menu('admin-home/polls/new')); ?>"><a
                                            href="<?php echo e(route('admin.polls.new')); ?>"><?php echo e(__('Add New Poll')); ?></a></li>
                                 <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['newsletter-list','newsletter-send-mail'])): ?>
                        <li class="main_dropdown <?php if(request()->is(['admin-home/newsletter/*','admin-home/newsletter'])): ?> active <?php endif; ?> ">
                            <a href="javascript:void(0)" aria-expanded="true"><i class="ti-email"></i>
                                <span><?php echo e(__('Newsletter Manage')); ?></span></a>
                            <ul class="collapse">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('newsletter-list')): ?>
                                    <li class="<?php echo e(active_menu('admin-home/newsletter')); ?>"><a
                                                href="<?php echo e(route('admin.newsletter')); ?>"><?php echo e(__('All Subscriber')); ?></a></li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('newsletter-send-mail')): ?>
                                    <li class="<?php echo e(active_menu('admin-home/newsletter/all')); ?>"><a
                                                href="<?php echo e(route('admin.newsletter.mail')); ?>"><?php echo e(__('Send Mail To All')); ?></a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>

                 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('form-builder')): ?>
                        <li class="main_dropdown <?php if(request()->is('admin-home/form-builder/*')): ?> active <?php endif; ?>">
                            <a href="javascript:void(0)"
                               aria-expanded="true"><i class="ti-write mr-2"></i>
                                <?php echo e(__('Form Builder')); ?>

                            </a>
                            <ul class="collapse">
                                <li class="<?php echo e(active_menu('admin-home/form-builder/all')); ?>">
                                    <a href="<?php echo e(route('admin.form.builder.all')); ?>"><?php echo e(__('All Custom Form')); ?></a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>

                 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['appearance-topbar-settings','appearance-media-image-manage','appearance-widget-builder','appearance-menu-list','appearance-header-banner-settings'])): ?>
                    <li class="main_dropdown <?php if(request()->is([
                            'admin-home/topbar-settings',
                            'admin-home/leftbar-settings',
                            'admin-home/media-upload/page',
                            'admin-home/menu',
                            'admin-home/widgets',
                            'admin-home/header-banner-settings',
                            'admin-home/menu-edit/*',
                        ])): ?> active
                        <?php endif; ?>">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-palette"></i>
                            <span><?php echo e(__('Appearance Settings')); ?></span></a>
                        <ul class="collapse">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('appearance-topbar-settings')): ?>
                            <li class="<?php echo e(active_menu('admin-home/topbar-settings')); ?>">
                                <a href="<?php echo e(route('admin.topbar.settings')); ?>">
                                    <?php echo e(__('Topbar Settings')); ?>

                                </a>
                            </li>
                           <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('appearance-header-banner-settings')): ?>
                                <li class="<?php echo e(active_menu('admin-home/header-banner-settings')); ?>">
                                    <a href="<?php echo e(route('admin.header.banner.settings')); ?>">
                                        <?php echo e(__('Header Banner Settings')); ?>

                                    </a>
                                </li>
                                <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('appearance-leftbar-settings')): ?>
                            <li class="<?php echo e(active_menu('admin-home/leftbar-settings')); ?>">
                                <a href="<?php echo e(route('admin.leftbar.settings')); ?>">
                                    <?php echo e(__('Leftbar Settings')); ?>

                                </a>
                            </li>
                            <?php endif; ?>


                           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('appearance-media-image-manage')): ?>
                            <li class="<?php echo e(active_menu('admin-home/media-upload/page')); ?>">
                                <a href="<?php echo e(route('admin.upload.media.images.page')); ?>"
                                   aria-expanded="true">
                                    <?php echo e(__('Media Images Manage')); ?>

                                </a>
                            </li>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('appearance-widget-builder')): ?>
                            <li class="<?php echo e(active_menu('admin-home/widgets')); ?>"><a
                                href="<?php echo e(route('admin.widgets')); ?>"><?php echo e(__('Widget Builder')); ?></a></li>
                            <?php endif; ?>

                           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('appearance-menu-list')): ?>
                            <li class="<?php echo e(active_menu('admin-home/menu')); ?>

                            <?php if(request()->is('admin-home/menu-edit/*')): ?> active <?php endif; ?>">
                                <a href="<?php echo e(route('admin.menu')); ?>"><?php echo e(__('Menu Manage')); ?></a></li>
                            <?php endif; ?>

                        </ul>
                    </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['page-settings-404-page-manage','page-settings-maintain-page-manage','misc-settings'])): ?>
                    <li class="main_dropdown
                        <?php if(request()->is([
                            'admin-home/contact-page/*',
                            'admin-home/404-page-manage',
                            'admin-home/maintains-page/settings',
                            'admin-home/misc/settings',

                        ])): ?> active <?php endif; ?> ">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-panel"></i>
                            <span><?php echo e(__('Other Page Settings')); ?></span></a>
                        <ul class="collapse">

                           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('page-settings-404-page-manage')): ?>
                            <li class="<?php echo e(active_menu('admin-home/404-page-manage')); ?>">
                                <a href="<?php echo e(route('admin.404.page.settings')); ?>"><?php echo e(__('404 Page Manage')); ?></a>
                            </li>
                           <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('page-settings-maintain-page-manage')): ?>
                            <li class="<?php echo e(active_menu('admin-home/maintains-page/settings')); ?>">
                                <a href="<?php echo e(route('admin.maintains.page.settings')); ?>"><?php echo e(__('Maintain Page Manage')); ?></a>
                            </li>
                            <?php endif; ?>

                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('misc-settings')): ?>
                           <li class="<?php echo e(active_menu('admin-home/misc/settings')); ?>">
                               <a href="<?php echo e(route('admin.misc.settings')); ?>"><?php echo e(__('Miscellaneous Settings')); ?></a>
                           </li>
                         <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any([
                        'general-settings-reading-settings',
                        'general-settings-global-navbar-settings',
                        'general-settings-site-identity',
                        'general-settings-basic-settings',
                        'general-settings-color-settings',
                        'general-settings-typography-settings',
                        'general-settings-seo-settings',
                        'general-settings-third-party-scripts',
                        'general-settings-email-template',
                        'general-settings-email-settings',
                        'general-settings-smtp-settings',
                        'general-settings-custom-css',
                        'general-settings-custom-js',
                        'general-settings-licence-settings',
                        'general-settings-cache-settings',
                        'database-upgrade'
                    ])): ?>
                        <li class="<?php if(request()->is('admin-home/general-settings/*')): ?> active <?php endif; ?>">
                            <a href="javascript:void(0)" aria-expanded="true"><i class="ti-settings"></i>
                                <span><?php echo e(__('General Settings')); ?></span></a>
                            <ul class="collapse ">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('general-settings-reading-settings')): ?>
                                <li class="<?php echo e(active_menu('admin-home/general-settings/reading')); ?>"><a
                                            href="<?php echo e(route('admin.general.reading')); ?>"><?php echo e(__('Reading')); ?></a>
                                </li>
                                <?php endif; ?>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('general-settings-global-navbar-settings')): ?>
                                    <li class="<?php echo e(active_menu('admin-home/general-settings/global-variant-navbar')); ?>"><a
                                                href="<?php echo e(route('admin.general.global.variant.navbar')); ?>"><?php echo e(__('Navbar Global Variant')); ?></a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('general-settings-global-footer-settings')): ?>
                                    <li class="<?php echo e(active_menu('admin-home/general-settings/global-variant-footer')); ?>"><a
                                                href="<?php echo e(route('admin.general.global.variant.footer')); ?>"><?php echo e(__('Footer Global Variant')); ?></a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('general-settings-site-identity')): ?>
                                        <li class="<?php echo e(active_menu('admin-home/general-settings/site-identity')); ?>"><a
                                                    href="<?php echo e(route('admin.general.site.identity')); ?>"><?php echo e(__('Site Identity')); ?></a>
                                        </li>
                                    <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('general-settings-basic-settings')): ?>
                                <li class="<?php echo e(active_menu('admin-home/general-settings/basic-settings')); ?>"><a
                                            href="<?php echo e(route('admin.general.basic.settings')); ?>"><?php echo e(__('Basic Settings')); ?></a>
                                </li>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('general-settings-color-settings')): ?>
                                <li class="<?php echo e(active_menu('admin-home/general-settings/color-settings')); ?>"><a
                                            href="<?php echo e(route('admin.general.color.settings')); ?>"><?php echo e(__('Color Settings')); ?></a>
                                </li>
                                 <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('general-settings-typography-settings')): ?>
                                <li class="<?php echo e(active_menu('admin-home/general-settings/typography-settings')); ?>"><a
                                            href="<?php echo e(route('admin.general.typography.settings')); ?>"><?php echo e(__('Typography Settings')); ?></a>
                                </li>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('general-settings-seo-settings')): ?>
                                <li class="<?php echo e(active_menu('admin-home/general-settings/seo-settings')); ?>"><a
                                            href="<?php echo e(route('admin.general.seo.settings')); ?>"><?php echo e(__('SEO Settings')); ?></a>
                                </li>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('general-settings-third-party-scripts')): ?>
                                <li class="<?php echo e(active_menu('admin-home/general-settings/scripts')); ?>"><a
                                            href="<?php echo e(route('admin.general.scripts.settings')); ?>"><?php echo e(__('Third Party Scripts')); ?></a>
                                </li>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('general-settings-email-template')): ?>
                                <li class="<?php echo e(active_menu('admin-home/general-settings/email-template')); ?>"><a
                                            href="<?php echo e(route('admin.general.email.template')); ?>"><?php echo e(__('Email Template')); ?></a>
                                </li>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('general-settings-email-settings')): ?>
                                <li class="<?php echo e(active_menu('admin-home/general-settings/email-settings')); ?>"><a
                                            href="<?php echo e(route('admin.general.email.settings')); ?>"><?php echo e(__('Email Settings')); ?></a>
                                </li>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('general-settings-smtp-settings')): ?>
                                <li class="<?php echo e(active_menu('admin-home/general-settings/smtp-settings')); ?>"><a
                                            href="<?php echo e(route('admin.general.smtp.settings')); ?>"><?php echo e(__('SMTP Settings')); ?></a>
                                </li>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('general-settings-custom-css')): ?>
                                <li class="<?php echo e(active_menu('admin-home/general-settings/custom-css')); ?>"><a
                                            href="<?php echo e(route('admin.general.custom.css')); ?>"><?php echo e(__('Custom CSS')); ?></a></li>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('general-settings-custom-js')): ?>
                                <li class="<?php echo e(active_menu('admin-home/general-settings/custom-js')); ?>"><a
                                            href="<?php echo e(route('admin.general.custom.js')); ?>"><?php echo e(__('Custom JS')); ?></a></li>
                                 <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('general-settings-licence-settings')): ?>
                                <li class="<?php echo e(active_menu('admin-home/general-settings/license-setting')); ?>"><a
                                            href="<?php echo e(route('admin.general.license.settings')); ?>"><?php echo e(__('Licence Settings')); ?></a>
                                </li>
                                <?php endif; ?>
                                <li class="<?php echo e(active_menu('admin-home/general-settings/sitemap-settings')); ?>"><a
                                            href="<?php echo e(route('admin.general.sitemap.settings')); ?>"><?php echo e(__('Generate Sitemap')); ?></a>
                                </li>

                                 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('general-settings-rss-feed')): ?>
                                <li class="<?php echo e(active_menu('admin-home/general-settings/rss-settings')); ?>"><a
                                            href="<?php echo e(route('admin.general.rss.feed.settings')); ?>"><?php echo e(__('RSS Feed Settings')); ?></a>
                                </li>
                                  <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('database-upgrade')): ?>
                                 <li class="<?php echo e(active_menu('admin-home/general-settings/database-upgrade')); ?>"><a
                                        href="<?php echo e(route('admin.general.database.upgrade')); ?>"><?php echo e(__('Database Upgrade')); ?></a>
                                  </li>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('general-settings-cache-settings')): ?>
                                <li class="<?php echo e(active_menu('admin-home/general-settings/cache-settings')); ?>"><a
                                            href="<?php echo e(route('admin.general.cache.settings')); ?>"><?php echo e(__('Cache Settings')); ?></a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>

                       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('language-list')): ?>
                        <li class="<?php if(request()->is('admin-home/languages/*') || request()->is('admin-home/languages') ): ?> active <?php endif; ?>">
                            <a href="<?php echo e(route('admin.languages')); ?>" aria-expanded="true"><i class="ti-signal"></i>
                                <span><?php echo e(__('Languages')); ?></span></a>
                        </li>
                       <?php endif; ?>

                </ul>
            </nav>
        </div>
    </div>
</div>
<?php /**PATH /home/xgenxchi/public_html/laravel/katerio/beta/@core/resources/views/backend/partials/sidebar.blade.php ENDPATH**/ ?>