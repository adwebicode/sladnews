<?php
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::feeds();

/*----------------------------------------------------------------------------------------------------------------------------
| FRONTEND
|----------------------------------------------------------------------------------------------------------------------------*/
Route::group(['middleware' =>['setlang','globalVariable','maintains_mode','banned']],function (){
/*----------------------------------------------------------------------------------------------------------------------------
| FRONTEND ROUTES
|----------------------------------------------------------------------------------------------------------------------------*/
Route::get('/','FrontendController@index')->name('homepage');
Route::get('/dark-mode-toggle', 'FrontendController@dark_mode_toggle')->name('frontend.dark.mode.toggle');
Route::post('poll/vote/store','FrontendController@poll_vote_store')->name('frontend.poll.vote.store');
Route::get('home/advertisement/click/store','FrontendController@home_advertisement_click_store')->name('frontend.home.advertisement.click.store');
Route::get('home/advertisement/impression/store','FrontendController@home_advertisement_impression_store')->name('frontend.home.advertisement.impression.store');

//Newsletter
Route::get('/subscriber/email-verify/{token}','FrontendController@subscriber_verify')->name('subscriber.verify');
Route::post('/subscribe-newsletter','FrontendController@subscribe_newsletter')->name('frontend.subscribe.newsletter');

/*------------------------------
    SOCIAL LOGIN CALLBACK
------------------------------*/
    Route::group(['prefix' => 'facebook','namespace'=>'Frontend'],function (){
        Route::get('callback','SocialLoginController@facebook_callback')->name('facebook.callback');
        Route::get('redirect','SocialLoginController@facebook_redirect')->name('login.facebook.redirect');
    });
    Route::group(['prefix' => 'google','namespace'=>'Frontend'],function (){
        Route::get('callback','SocialLoginController@google_callback')->name('google.callback');
        Route::get('redirect','SocialLoginController@google_redirect')->name('login.google.redirect');
    });

/*----------------------------------------
  FRONTEND: CUSTOM FORM BUILDER ROUTES
-----------------------------------------*/
Route::post('submit-custom-form', 'FrontendFormController@custom_form_builder_message')->name('frontend.form.builder.custom.submit');
    /*----------------------------------------------------------------------------------------------------------------------------
    | USER DASHBOARD
    |----------------------------------------------------------------------------------------------------------------------------*/
    Route::prefix('user-home')->middleware(['userEmailVerify','setlang','globalVariable','banned'])->group(function (){

    Route::get('/', 'UserDashboardController@user_index')->name('user.home')->middleware('user_post');
    Route::get('/download/file/{id}', 'UserDashboardController@download_file')->name('user.dashboard.download.file');
    Route::get('/change-password', 'UserDashboardController@change_password')->name('user.home.change.password');
    Route::get('/edit-profile', 'UserDashboardController@edit_profile')->name('user.home.edit.profile');
    Route::post('/profile-update', 'UserDashboardController@user_profile_update')->name('user.profile.update');
    Route::post('/password-change', 'UserDashboardController@user_password_change')->name('user.password.change');

    // media upload routes for User
    Route::group(['namespace'=>'User'],function(){
        Route::post('/media-upload/all','MediaUploadController@all_upload_media_file')->name('web.upload.media.file.all');
        Route::post('/media-upload','MediaUploadController@upload_media_file')->name('web.upload.media.file');
        Route::post('/media-upload/alt','MediaUploadController@alt_change_upload_media_file')->name('web.upload.media.file.alt.change');
        Route::post('/media-upload/delete','MediaUploadController@delete_upload_media_file')->name('web.upload.media.file.delete');
        Route::post('/media-upload/loadmore', 'MediaUploadController@get_image_for_loadmore')->name('web.upload.media.file.loadmore');
    });


    //User Blog Post
    Route::group(['namespace'=>'User','prefix'=>'user-posts', 'middleware'=>'user_post'],function(){
        
        Route::group(['middleware' => 'demo' ],function(){
            Route::get('/', 'UserPostController@user_index')->name('user.blog');
            Route::get('/new', 'UserPostController@user_new_blog')->name('user.blog.new');
            Route::get('/edit/{id}', 'UserPostController@user_edit_blog')->name('user.blog.edit');
            Route::post('/update/{id}', 'UserPostController@user_update_blog')->name('user.blog.update');
        });
       
        
        Route::post('/new', 'UserPostController@user_store_new_blog');
        
        Route::post('/clone', 'UserPostController@user_clone_blog')->name('user.blog.clone');
        Route::post('/delete/all/lang/{id}', 'UserPostController@user_delete_blog_all_lang')->name('user.blog.delete.all.lang');
        //Trashed & Restore
        Route::get('/trashed', 'UserPostController@trashed_blogs')->name('user.blog.trashed');
        Route::get('/trashed/restore/{id}', 'UserPostController@user_restore_trashed_blog')->name('user.blog.trashed.restore');
        Route::post('/trashed/delete/{id}', 'UserPostController@user_delete_trashed_blog')->name('user.blog.trashed.delete');
    });

});

    /*----------------------------------------------------------------------------------------------------------------------------
    | USER LOGIN - REGISTRATION
    |----------------------------------------------------------------------------------------------------------------------------*/
    Route::get('/login','Auth\LoginController@showLoginForm')->name('user.login');
    Route::post('/ajax-login','FrontendController@ajax_login')->name('user.ajax.login');
    Route::post('/login','Auth\LoginController@login');
    Route::get('/login/forget-password','FrontendController@showUserForgetPasswordForm')->name('user.forget.password');
    Route::get('/login/reset-password/{user}/{token}','FrontendController@showUserResetPasswordForm')->name('user.reset.password');
    Route::post('/login/reset-password','FrontendController@UserResetPassword')->name('user.reset.password.change');
    Route::post('/login/forget-password','FrontendController@sendUserForgetPasswordMail');
    Route::post('/logout','Auth\LoginController@logout')->name('user.logout');
    Route::get('/user-logout','FrontendController@user_logout')->name('frontend.user.logout');
    //user register
    Route::post('/register','Auth\RegisterController@register');
    Route::get('/register','Auth\RegisterController@showRegistrationForm')->name('user.register');
    //user email verify
    Route::get('/user/email-verify','UserDashboardController@user_email_verify_index')->name('user.email.verify');
    Route::get('/user/resend-verify-code','UserDashboardController@reset_user_email_verify_code')->name('user.resend.verify.mail');
    Route::post('/user/email-verify','UserDashboardController@user_email_verify');
    Route::post('/package-user/generate-invoice','FrontendController@generate_package_invoice')->name('frontend.package.invoice.generate');

});


/*----------------------------------------------------------------------------------------------------------------------------
| LANGUAGE CHANGE
|----------------------------------------------------------------------------------------------------------------------------*/
Route::get('/lang','FrontendController@lang_change')->name('frontend.langchange');
Route::get('/subscriber/email-verify/{token}','FrontendController@subscriber_verify')->name('subscriber.verify');

/*----------------------------------------------------------------------------------------------------------------------------
| ADMIN LOGIN
|----------------------------------------------------------------------------------------------------------------------------*/
Route::middleware(['setlang'])->group(function (){
    Route::get('/login/admin','Auth\LoginController@showAdminLoginForm')->name('admin.login');
    Route::get('/login/admin/forget-password','FrontendController@showAdminForgetPasswordForm')->name('admin.forget.password');
    Route::get('/login/admin/reset-password/{user}/{token}','FrontendController@showAdminResetPasswordForm')->name('admin.reset.password');
    Route::post('/login/admin/reset-password','FrontendController@AdminResetPassword')->name('admin.reset.password.change');
    Route::post('/login/admin/forget-password','FrontendController@sendAdminForgetPasswordMail');
    Route::get('/logout/admin','AdminDashboardController@adminLogout')->name('admin.logout');
    Route::post('/login/admin','Auth\LoginController@adminLogin');
});

Route::group(['middleware' =>['setlang','globalVariable','maintains_mode','banned']],function () {
    Route::get('/{slug}', 'FrontendController@dynamic_single_page')->name('frontend.dynamic.page');
});

