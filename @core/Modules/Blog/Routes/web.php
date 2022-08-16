<?php

/*----------------------------------------------------------------------------------------------------------------------------
|                                                      BACKEND ROUTES
|----------------------------------------------------------------------------------------------------------------------------*/

//Blogs
Route::group(['prefix'=>'admin-home'],function() {

    Route::group(['prefix'=>'blog'],function() {
        Route::get('/', 'BlogController@index')->name('admin.blog');
        Route::get('/new', 'BlogController@new_blog')->name('admin.blog.new');
        Route::post('/new', 'BlogController@store_new_blog');
        Route::get('/get/tags','BlogController@get_tags_by_ajax')->name('admin.get.tags.by.ajax');
        Route::get('/edit/{id}', 'BlogController@edit_blog')->name('admin.blog.edit');
        Route::post('/update/{id}', 'BlogController@update_blog')->name('admin.blog.update');
        Route::post('/clone', 'BlogController@clone_blog')->name('admin.blog.clone');
        Route::post('/delete/all/lang/{id}', 'BlogController@delete_blog_all_lang')->name('admin.blog.delete.all.lang');
        Route::post('/bulk-action', 'BlogController@bulk_action_blog')->name('admin.blog.bulk.action');
        Route::get('/view/analytics/{id}', 'BlogController@view_analytics')->name('admin.blog.view.analytics');
        Route::post('/view/data/monthly', 'BlogController@view_data_monthly')->name('admin.blog.view.data.monthly');

        //Blog Comments Route
        Route::get('/comments/view/{id}', 'BlogController@view_comments')->name('admin.blog.comments.view');
        Route::post('/comments/delete/all/lang/{id}', 'BlogController@delete_all_comments')->name('admin.blog.comments.delete.all.lang');
        Route::post('/comments/bulk-action', 'BlogController@bulk_action_comments')->name('admin.blog.comments.bulk.action');
        
        //Blog Status Approve
        Route::get('/blog/approve/{id}', 'BlogController@approveBlog')->name('admin.blog.approve');

        //Trashed & Restore
        Route::get('/trashed', 'BlogController@trashed_blogs')->name('admin.blog.trashed');
        Route::get('/trashed/restore/{id}', 'BlogController@restore_trashed_blog')->name('admin.blog.trashed.restore');
        Route::post('/trashed/delete/{id}', 'BlogController@delete_trashed_blog')->name('admin.blog.trashed.delete');
        Route::post('/trashed/bulk-action', 'BlogController@trashed_bulk_action_blog')->name('admin.blog.trashed.bulk.action');

        //Single Page Settings
        Route::get('/single-settings', 'BlogController@blog_single_page_settings')->name('admin.blog.single.settings');
        Route::post('/single-settings', 'BlogController@update_blog_single_page_settings');

        //Others Page Settings
        Route::get('/others-settings', 'BlogController@blog_others_page_settings')->name('admin.blog.others.settings');
        Route::post('/others-settings', 'BlogController@update_blog_others_page_settings');
        Route::post('/blog-approve', 'BlogController@blog_approve')->name('admin.blog.approve');
        //Details Page Variant
        Route::get('/details-variant-settings', 'BlogController@details_variant')->name('admin.blog.details.variant.settings');
        Route::post('/details-variant-settings', 'BlogController@update_details_variant');
    });


    Route::group(['prefix' => 'rss'],function(){

        //ALL RSS FEED INFO
        Route::get('/feed-all-info','BlogController@rss_feed_all_info')->name('admin.blog.rss.feed.all.info');
        Route::post('/feed-all-info','BlogController@add_new_rss_feed');
        Route::post('/feed-all-info-update','BlogController@update_rss_feed')->name('admin.blog.rss.feed.all.info.update');
        Route::post('/feed-all-info-delete/{id}','BlogController@rss_feed_delete')->name('admin.blog.rss.feed.all.info.delete');
        Route::post('/feed-all-info-bulk-action','BlogController@rss_feed_bulk_action')->name('admin.blog.rss.feed.all.info.bulk.action');

        //RSS FEED IMPORT ROUTES SINGLE
        Route::group(['prefix'=>'import'],function(){
            Route::get('/rss-feed','BlogController@rss_feed_page')->name('admin.blog.import.rss.feed');
            Route::post('/rss-feed','BlogController@rss_feed_store');
        });
    });


    //BACKEND BLOG CATEGORY AREA
    Route::group(['prefix'=>'blog-category'],function(){
        Route::get('/','BlogCategoryController@index')->name('admin.blog.category');
        Route::post('/store','BlogCategoryController@new_category')->name('admin.blog.category.store');
        Route::post('/update','BlogCategoryController@update_category')->name('admin.blog.category.update');
        Route::post('/delete/all/lang/{id}','BlogCategoryController@delete_category_all_lang')->name('admin.blog.category.delete.all.lang');
        Route::post('/bulk-action', 'BlogCategoryController@bulk_action')->name('admin.blog.category.bulk.action');
    });

    //BACKEND BLOG TAGS
    Route::group(['prefix'=>'blog-tags'],function(){
        Route::get('/','BlogTagsController@index')->name('admin.blog.tags');
        Route::post('/store','BlogTagsController@new_tags')->name('admin.blog.tags.store');
        Route::post('/update','BlogTagsController@update_tags')->name('admin.blog.tags.update');
        Route::post('/delete/all/lang/{id}','BlogTagsController@delete_tags_all_lang')->name('admin.blog.tags.delete.all.lang');
        Route::post('/bulk-action', 'BlogTagsController@bulk_action')->name('admin.blog.tags.bulk.action');
    });

});


/*----------------------------------------------------------------------------------------------------------------------------
|                                                      FRONTEND ROUTES
|----------------------------------------------------------------------------------------------------------------------------*/

//Blogs
$blog_page_slug = get_page_slug(get_static_option('blog_page'),'blog');

Route::group(['prefix' => $blog_page_slug,'namespace' => 'Frontend', 'middleware' => ['setlang','globalVariable','maintains_mode','banned']],function (){
    Route::get('/search','BlogController@blog_search_page')->name('frontend.blog.search');
    Route::get('/get/search','BlogController@blog_get_search')->name('frontend.blog.get.search');
    Route::get('/{slug}','BlogController@blog_single')->name('frontend.blog.single');
    Route::get('/category/{id}/{any?}','BlogController@category_wise_blog_page')->name('frontend.blog.category');
    Route::get('/tags/{any}','BlogController@tags_wise_blog_page')->name('frontend.blog.tags.page');
    Route::get('blog/autocomplete-search','BlogController@autocompleteSearch')->name('frontend.blog.autocomplete.search');
    Route::get('blog/autocomplete/search/tag/page','BlogController@auto_complete_search_tag_blogs');
    Route::get('/get/tags','BlogController@get_tags_by_ajax')->name('frontend.get.tags.by.ajax');
    Route::get('/get/blog/by/ajax','BlogController@get_blog_by_ajax')->name('frontend.get.blogs.by.ajax');

    Route::get('/gallery/category/{id}/{any?}','ImageGalleryController@category_wise_gallery_page')->name('frontend.gallery.category');
    Route::get('author/profile/{id}','BlogController@author_profile')->name('frontend.author.profile');
    Route::get('blog-by-{user}/{id}','BlogController@user_created_blogs')->name('frontend.user.created.blog');
    Route::get('user/blg-password','BlogController@user_blog_password')->name('frontend.user.blog.password');

    Route::post('/blog/comment/store','BlogController@blog_comment_store')->name('blog.comment.store');
    Route::post('blog/all/comment','BlogController@load_more_comments')->name('frontend.load.blog.comment.data');

});


