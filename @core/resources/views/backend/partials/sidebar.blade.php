<div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <a href="{{route('admin.home')}}">
                @if(get_static_option('site_admin_dark_mode') == 'off')
                {!! render_image_markup_by_attachment_id(get_static_option('site_logo'),'','',false) !!}
                @else
                {!! render_image_markup_by_attachment_id(get_static_option('site_white_logo'),'','',false) !!}
                @endif
            </a>
        </div>
    </div>

    <div class="main-menu">
        <div class="menu-inner">
            <nav>
                <ul class="metismenu" id="menu">
                    <li class="{{active_menu('admin-home')}}">
                        <a href="{{route('admin.home')}}"
                           aria-expanded="true">
                            <i class="ti-dashboard"></i>
                            <span>@lang('dashboard')</span>
                        </a>
                    </li>

                  @if(auth()->guard('admin')->user()->hasRole('Super Admin'))
                    <li class="
                        {{active_menu('admin-home/admin/new')}}
                    {{active_menu('admin-home/admin/role')}}
                    {{active_menu('admin-home/admin/all')}}">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-user"></i>
                            <span>{{__('Admin Role Manage')}}</span></a>
                        <ul class="collapse">
                            <li class="{{active_menu('admin-home/admin/all')}}"><a
                                        href="{{route('admin.all.user')}}">{{__('All Admin')}}</a></li>
                            <li class="{{active_menu('admin-home/admin/new')}}"><a
                                        href="{{route('admin.new.user')}}">{{__('Add New Admin')}}</a></li>
                            <li class="{{active_menu('admin-home/admin/role')}} "><a
                                        href="{{route('admin.all.admin.role')}}">{{__('All Admin Role')}}</a></li>
                        </ul>
                    </li>
                   @endif

                    @canany(['user-list','user-create'])
                        <li
                        class="main_dropdown
                        @if(request()->is(['admin-home/frontend/new-user','admin-home/frontend/all-user','admin-home/frontend/all-user/role'])) active @endif
                        ">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-user"></i>
                            <span>{{__('Users Manage')}}</span></a>
                        <ul class="collapse">
                            @can('user-list')
                            <li class="{{active_menu('admin-home/frontend/all-user')}}"><a
                                    href="{{route('admin.all.frontend.user')}}">{{__('All Users')}}</a></li>
                            @endcan
                            @can('user-create')
                            <li class="{{active_menu('admin-home/frontend/new-user')}}"><a
                                    href="{{route('admin.frontend.new.user')}}">{{__('Add New User')}}</a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany

                    @canany(['blog-list','blog-category-list','blog-tag-list','blog-create','blog-trashed-list','blog-single-settings'])
                          <li class=" {{active_menu('admin-home/blog')}}
                            @if(request()->is(['admin-home/blog/*','admin-home/blog-category','admin-home/blog-tags'])) active @endif">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-comment-alt"></i>
                            <span>{{__('Blogs')}}</span></a>
                        <ul class="collapse">
                            @can('blog-list')
                            <li class="{{active_menu('admin-home/blog')}} @if(request()->is('admin-home/blog-edit/*')) active @endif"><a
                                        href="{{route('admin.blog')}}">{{__('All Blogs')}}</a></li>
                            @endcan

                             @can('blog-category-list')
                             <li class="{{active_menu('admin-home/blog-category')}}"><a
                                        href="{{route('admin.blog.category')}}">{{__('Category')}}</a></li>
                             @endcan
                                @can('blog-tag-list')
                                <li class="{{active_menu('admin-home/blog-tags')}}"><a
                                            href="{{route('admin.blog.tags')}}">{{__('Tags')}}</a></li>
                                @endcan

                           @can('blog-create')
                            <li class="{{active_menu('admin-home/blog/new')}}"><a
                                        href="{{route('admin.blog.new')}}">{{__('Add New Post')}}</a></li>
                            @endcan

                                @can('blog-trashed-list')
                                    <li class="{{active_menu('admin-home/blog/trashed')}}"><a
                                                href="{{route('admin.blog.trashed')}}">{{__('All Trashed Items')}}</a></li>
                                @endcan

                             @can('blog-single-settings')
                            <li class="{{active_menu('admin-home/blog/single-settings')}}"><a
                                        href="{{route('admin.blog.single.settings')}}">{{__('Blog Single Settings')}}</a></li>

                                    <li class="{{active_menu('admin-home/blog/others-settings')}}"><a
                                         href="{{route('admin.blog.others.settings')}}">{{__('Blog Others Settings')}}</a></li>

                                    <li class="{{active_menu('admin-home/blog/details-variant-settings')}}"><a
                                                href="{{route('admin.blog.details.variant.settings')}}">{{__('Blog Details Variant')}}</a></li>
                             @endcan
                        </ul>
                    </li>
                    @endcanany

                    <li class="main_dropdown
                        @if(request()->is(['admin-home/rss/feed-all-info','admin-home/rss/import/rss-feed','admin-home/rss/feed/automation'])) active @endif
                        ">
                            <a href="javascript:void(0)" aria-expanded="true"><i class="ti-user"></i>
                                <span>{{__('RSS Feed Manage')}}</span></a>
                            <ul class="collapse">

                                <li class="{{active_menu('admin-home/rss/feed-all-info')}}">
                                    <a href="{{route('admin.blog.rss.feed.all.info')}}">{{__('All Rss Feed')}}</a></li>

{{--                                <li class="{{active_menu('admin-home/rss/import/rss-feed')}}">--}}
{{--                                    <a href="{{route('admin.blog.import.rss.feed')}}">{{__('Import Rss Feed Single')}}</a></li>--}}

{{--                                <li class="{{active_menu('admin-home/rss/feed/automation')}}">--}}
{{--                                    <a href="{{route('admin.blog.rss.feed.automation')}}">{{__('Rss Feed Automation')}}</a></li>--}}

                            </ul>
                        </li>


                    @can('faq-list')
                    <li class="main_dropdown {{active_menu('admin-home/faq')}}">
                        <a href="{{route('admin.faq')}}" aria-expanded="true"><i class="ti-control-forward"></i>
                            <span>{{__('FAQ')}}</span></a>
                    </li>
                    @endcan


                @canany(['pages-list','pages-create'])
                    <li class="{{active_menu('admin-home/dynamic-page')}}
                        {{active_menu('admin-home/dynamic-page/new')}}
                        @if(request()->is('admin-home/page-edit/*')) active @endif">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-write"></i>
                            <span>{{__('Pages')}}</span></a>
                        <ul class="collapse">
                            @can('pages-list')
                            <li class="{{active_menu('admin-home/dynamic-page')}} @if(request()->is('admin-home/dynamic-page/edit/*')) active @endif"><a
                                        href="{{route('admin.page')}}">{{__('All Pages')}}</a></li>
                            @endcan
                            @can('pages-create')
                            <li class="{{active_menu('admin-home/dynamic-page/new')}}"><a
                                        href="{{route('admin.page.new')}}">{{__('Add New Page')}}</a></li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany

                    @can('testimonial-list')
                        <li class="main_dropdown {{active_menu('admin-home/testimonial/all')}}">
                            <a href="{{route('admin.testimonial')}}" aria-expanded="true"><i
                                        class="ti-control-forward"></i>
                                <span>{{__('Testimonial')}}</span></a>
                        </li>
                    @endcan

                    @can('partner-list')
                        <li class="main_dropdown {{active_menu('admin-home/partner')}}">
                            <a href="{{route('admin.partner')}}" aria-expanded="true"><i class="ti-control-forward"></i>
                                <span>{{__('Partners')}}</span></a>
                        </li>
                    @endcan

                    @canany(['pages-list','pages-create'])
                    <li class="{{active_menu('admin-home/advertisement')}}
                           {{active_menu('admin-home/advertisement/new')}}
                    @if(request()->is('admin-home/advertisement/edit/*')) active @endif">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-announcement"></i>
                            <span>{{__('Advertisements')}}</span></a>
                        <ul class="collapse">
                            @can('advertisement-list')
                            <li class="{{active_menu('admin-home/advertisement')}} @if(request()->is('admin-home/advertisement/edit/*')) active @endif"><a
                                        href="{{route('admin.advertisement')}}">{{__('All Advertisements')}}</a></li>
                            @endcan

                           @can('advertisement-create')
                            <li class="{{active_menu('admin-home/advertisement/new')}}"><a
                                        href="{{route('admin.advertisement.new')}}">{{__('Add New Advertisement')}}</a></li>
                            @endcan
                        </ul>
                    </li>
                    @endcan


                 @canany(['pages-list','pages-create'])
                    <li class="{{active_menu('admin-home/polls')}}
                    @if(request()->is('admin-home/polls/*')) active @endif">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-layout-grid2"></i>
                            <span>{{__('Polls')}}</span></a>
                        <ul class="collapse">
                                @can('poll-list')
                                <li class="{{active_menu('admin-home/polls')}} @if(request()->is('admin-home/polls/edit/*')) active @endif"><a
                                            href="{{route('admin.polls')}}">{{__('All Polls')}}</a></li>
                                 @endcan
                                 @can('poll-create')
                                <li class="{{active_menu('admin-home/polls/new')}}"><a
                                            href="{{route('admin.polls.new')}}">{{__('Add New Poll')}}</a></li>
                                 @endcan
                        </ul>
                    </li>
                    @endcan

                    @canany(['newsletter-list','newsletter-send-mail'])
                        <li class="main_dropdown @if(request()->is(['admin-home/newsletter/*','admin-home/newsletter'])) active @endif ">
                            <a href="javascript:void(0)" aria-expanded="true"><i class="ti-email"></i>
                                <span>{{__('Newsletter Manage')}}</span></a>
                            <ul class="collapse">
                                @can('newsletter-list')
                                    <li class="{{active_menu('admin-home/newsletter')}}"><a
                                                href="{{route('admin.newsletter')}}">{{__('All Subscriber')}}</a></li>
                                @endcan
                                @can('newsletter-send-mail')
                                    <li class="{{active_menu('admin-home/newsletter/all')}}"><a
                                                href="{{route('admin.newsletter.mail')}}">{{__('Send Mail To All')}}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                 @can('form-builder')
                        <li class="main_dropdown @if(request()->is('admin-home/form-builder/*')) active @endif">
                            <a href="javascript:void(0)"
                               aria-expanded="true"><i class="ti-write mr-2"></i>
                                {{__('Form Builder')}}
                            </a>
                            <ul class="collapse">
                                <li class="{{active_menu('admin-home/form-builder/all')}}">
                                    <a href="{{route('admin.form.builder.all')}}">{{__('All Custom Form')}}</a>
                                </li>
                            </ul>
                        </li>
                    @endcan

                 @canany(['appearance-topbar-settings','appearance-media-image-manage','appearance-widget-builder','appearance-menu-list','appearance-header-banner-settings'])
                    <li class="main_dropdown @if(request()->is([
                            'admin-home/topbar-settings',
                            'admin-home/leftbar-settings',
                            'admin-home/media-upload/page',
                            'admin-home/menu',
                            'admin-home/widgets',
                            'admin-home/header-banner-settings',
                            'admin-home/menu-edit/*',
                        ])) active
                        @endif">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-palette"></i>
                            <span>{{__('Appearance Settings')}}</span></a>
                        <ul class="collapse">
                            @can('appearance-topbar-settings')
                            <li class="{{active_menu('admin-home/topbar-settings')}}">
                                <a href="{{route('admin.topbar.settings')}}">
                                    {{__('Topbar Settings')}}
                                </a>
                            </li>
                           @endcan

                                @can('appearance-header-banner-settings')
                                <li class="{{active_menu('admin-home/header-banner-settings')}}">
                                    <a href="{{route('admin.header.banner.settings')}}">
                                        {{__('Header Banner Settings')}}
                                    </a>
                                </li>
                                @endcan

                            @can('appearance-leftbar-settings')
                            <li class="{{active_menu('admin-home/leftbar-settings')}}">
                                <a href="{{route('admin.leftbar.settings')}}">
                                    {{__('Leftbar Settings')}}
                                </a>
                            </li>
                            @endcan


                           @can('appearance-media-image-manage')
                            <li class="{{active_menu('admin-home/media-upload/page')}}">
                                <a href="{{route('admin.upload.media.images.page')}}"
                                   aria-expanded="true">
                                    {{__('Media Images Manage')}}
                                </a>
                            </li>
                            @endcan

                            @can('appearance-widget-builder')
                            <li class="{{active_menu('admin-home/widgets')}}"><a
                                href="{{route('admin.widgets')}}">{{__('Widget Builder')}}</a></li>
                            @endcan

                           @can('appearance-menu-list')
                            <li class="{{active_menu('admin-home/menu')}}
                            @if(request()->is('admin-home/menu-edit/*')) active @endif">
                                <a href="{{route('admin.menu')}}">{{__('Menu Manage')}}</a></li>
                            @endcan

                        </ul>
                    </li>
                    @endcanany

                    @canany(['page-settings-404-page-manage','page-settings-maintain-page-manage','misc-settings'])
                    <li class="main_dropdown
                        @if(request()->is([
                            'admin-home/contact-page/*',
                            'admin-home/404-page-manage',
                            'admin-home/maintains-page/settings',
                            'admin-home/misc/settings',

                        ])) active @endif ">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-panel"></i>
                            <span>{{__('Other Page Settings')}}</span></a>
                        <ul class="collapse">

                           @can('page-settings-404-page-manage')
                            <li class="{{active_menu('admin-home/404-page-manage')}}">
                                <a href="{{route('admin.404.page.settings')}}">{{__('404 Page Manage')}}</a>
                            </li>
                           @endcan

                            @can('page-settings-maintain-page-manage')
                            <li class="{{active_menu('admin-home/maintains-page/settings')}}">
                                <a href="{{route('admin.maintains.page.settings')}}">{{__('Maintain Page Manage')}}</a>
                            </li>
                            @endcan

                         @can('misc-settings')
                           <li class="{{active_menu('admin-home/misc/settings')}}">
                               <a href="{{route('admin.misc.settings')}}">{{__('Miscellaneous Settings')}}</a>
                           </li>
                         @endcan
                        </ul>
                    </li>
                    @endcanany

                    @canany([
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
                    ])
                        <li class="@if(request()->is('admin-home/general-settings/*')) active @endif">
                            <a href="javascript:void(0)" aria-expanded="true"><i class="ti-settings"></i>
                                <span>{{__('General Settings')}}</span></a>
                            <ul class="collapse ">
                                @can('general-settings-reading-settings')
                                <li class="{{active_menu('admin-home/general-settings/reading')}}"><a
                                            href="{{route('admin.general.reading')}}">{{__('Reading')}}</a>
                                </li>
                                @endcan

                                    @can('general-settings-global-navbar-settings')
                                    <li class="{{active_menu('admin-home/general-settings/global-variant-navbar')}}"><a
                                                href="{{route('admin.general.global.variant.navbar')}}">{{__('Navbar Global Variant')}}</a>
                                    </li>
                                    @endcan

                                    @can('general-settings-global-footer-settings')
                                    <li class="{{active_menu('admin-home/general-settings/global-variant-footer')}}"><a
                                                href="{{route('admin.general.global.variant.footer')}}">{{__('Footer Global Variant')}}</a>
                                    </li>
                                    @endcan

                                    @can('general-settings-site-identity')
                                        <li class="{{active_menu('admin-home/general-settings/site-identity')}}"><a
                                                    href="{{route('admin.general.site.identity')}}">{{__('Site Identity')}}</a>
                                        </li>
                                    @endcan

                                @can('general-settings-basic-settings')
                                <li class="{{active_menu('admin-home/general-settings/basic-settings')}}"><a
                                            href="{{route('admin.general.basic.settings')}}">{{__('Basic Settings')}}</a>
                                </li>
                                @endcan

                                @can('general-settings-color-settings')
                                <li class="{{active_menu('admin-home/general-settings/color-settings')}}"><a
                                            href="{{route('admin.general.color.settings')}}">{{__('Color Settings')}}</a>
                                </li>
                                 @endcan

                                @can('general-settings-typography-settings')
                                <li class="{{active_menu('admin-home/general-settings/typography-settings')}}"><a
                                            href="{{route('admin.general.typography.settings')}}">{{__('Typography Settings')}}</a>
                                </li>
                                @endcan

                                @can('general-settings-seo-settings')
                                <li class="{{active_menu('admin-home/general-settings/seo-settings')}}"><a
                                            href="{{route('admin.general.seo.settings')}}">{{__('SEO Settings')}}</a>
                                </li>
                                @endcan

                                @can('general-settings-third-party-scripts')
                                <li class="{{active_menu('admin-home/general-settings/scripts')}}"><a
                                            href="{{route('admin.general.scripts.settings')}}">{{__('Third Party Scripts')}}</a>
                                </li>
                                @endcan

                                @can('general-settings-email-template')
                                <li class="{{active_menu('admin-home/general-settings/email-template')}}"><a
                                            href="{{route('admin.general.email.template')}}">{{__('Email Template')}}</a>
                                </li>
                                @endcan

                                @can('general-settings-email-settings')
                                <li class="{{active_menu('admin-home/general-settings/email-settings')}}"><a
                                            href="{{route('admin.general.email.settings')}}">{{__('Email Settings')}}</a>
                                </li>
                                @endcan

                                @can('general-settings-smtp-settings')
                                <li class="{{active_menu('admin-home/general-settings/smtp-settings')}}"><a
                                            href="{{route('admin.general.smtp.settings')}}">{{__('SMTP Settings')}}</a>
                                </li>
                                @endcan

                                @can('general-settings-custom-css')
                                <li class="{{active_menu('admin-home/general-settings/custom-css')}}"><a
                                            href="{{route('admin.general.custom.css')}}">{{__('Custom CSS')}}</a></li>
                                @endcan

                                @can('general-settings-custom-js')
                                <li class="{{active_menu('admin-home/general-settings/custom-js')}}"><a
                                            href="{{route('admin.general.custom.js')}}">{{__('Custom JS')}}</a></li>
                                 @endcan

                                @can('general-settings-licence-settings')
                                <li class="{{active_menu('admin-home/general-settings/license-setting')}}"><a
                                            href="{{route('admin.general.license.settings')}}">{{__('Licence Settings')}}</a>
                                </li>
                                @endcan
                                <li class="{{active_menu('admin-home/general-settings/sitemap-settings')}}"><a
                                            href="{{route('admin.general.sitemap.settings')}}">{{__('Generate Sitemap')}}</a>
                                </li>

                                 @can('general-settings-rss-feed')
                                <li class="{{active_menu('admin-home/general-settings/rss-settings')}}"><a
                                            href="{{route('admin.general.rss.feed.settings')}}">{{__('RSS Feed Settings')}}</a>
                                </li>
                                  @endcan

                                @can('database-upgrade')
                                 <li class="{{active_menu('admin-home/general-settings/database-upgrade')}}"><a
                                        href="{{route('admin.general.database.upgrade')}}">{{__('Database Upgrade')}}</a>
                                  </li>
                                @endcan

                                @can('general-settings-cache-settings')
                                <li class="{{active_menu('admin-home/general-settings/cache-settings')}}"><a
                                            href="{{route('admin.general.cache.settings')}}">{{__('Cache Settings')}}</a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                       @can('language-list')
                        <li class="@if(request()->is('admin-home/languages/*') || request()->is('admin-home/languages') ) active @endif">
                            <a href="{{route('admin.languages')}}" aria-expanded="true"><i class="ti-signal"></i>
                                <span>{{__('Languages')}}</span></a>
                        </li>
                       @endcan

                </ul>
            </nav>
        </div>
    </div>
</div>
