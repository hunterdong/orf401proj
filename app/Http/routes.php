<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
/**
 * Do not edit anything below this un-till unless you know what you are doing.
 * If you modify anything your site might go corrupted.
 * Thanks
 */
// Patterns
Route::pattern('id', '[0-9]+');

Route::get('/', ['as' => 'home', 'middleware' => 'guest', 'uses' => 'HomeController@getIndex']);
Route::get('gallery', ['as' => 'gallery', 'uses' => 'GalleryController@getIndex']);
Route::get('image/{id}/{slug?}', ['as' => 'image', 'uses' => 'ImageController@getIndex']);
Route::get('user/{username}', ['as' => 'user', 'uses' => 'UserController@getUser']);
Route::get('user/{username}/favorites', ['as' => 'users.favorites', 'uses' => 'UserController@getFavorites']);
Route::get('user/{username}/followers', ['as' => 'users.followers', 'uses' => 'UserController@getFollowers']);
Route::get('user/{username}/rss', ['as' => 'users.rss', 'uses' => 'UserController@getRss']);
Route::get('users', ['as' => 'users', 'uses' => 'UserController@getAll']);
Route::get('category/{category}', ['as' => 'category', 'uses' => 'CategoryController@getIndex']);
Route::get('category/{category}/rss', 'CategoryController@getRss');
Route::get('tag/{tag}', ['as' => 'tags', 'uses' => 'TagsController@getIndex']);
Route::get('tag/{tag}/rss', 'TagsController@getRss');
Route::get('notifications', ['as' => 'notifications', 'uses' => 'UserController@getNotifications']);
Route::get('tos', ['as' => 'tos', 'uses' => 'PolicyController@getTos']);
Route::get('privacy', ['as' => 'privacy', 'uses' => 'PolicyController@getPrivacy']);
Route::get('faq', ['as' => 'faq', 'uses' => 'PolicyController@getFaq']);
Route::get('about', ['as' => 'about', 'uses' => 'PolicyController@getAbout']);
Route::get('search', ['as' => 'search', 'uses' => 'GalleryController@search']);
Route::get('featured', ['as' => 'images.featured', 'uses' => 'GalleryController@featured']);
Route::get('popular', ['as' => 'images.popular', 'uses' => 'GalleryController@mostPopular']);
Route::get('most/viewed', ['as' => 'images.most.viewed', 'uses' => 'GalleryController@mostViewed']);
Route::get('most/commented', ['as' => 'images.most.commented', 'uses' => 'GalleryController@mostCommented']);
Route::get('most/favorites', ['as' => 'images.most.favorites', 'uses' => 'GalleryController@mostFavorited']);
Route::get('most/downloads', ['as' => 'images.most.downloads', 'uses' => 'GalleryController@mostDownloaded']);
Route::get('blogs', ['as' => 'blogs', 'uses' => 'BlogController@getIndex']);
Route::get('blog/{id}/{slug}', ['as' => 'blog', 'uses' => 'BlogController@getBlog']);
Route::get('lang/{lang?}', 'PolicyController@switchLang');
Route::post('queue/receive', 'PolicyController@queue');

/**
 * Guest only visit this section
 */
Route::group(['middleware' => 'guest'], function () {
    Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@getLogin']);
    Route::get('auth/{provider}', 'Auth\LoginController@getSocial');
    Route::get('auth/{provider}/callback', 'Auth\LoginController@getSocialCallback');
    Route::get('registration/{provider}', 'Auth\RegistrationController@getSocialRegister');
    Route::get('registration', ['as' => 'registration', 'uses' => 'Auth\RegistrationController@getIndex']);
    Route::get('registration/activate/{username}/{code}', 'Auth\RegistrationController@validateUser');
    Route::get('password/email', ['as' => 'password.reminder', 'uses' => 'Auth\PasswordController@getEmail']);
    Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
});

/**
 * Guest Post form with csrf protection
 */
Route::group(['middleware' => 'csrf:guest'], function () {
    Route::post('login', 'Auth\LoginController@postLogin');
    Route::post('registration/{provider}', 'Auth\RegistrationController@postSocialRegister');
    Route::post('password/email', 'Auth\PasswordController@postEmail');
    Route::post('password/reset/{token}', 'Auth\PasswordController@postReset');
    Route::post('registration', 'Auth\RegistrationController@postIndex');
});

/*
 * Ajax post
 */
Route::group(['middleware' => 'auth'], function () {
//    Ajax Routes
    Route::post('favorite', 'ImageController@postFavorite');
    Route::post('follow', 'UserController@follow');
    Route::post('reply', 'ReplyController@postReply');
    Route::post('votecomment', 'CommentController@vote');
    Route::post('votereply', 'ReplyController@vote');
    Route::post('deletecomment', 'CommentController@postDeleteComment');
    Route::post('deletereply', 'ReplyController@delete');
    Route::post('upload', 'UploadController@postUpload');

//    Non-Ajax Routes
    Route::get('image/{id}/{slug?}/edit', ['as' => 'images.edit', 'uses' => 'ImageController@getEdit']);
    Route::get('upload', ['as' => 'images.upload', 'uses' => 'UploadController@getIndex']);
    Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@getLogout']);
    Route::get('feeds', ['as' => 'users.feeds', 'uses' => 'UserController@getFeeds']);
    Route::get('user/{username}/following', ['as' => 'users.following', 'uses' => 'UserController@getFollowing']);
    Route::get('image/{any}/download', ['as' => 'images.download', 'uses' => 'ImageController@download']);
    Route::get('settings', ['as' => 'users.settings', 'uses' => 'UserController@getSettings']);
    Route::get('image/{id}/{slug?}/delete', ['as' => 'images.delete', 'uses' => 'ImageController@delete']);
    Route::get('image/{id}/{slug?}/report', ['as' => 'images.report', 'uses' => 'ReportController@getReport']);
    Route::get('user/{username}/report', ['as' => 'user.report', 'uses' => 'ReportController@getReport']);
});

/**
 * Post Sections CSRF + AUTH both
 */
Route::group(['middleware' => 'csrf:auth'], function () {
    Route::post('image/{id}/{slug?}', 'CommentController@postComment');
    Route::post('image/{id}/{slug?}/edit', 'ImageController@postEdit');
    Route::post('settings/changepassword', 'UserController@postChangePassword');
    Route::post('settings/updateprofile', 'UserController@postUpdateProfile');
    Route::post('settings/mailsettings', 'UserController@postMailSettings');
    Route::post('settings/updateavatar', 'UserController@postUpdateAvatar');
    Route::post('image/{id}/{slug?}/report', 'ReportController@postReportImage');
    Route::post('user/{username}/report', 'ReportController@postReportUser');
});

/**
 * Admin section users with admin privileges can access this area
 */
Route::group(['middleware' => 'admin', 'namespace' => 'Admin'], function () {
    Route::get('admin', ['as' => 'admin', 'uses' => 'IndexController@getIndex']);

// User Manager
    Route::get('admin/users', ['as' => 'admin.users', 'uses' => 'User\UserController@getIndex']);
    Route::get('admin/users/data', ['as' => 'admin.users.data', 'uses' => 'User\UserController@getData']);
    Route::get('admin/users/{id}/edit', ['as' => 'admin.users.edit', 'uses' => 'User\UpdateController@getEdit']);
    Route::get('admin/users/add', ['as' => 'admin.users.add', 'uses' => 'User\UserController@getAddUser']);
    Route::post('admin/users/add', ['as' => 'admin.users.add', 'uses' => 'User\UpdateController@postAddUser']);
    Route::post('admin/users/{id}/edit', ['as' => 'admin.users.edit', 'uses' => 'User\UpdateController@postEdit']);
    Route::post('admin/users/approve', ['as' => 'admin.users.approve', 'uses' => 'User\UpdateController@postApprove']);

// Comment Manger
    Route::get('admin/comments', ['as' => 'admin.comments', 'uses' => 'Comment\CommentController@getIndex']);
    Route::get('admin/comments/data', ['as' => 'admin.comments.data', 'uses' => 'Comment\CommentController@getData']);
    Route::get('admin/comments/{id}/edit', ['as' => 'admin.comments.edit', 'uses' => 'Comment\CommentController@getEdit']);
    Route::post('admin/comments/{id}/edit', ['as' => 'admin.comments.edit', 'uses' => 'Comment\CommentController@postEdit']);

// Image Manger
    Route::get('admin/images', ['as' => 'admin.images', 'uses' => 'Image\ImageController@getIndex']);
    Route::get('admin/images/data', ['as' => 'admin.images.data', 'uses' => 'Image\ImageController@getData']);
    Route::get('admin/images/{id}/edit', ['as' => 'admin.images.edit', 'uses' => 'Image\UpdateController@getEdit']);
    Route::post('admin/images/{id}/edit', ['as' => 'admin.images.edit', 'uses' => 'Image\UpdateController@postEdit']);
    Route::post('admin/images/approve', ['as' => 'admin.images.approve', 'uses' => 'Image\UpdateController@approve']);
    Route::post('admin/images/clearcache', ['as' => 'admin.images.clearcache', 'uses' => 'Image\UpdateController@clearCache']);
    Route::get('admin/images/bulkupload', ['as' => 'admin.images.bulkupload', 'uses' => 'Image\ImageController@getBulkUpload']);
    Route::post('admin/images/bulkupload', ['as' => 'admin.images.bulkupload', 'uses' => 'Image\UpdateController@postBulkUpload']);

// Category
    Route::get('admin/categories', ['as' => 'admin.categories', 'uses' => 'Category\CategoryController@index']);
    Route::post('admin/categories', 'Category\CategoryController@createCategory');
    Route::post('admin/categories/reorder', 'Category\CategoryController@reorderCategory');
    Route::post('admin/categories/update', 'Category\CategoryController@updateCategory');

// Site Settings
    Route::get('admin/settings/details', ['as' => 'admin.settings.details', 'uses' => 'Settings\SettingsController@getSiteDetails']);
    Route::post('admin/settings/details', ['as' => 'admin.settings.details', 'uses' => 'Settings\UpdateController@postSiteDetails']);
    Route::get('admin/settings/limits', ['as' => 'admin.settings.limits', 'uses' => 'Settings\SettingsController@getLimitSettings']);
    Route::post('admin/settings/limits', ['as' => 'admin.settings.limits', 'uses' => 'Settings\UpdateController@postLimitSettings']);
    Route::get('admin/settings/cache', ['as' => 'admin.settings.cache', 'uses' => 'Settings\SettingsController@getCacheSettings']);
    Route::post('admin/settings/cache', ['as' => 'admin.settings.cache', 'uses' => 'Settings\UpdateController@postCacheSettings']);
    Route::get('admin/settings/sitemap', ['as' => 'admin.settings.sitemap', 'uses' => 'Settings\UpdateController@updateSiteMap']);

// Blogs
    Route::get('admin/blogs', ['as' => 'admin.blogs', 'uses' => 'Blog\BlogController@getIndex']);
    Route::get('admin/blogs/data', ['as' => 'admin.blogs.data', 'uses' => 'Blog\BlogController@getData']);
    Route::get('admin/blogs/create', ['as' => 'admin.blogs.create', 'uses' => 'Blog\BlogController@getCreate']);
    Route::post('admin/blogs/create', ['as' => 'admin.blogs.create', 'uses' => 'Blog\BlogController@postCreate']);
    Route::get('admin/blogs/{id}', ['as' => 'admin.blogs.edit', 'uses' => 'Blog\BlogController@getEdit']);
    Route::post('admin/blogs/{id}', ['as' => 'admin.blogs.edit', 'uses' => 'Blog\BlogController@postEdit']);

// Reports
    Route::get('admin/reports', ['as' => 'admin.reports', 'uses' => 'Report\ReportController@getReports']);
    Route::get('admin/reports/data', ['as' => 'admin.reports.data', 'uses' => 'Report\ReportController@getData']);
    Route::get('admin/reports/{id}', ['as' => 'admin.reports.read', 'uses' => 'Report\ReportController@getReadReport']);
});
