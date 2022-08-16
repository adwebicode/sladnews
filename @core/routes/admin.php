<?php

use Illuminate\Support\Facades\Route;

//Route::middleware(['setlang','demo'])->group(function(){
    


/*----------------------------------------------------------------------------------------------------------------------------
                                                     ADMIN PANEL ROUTES
----------------------------------------------------------------------------------------------------------------------------*/
    Route::get('/', 'AdminDashboardController@adminIndex')->name('admin.home');
    Route::get('/dark-mode-toggle', 'AdminDashboardController@dark_mode_toggle')->name('admin.dark.mode.toggle');
    

    // media upload routes for restrict user in demo mode
    Route::get('/media-upload/page','MediaUploadController@all_upload_media_images_for_page')->name('admin.upload.media.images.page');
    Route::post('/media-upload/delete','MediaUploadController@delete_upload_media_file')->name('admin.upload.media.file.delete');


    /*--------------------------
      PAGE BUILDER
    --------------------------*/
    Route::post('/update', 'PageBuilderController@update_addon_content')->name('admin.page.builder.update');
    Route::post('/new', 'PageBuilderController@store_new_addon_content')->name('admin.page.builder.new');
    Route::post('/delete', 'PageBuilderController@delete')->name('admin.page.builder.delete');
    Route::post('/update-order', 'PageBuilderController@update_addon_order')->name('admin.page.builder.update.addon.order');
    Route::post('/get-admin-markup', 'PageBuilderController@get_admin_panel_addon_markup')->name('admin.page.builder.get.addon.markup');



/*----------------------------------------------------------------------------------------------------------------------------
| DYNAMIC PAGE ROUTES
|----------------------------------------------------------------------------------------------------------------------------*/

    Route::group(['prefix'=> 'dynamic-page'],function(){
        Route::get('/','PagesController@index')->name('admin.page');
        Route::get('/new','PagesController@new_page')->name('admin.page.new');
        Route::post('/new','PagesController@store_new_page');
        Route::get('/edit/{id}','PagesController@edit_page')->name('admin.page.edit');
        Route::post('/update/{id}','PagesController@update_page')->name('admin.page.update');
        Route::post('/delete/{id}','PagesController@delete_page')->name('admin.page.delete');
        Route::post('/delete/lang/all/{id}','PagesController@delete_page_lang_all')->name('admin.page.delete.lang.all');
        Route::post('/bulk-action','PagesController@bulk_action')->name('admin.page.bulk.action');
    });

/*----------------------------------------------------------------------------------------------------------------------------
| ADVERTISEMENT ROUTES
|----------------------------------------------------------------------------------------------------------------------------*/
Route::group(['prefix'=>'advertisement'],function(){
    Route::get('/','AdvertisementController@index')->name('admin.advertisement');
    Route::get('/new','AdvertisementController@new_advertisement')->name('admin.advertisement.new');
    Route::post('/store','AdvertisementController@store_advertisement')->name('admin.advertisement.store');
    Route::get('/edit/{id}','AdvertisementController@edit_advertisement')->name('admin.advertisement.edit');
    Route::post('/update/{id}','AdvertisementController@update_advertisement')->name('admin.advertisement.update');
    Route::post('/delete/{id}','AdvertisementController@delete_advertisement')->name('admin.advertisement.delete');
    Route::post('/bulk-action', 'AdvertisementController@bulk_action')->name('admin.advertisement.bulk.action');
});

/*----------------------------------------------------------------------------------------------------------------------------
 | CLIENT AREA ROUTES
 |----------------------------------------------------------------------------------------------------------------------------*/
Route::group(['prefix' => 'partner'], function () {
    Route::get('/', 'PartnerController@index')->name('admin.partner');
    Route::post('/', 'PartnerController@store');
    Route::post('/update', 'PartnerController@update')->name('admin.partner.update');
    Route::post('/delete/{id}', 'PartnerController@delete')->name('admin.partner.delete');
    Route::post('/bulk-action', 'PartnerController@bulk_action')->name('admin.partner.bulk.action');
});



/*----------------------------------------------------------------------------------------------------------------------------
| POLL AND VOTING ROUTES
|----------------------------------------------------------------------------------------------------------------------------*/
Route::group(['prefix'=>'polls'],function(){
    Route::get('/','PollController@index')->name('admin.polls');
    Route::get('/new','PollController@new_poll')->name('admin.polls.new');
    Route::post('/store','PollController@store_poll')->name('admin.polls.store');
    Route::get('/edit/{id}','PollController@edit_poll')->name('admin.poll.edit');
    Route::post('/update/{id}','PollController@update_poll')->name('admin.poll.update');
    Route::get('/result/{id}','PollController@view_result')->name('admin.poll.result');
    Route::post('/delete/{id}','PollController@delete_poll')->name('admin.poll.delete');
    Route::post('/bulk-action', 'PollController@bulk_action')->name('admin.poll.bulk.action');
});

/*----------------------------------------------------------------------------------------------------------------------------
| TESTIMONIAL  ROUTES
|----------------------------------------------------------------------------------------------------------------------------*/
Route::group(['prefix' => 'testimonial'], function () {
    Route::get('/all','TestimonialController@index')->name('admin.testimonial');
    Route::post('/all','TestimonialController@store');
    Route::post('/clone','TestimonialController@clone')->name('admin.testimonial.clone');
    Route::post('/update','TestimonialController@update')->name('admin.testimonial.update');
    Route::post('/delete/{id}','TestimonialController@delete')->name('admin.testimonial.delete');
    Route::post('/bulk-action','TestimonialController@bulk_action')->name('admin.testimonial.bulk.action');
});



/*----------------------------------------------------------------------------------------------------------------------------
|NEWSLETTER PAGE MANAGE
|----------------------------------------------------------------------------------------------------------------------------*/
Route::group(['prefix' => 'newsletter'], function () {
    //newsletter
    Route::get('/', 'NewsletterController@index')->name('admin.newsletter');
    Route::post('/delete/{id}', 'NewsletterController@delete')->name('admin.newsletter.delete');
    Route::post('/single', 'NewsletterController@send_mail')->name('admin.newsletter.single.mail');
    Route::get('/all', 'NewsletterController@send_mail_all_index')->name('admin.newsletter.mail');
    Route::post('/all', 'NewsletterController@send_mail_all');
    Route::post('/new', 'NewsletterController@add_new_sub')->name('admin.newsletter.new.add');
    Route::post('/bulk-action', 'NewsletterController@bulk_action')->name('admin.newsletter.bulk.action');
    Route::post('/newsletter/verify-mail-send','NewsletterController@verify_mail_send')->name('admin.newsletter.verify.mail.send');
});


/*----------------------------------------------------------------------------------------------------------------------------
 | FAQ ROUTES
 |----------------------------------------------------------------------------------------------------------------------------*/
Route::group(['prefix' => 'faq'], function () {
    Route::get('/', 'FaqController@index')->name('admin.faq');
    Route::post('/', 'FaqController@store');
    Route::post('/update-faq', 'FaqController@update')->name('admin.faq.update');
    Route::post('/delete-faq/{id}', 'FaqController@delete')->name('admin.faq.delete');
    Route::post('/clone-faq', 'FaqController@clone')->name('admin.faq.clone');
    Route::post('/faq/bulk-action', 'FaqController@bulk_action')->name('admin.faq.bulk.action');
});


/*===================================================
     PAGE BUILDER ROUTE
 ==================================================*/
Route::group(['prefix' => 'page-builder','middleware' => 'auth:admin'],function () {
    /*-------------------------
        HOME PAGE BUILDER
    -------------------------*/
    Route::get('/home-page', 'PageBuilderController@homepage_builder')->name('admin.home.page.builder');
    Route::post('/home-page', 'PageBuilderController@update_homepage_builder');
    /*-------------------------
         ABOUT PAGE BUILDER
    -------------------------*/
    Route::get('/about-page', 'PageBuilderController@aboutpage_builder')->name('admin.about.page.builder');
    Route::post('/about-page', 'PageBuilderController@update_aboutpage_builder');
    /*-------------------------
         CONTACT PAGE BUILDER
    -------------------------*/
    Route::get('/contact-page', 'PageBuilderController@contactpage_builder')->name('admin.contact.page.builder');
    Route::post('/contact-page', 'PageBuilderController@update_contactpage_builder');

    /*-------------------------
       DYNAMIC PAGE BUILDER
    -------------------------*/
    Route::get('/dynamic-page/{type}/{id}', 'PageBuilderController@dynamicpage_builder')->name('admin.dynamic.page.builder');
    Route::post('/dynamic-page', 'PageBuilderController@update_dynamicpage_builder')->name('admin.dynamic.page.builder.store');

});


/*-------------------------
    MISC Routes
-------------------------*/
Route::prefix('misc')->group(function (){
    Route::get('/settings', 'MiscellaneousController@misc_index')->name('admin.misc.settings');
    Route::post('/settings', 'MiscellaneousController@misc_update');
});


/*----------------------------------------------------------------------------------------------------------------------------
| GENERAL SETTINGS MANAGE
|----------------------------------------------------------------------------------------------------------------------------*/
    Route::prefix('general-settings')->group(function (){
        
        /*----------------------------------------------------
        SITEMAP SETTINGS
        ----------------------------------------------------*/
        Route::get('/sitemap-settings', 'GeneralSettingsController@sitemap_settings')->name('admin.general.sitemap.settings');
        Route::post('/sitemap-settings', 'GeneralSettingsController@update_sitemap_settings');
        Route::post('/sitemap-settings/delete', 'GeneralSettingsController@delete_sitemap_settings')->name('admin.general.sitemap.settings.delete');


        Route::get('/regenerate','MediaUploadController@regenerate_media_images');
        //Reading
        Route::get('/reading','GeneralSettingsController@reading')->name('admin.general.reading');
        Route::post('/reading','GeneralSettingsController@update_reading');
        //Navbar Global Variant
        Route::get('/global-variant-navbar','GeneralSettingsController@global_variant_navbar')->name('admin.general.global.variant.navbar');
        Route::post('/global-variant-navbar','GeneralSettingsController@update_global_variant_navbar');
        //Footer Global Variant
        Route::get('/global-variant-footer','GeneralSettingsController@global_variant_footer')->name('admin.general.global.variant.footer');
        Route::post('/global-variant-footer','GeneralSettingsController@update_global_variant_footer');
        //site-identity
        Route::get('/site-identity','GeneralSettingsController@site_identity')->name('admin.general.site.identity');
        Route::post('/site-identity','GeneralSettingsController@update_site_identity');
        //basic-settings
        Route::get('/basic-settings','GeneralSettingsController@basic_settings')->name('admin.general.basic.settings');
        Route::post('/basic-settings','GeneralSettingsController@update_basic_settings');
        //color-settings
        Route::get('/color-settings','GeneralSettingsController@color_settings')->name('admin.general.color.settings');
        Route::post('/color-settings','GeneralSettingsController@update_color_settings');
        //seo settings
        Route::get('/seo-settings','GeneralSettingsController@seo_settings')->name('admin.general.seo.settings');
        Route::post('/seo-settings','GeneralSettingsController@update_seo_settings');
        //scripts settings
        Route::get('/scripts','GeneralSettingsController@scripts_settings')->name('admin.general.scripts.settings');
        Route::post('/scripts','GeneralSettingsController@update_scripts_settings');
        //email template settings
        Route::get('/email-template','GeneralSettingsController@email_template_settings')->name('admin.general.email.template');
        Route::post('/email-template','GeneralSettingsController@update_email_template_settings');
        //email settings
        Route::get('/email-settings','GeneralSettingsController@email_settings')->name('admin.general.email.settings');
        Route::post('/email-settings','GeneralSettingsController@update_email_settings');
        //typography settings
        Route::get('/typography-settings','GeneralSettingsController@typography_settings')->name('admin.general.typography.settings');
        Route::post('/typography-settings','GeneralSettingsController@update_typography_settings');
        Route::post('typography-settings/single','GeneralSettingsController@get_single_font_variant')->name('admin.general.typography.single');
        //smtp settings
        Route::get('/smtp-settings','GeneralSettingsController@smtp_settings')->name('admin.general.smtp.settings');
        Route::post('/smtp-settings','GeneralSettingsController@update_smtp_settings');
        Route::post('/smtp-settings/test', 'GeneralSettingsController@test_smtp_settings')->name('admin.general.smtp.settings.test');
        //page settings
        Route::get('/page-settings','GeneralSettingsController@page_settings')->name('admin.general.page.settings');
        Route::post('/page-settings','GeneralSettingsController@update_page_settings');
        //payment gateway
        Route::get('/payment-settings', 'GeneralSettingsController@payment_settings')->name('admin.general.payment.settings');
        Route::post('/payment-settings', 'GeneralSettingsController@update_payment_settings');
        //custom css
        Route::get('/custom-css','GeneralSettingsController@custom_css_settings')->name('admin.general.custom.css');
        Route::post('/custom-css','GeneralSettingsController@update_custom_css_settings');
        //custom js
        Route::get('/custom-js','GeneralSettingsController@custom_js_settings')->name('admin.general.custom.js');
        Route::post('/custom-js','GeneralSettingsController@update_custom_js_settings');
        //gdpr-settings
        Route::get('/gdpr-settings','GeneralSettingsController@gdpr_settings')->name('admin.general.gdpr.settings');
        Route::post('/gdpr-settings','GeneralSettingsController@update_gdpr_cookie_settings');
        //license-setting
        Route::get('/license-setting','GeneralSettingsController@license_settings')->name('admin.general.license.settings');
        Route::post('/license-setting','GeneralSettingsController@update_license_settings');
        //cache settings
        Route::get('/cache-settings','GeneralSettingsController@cache_settings')->name('admin.general.cache.settings');
        Route::post('/cache-settings','GeneralSettingsController@update_cache_settings');
        
        //database upgrade
        Route::get('/database-upgrade', 'GeneralSettingsController@database_upgrade')->name('admin.general.database.upgrade');
        Route::post('/database-upgrade', 'GeneralSettingsController@database_upgrade_post');

        //rss feed
        Route::get('/rss-settings', 'GeneralSettingsController@rss_feed_settings')->name('admin.general.rss.feed.settings');
        Route::post('/rss-settings', 'GeneralSettingsController@update_rss_feed_settings');
    });


    //widget manage
    Route::get('/widgets','WidgetsController@index')->name('admin.widgets');
    Route::post('/widgets/create','WidgetsController@new_widget')->name('admin.widgets.new');
    Route::post('/widgets/markup','WidgetsController@widget_markup')->name('admin.widgets.markup');
    Route::post('/widgets/update','WidgetsController@update_widget')->name('admin.widgets.update');
    Route::post('/widgets/update/order','WidgetsController@update_order_widget')->name('admin.widgets.update.order');
    Route::post('/widgets/delete','WidgetsController@delete_widget')->name('admin.widgets.delete');


    //TOPBAR SETTINGS
    Route::get('/topbar-settings',"TopbarController@index")->name('admin.topbar.settings');
    Route::post('/topbar-settings','TopbarController@store');
    Route::post('/update-topbar-settings','TopbarController@update')->name('admin.topbar.update');
    Route::post('/delete-topbar-settings/{id}','TopbarController@delete')->name('admin.topbar.delete');
    Route::post('/topbar-settings/bulk-action','TopbarController@bulk_action')->name('admin.topbar.bulk.action');

    //Header Banner Settings
    Route::get('/header-banner-settings','TopbarController@header_banner_settings')->name('admin.header.banner.settings');
    Route::post('/header-banner-settings','TopbarController@update_header_banner_settings');


    //Leftbar Settings
    Route::get('/leftbar-settings','TopbarController@leftbar_settings')->name('admin.leftbar.settings');
    Route::post('/leftbar-settings','TopbarController@update_leftbar_settings');

    //TOPBAR SOCIAL MEDIA
    Route::get('/topbar','TopBarController@index')->name('admin.topbar');
    Route::post('/topbar/new-support-info','TopbarController@new_support_info')->name('admin.new.support.info');
    Route::post('/topbar/update-support-info','TopbarController@update_support_info')->name('admin.update.support.info');
    Route::post('/topbar/delete-support-info/{id}','TopbarController@delete_support_info')->name('admin.delete.support.info');
    Route::post('/topbar/new-social-item','TopbarController@new_social_item')->name('admin.new.social.item');
    Route::post('/topbar/update-social-item','TopbarController@update_social_item')->name('admin.update.social.item');
    Route::post('/topbar/delete-social-item/{id}','TopbarController@delete_social_item')->name('admin.delete.social.item');


    //MENU MANAGE
    Route::get('/menu','MenuController@index')->name('admin.menu');
    Route::post('/new-menu','MenuController@store_new_menu')->name('admin.menu.new');
    Route::get('/menu-edit/{id}','MenuController@edit_menu')->name('admin.menu.edit');
    Route::post('/menu-update/{id}','MenuController@update_menu')->name('admin.menu.update');
    Route::post('/menu-delete/{id}','MenuController@delete_menu')->name('admin.menu.delete');
    Route::post('/menu-default/{id}','MenuController@set_default_menu')->name('admin.menu.default');
    Route::post('/mega-menu', 'MenuController@mega_menu_item_select_markup')->name('admin.mega.menu.item.select.markup');
    //404 page manage
    Route::get('404-page-manage','Error404PageManage@error_404_page_settings')->name('admin.404.page.settings');
    Route::post('404-page-manage','Error404PageManage@update_error_404_page_settings');
    // maintains page
    Route::get('/maintains-page/settings','MaintainsPageController@maintains_page_settings')->name('admin.maintains.page.settings');
    Route::post('/maintains-page/settings','MaintainsPageController@update_maintains_page_settings');

    /*----------------------------------------------------------------------------------------------------------------------------
    | ADMIN USER ROLE MANAGE
    |----------------------------------------------------------------------------------------------------------------------------*/
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/all','AdminRoleManageController@all_user')->name('admin.all.user');
        Route::get('/new','AdminRoleManageController@new_user')->name('admin.new.user');
        Route::post('/new','AdminRoleManageController@new_user_add');
        Route::get('/user-edit/{id}','AdminRoleManageController@user_edit')->name('admin.user.edit');
        Route::post('/user-update','AdminRoleManageController@user_update')->name('admin.user.update');
        Route::post('/user-password-change','AdminRoleManageController@user_password_change')->name('admin.user.password.change');
        Route::post('/delete-user/{id}','AdminRoleManageController@new_user_delete')->name('admin.delete.user');
        /*----------------------------
         ALL ADMIN ROLE ROUTES
        -----------------------------*/
        Route::get('/role','AdminRoleManageController@all_admin_role')->name('admin.all.admin.role');
        Route::get('/role/new','AdminRoleManageController@new_admin_role_index')->name('admin.role.new');
        Route::post('/role/new','AdminRoleManageController@store_new_admin_role');
        Route::get('/role/edit/{id}','AdminRoleManageController@edit_admin_role')->name('admin.user.role.edit');
        Route::post('/role/update','AdminRoleManageController@update_admin_role')->name('admin.user.role.update');
        Route::post('/role/delete/{id}','AdminRoleManageController@delete_admin_role')->name('admin.user.role.delete');
    });


    //Frontend User  management
    Route::get('/frontend/new-user','FrontendUserManageController@new_user')->name('admin.frontend.new.user');
    Route::post('/frontend/new-user','FrontendUserManageController@new_user_add');
    Route::post('/frontend/user-update','FrontendUserManageController@user_update')->name('admin.frontend.user.update');
    Route::post('/frontend/user-password-chnage','FrontendUserManageController@user_password_change')->name('admin.frontend.user.password.change');
    Route::post('/frontend/delete-user/{id}','FrontendUserManageController@new_user_delete')->name('admin.frontend.delete.user');
    Route::get('/frontend/all-user','FrontendUserManageController@all_user')->name('admin.all.frontend.user');
    Route::post('/frontend/all-user/bulk-action','FrontendUserManageController@bulk_action')->name('admin.all.frontend.user.bulk.action');
    Route::post('/frontend/all-user/email-status','FrontendUserManageController@email_status')->name('admin.all.frontend.user.email.status');
    Route::post('/user/permission-settings','FrontendUserManageController@update_permission_settings')->name('admin.frontend.user.permission.settings');

    //admin settings
    Route::get('/settings','AdminDashboardController@admin_settings')->name('admin.profile.settings');
    Route::get('/profile-update','AdminDashboardController@admin_profile')->name('admin.profile.update');
    Route::post('/profile-update','AdminDashboardController@admin_profile_update');
    Route::get('/password-change','AdminDashboardController@admin_password')->name('admin.password.change');
    Route::post('/password-change','AdminDashboardController@admin_password_chagne');

    //language
    Route::get('/languages','LanguageController@index')->name('admin.languages');
    Route::get('/languages/words/all/{id}','LanguageController@all_edit_words')->name('admin.languages.words.all');
    Route::post('/languages/words/update/{id}','LanguageController@update_words')->name('admin.languages.words.update');
    Route::post('/languages/new','LanguageController@store')->name('admin.languages.new');
    Route::post('/languages/update','LanguageController@update')->name('admin.languages.update');
    Route::post('/languages/delete/{id}','LanguageController@delete')->name('admin.languages.delete');
    Route::post('/languages/default/{id}','LanguageController@make_default')->name('admin.languages.default');
    Route::post('/languages/clone','LanguageController@clone_languages')->name('admin.languages.clone');
    Route::post('/add-new-string', 'LanguageController@add_new_string')->name('admin.languages.add.string');
    Route::post('/languages/regenerate-source-text','LanguageController@regenerate_source_text')->name('admin.languages.regenerate.source.texts');

/*==============================================
       FORM BUILDER ROUTES
==============================================*/
Route::prefix('form-builder')->group(function () {

    /*-------------------------
        CUSTOM FORM BUILDER
    --------------------------*/
    Route::get('/all', 'CustomFormBuilderController@all')->name('admin.form.builder.all');
    Route::post('/new', 'CustomFormBuilderController@store')->name('admin.form.builder.store');
    Route::get('/edit/{id}', 'CustomFormBuilderController@edit')->name('admin.form.builder.edit');
    Route::post('/update', 'CustomFormBuilderController@update')->name('admin.form.builder.update');
    Route::post('/delete/{id}', 'CustomFormBuilderController@delete')->name('admin.form.builder.delete');
    Route::post('/bulk-action', 'CustomFormBuilderController@bulk_action')->name('admin.form.builder.bulk.action');

    /*-------------------------
     GET IN TOUCH FORM ROUTES
    --------------------------*/
    Route::get('/get-in-touch', 'FormBuilderController@get_in_touch_form_index')->name('admin.form.builder.get.in.touch');
    Route::post('/get-in-touch', 'FormBuilderController@update_get_in_touch_form');

    /*-------------------------
      CONTACT FORM ROUTES
      --------------------------*/
    Route::get('/contact-form', 'FormBuilderController@contact_form_index')->name('admin.form.builder.contact');
    Route::post('/contact-form', 'FormBuilderController@update_contact_form');

});

//}); //demo middleware

    //Chart Data
    Route::post('/visited-url', 'AdminDashboardController@get_visited_url_data')->name('admin.home.visited.url.data');
    Route::post('/visited/device', 'AdminDashboardController@get_chart_data_device')->name('admin.home.chart.data.by.device');
    Route::post('/visited/os', 'AdminDashboardController@get_chart_data_os')->name('admin.home.chart.data.by.os');
    Route::post('/visited/country', 'AdminDashboardController@get_chart_data_country')->name('admin.home.chart.data.by.country');
    Route::post('/visited/browser', 'AdminDashboardController@get_chart_data_browser')->name('admin.home.chart.data.by.browser');

    // media upload routes end
    Route::post('/media-upload/all','MediaUploadController@all_upload_media_file')->name('admin.upload.media.file.all');
    Route::post('/media-upload','MediaUploadController@upload_media_file')->name('admin.upload.media.file');
    Route::post('/media-upload/alt','MediaUploadController@alt_change_upload_media_file')->name('admin.upload.media.file.alt.change');
    
    // media upload routes for restrict user in demo mode
    Route::post('/media-upload/loadmore', 'MediaUploadController@get_image_for_loadmore')->name('admin.upload.media.file.loadmore');



