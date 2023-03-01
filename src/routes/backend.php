<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/***** Author Controller namespace ***/
use Sourcebit\Dprimecms\Http\Controllers\Author\ChangePasswordController;
use Sourcebit\Dprimecms\Http\Controllers\Author\UsersController;
use Sourcebit\Dprimecms\Http\Controllers\Author\RegisterController;
use Sourcebit\Dprimecms\Http\Controllers\Author\BaseSettingController;
use Sourcebit\Dprimecms\Http\Controllers\Author\LoginController;
use Sourcebit\Dprimecms\Http\Controllers\Author\PermissionController;
use Sourcebit\Dprimecms\Http\Controllers\Author\LogsController;
use Sourcebit\Dprimecms\Http\Controllers\Author\SystemController;



use Sourcebit\Dprimecms\Http\Controllers\Author\CategoriesController;
use Sourcebit\Dprimecms\Http\Controllers\Author\ArticlesController;
use Sourcebit\Dprimecms\Http\Controllers\Author\HomeItemsController;
use Sourcebit\Dprimecms\Http\Controllers\Author\MenuTypesController;
use Sourcebit\Dprimecms\Http\Controllers\Author\MenuController;
use Sourcebit\Dprimecms\Http\Controllers\Author\NewsletterController;
use Sourcebit\Dprimecms\Http\Controllers\Author\MediaManagerController;

use Sourcebit\Dprimecms\Http\Controllers\Author\TagsController;
use Sourcebit\Dprimecms\Http\Controllers\Author\ContactUsController;

use Sourcebit\Dprimecms\Http\Controllers\Author\AjaxJsonController;


use Sourcebit\Dprimecms\Http\Controllers\Author\NewAllRoutePermissionController;


Route::get('new-all-route-permission',[NewAllRoutePermissionController::class, 'store']);
Route::get('all-routes',[NewAllRoutePermissionController::class, 'getAllRoutes']);

/** Auth */
Route::group(['prefix' => 'author'], function (){
    Route::get('/login',[LoginController::class,'login']);
    Route::post('login',[LoginController::class,'loginAction']);
    Route::get('logout',[LoginController::class,'logout']);


    Route::middleware(['admin'])->group(function () {
        Route::get('dashboard',[SystemController::class,'dashboard'])->name('dashboard');
        // Users Section route
        Route::get('user/create',[RegisterController::class,'create'])->name('author.user_create');
        Route::post('user/store',[RegisterController::class,'store'])->name('author.user_store');
        Route::match(['get', 'post'],'users',[UsersController::class,'index'] )->name('author.users');
        Route::get('user/profile/{id}',[UsersController::class,'Profile'] )->name('author.user_profile');
        Route::get('user/{id}/edit',[UsersController::class,'edit'] )->name('author.user_edit');
        Route::post('user/update',[UsersController::class,'update'] )->name('author.user_update');
        Route::match(['get', 'post'], 'user/delete/{id}',[UsersController::class,'destroy'] )->name('author.user_delete');
        Route::get('change-password', [ChangePasswordController::class,'changePassword'])->name('author.change_password');
        Route::post('change-password', [ChangePasswordController::class,'changePasswordAction'])->name('author.change_password_store');


        // Users role Section route
        Route::get('roles', [PermissionController::class,'roles'])->name('author.roles');
        Route::get('role-create', [PermissionController::class,'roleCreate'])->name('author.role_create');
        Route::post('role-store', [PermissionController::class,'roleStore'])->name('author.role_store');


        // permissions section route
        Route::match(['get', 'post'], 'permissions', [PermissionController::class,'permissions'])->name('author.permission');
        Route::post('permissions/assign', [PermissionController::class,'assign'])->name('author.assign_permission');
        Route::post('user_permission', [PermissionController::class,'userPermission'])->name('author.user_permission');
        Route::match(['get', 'post'],'permissions/route-register', [PermissionController::class,'routeRegister'])->name('author.permission_register');
        Route::match(['get', 'post'],'permissions/route-register-edit', [PermissionController::class,'routeRegisterEdit'])->name('author.permission_edit');
        Route::get('permissions/route_remove', [PermissionController::class,'routeRemove'])->name('author.ajax.permission_remove');
        Route::get('permissions/route_check', [PermissionController::class,'routeCheck'])->name('author.ajax.permission_check');


        // Settings section route
        Route::get('base_setting', [BaseSettingController::class,'index'])->name('base_setting');
        Route::post('base_setting/store', [BaseSettingController::class,'store'])->name('base_setting.store');
        Route::match(['get', 'post'], '/base_setting/logo', [BaseSettingController::class,'logo'])->name('base_setting.logo');
        Route::match(['get', 'post'],'logs', [LogsController::class,'index'])->name('author.logs');
        Route::get('logs/{id}', [LogsController::class,'show'])->name('author.logs.logs_show');

    });

});




/******* Author CRUD Route *******/
//Route::group(['middleware' => ['admin'], 'prefix' => 'author', 'name'=>'author'], function () {
Route::group(['middleware' => ['authx'], 'prefix' => 'author', 'name'=>'author'], static function () {
    Route::resource('category', 'Author\CategoriesController',  [
        'names' => [
            'create' => 'author.category.create',
            'edit' => 'author.category.edit',
            'show' => 'author.category.show',
        ]])->only(['create','edit','show']);
    Route::match(['get', 'post'], 'category', [CategoriesController::class,'index'])->name('author.category');
    Route::post('category/store', [CategoriesController::class,'store'])->name('author.category.store');
    Route::match(['get', 'post'], 'category/delete/{id}', [CategoriesController::class,'destroy'])
        ->where(['id'=>'[0-9]+'])->name('author.category.delete');


    Route::resource('articles', 'Author\ArticlesController',  [
        'names' => [
            'create' => 'author.articles.create',
            'edit' => 'author.articles.edit',
            'show' => 'author.articles.show',
        ]])->only(['create','edit','show']);
    Route::match(['get', 'post'], 'articles', [ArticlesController::class,'index'])->name('author.articles');
    Route::post('articles/store', [ArticlesController::class,'store'])->name('author.articles.store');
    Route::match(['get', 'post'], 'articles/delete/{id}', [ArticlesController::class,'destroy'])
        ->where(['id'=>'[0-9]+'])->name('author.articles.delete');
    Route::get('articles/attribute/{id}', [ArticlesController::class,'attribute'])->name('author.articles.attribute');
    Route::get('articles/lead/{id}', [ArticlesController::class,'categoryLead'])->name('author.articles.lead');
    Route::get('articles/post-menu/{id}', [ArticlesController::class,'postMenu'])->name('author.articles.post_menu');
    Route::post('articles/post-menu/{id}', [ArticlesController::class,'postMenuStore'])->name('author.articles.post_menu_store');


    Route::resource('home-items', 'Author\HomeItemsController',  [
        'names' => [
            'create' => 'author.home_items.create',
            'edit' => 'author.home_items.edit',
        ]])->only(['create','edit']);
    Route::match(['get', 'post'], 'home-items', [HomeItemsController::class,'index'])->name('author.home_items');
    Route::post('home-items/store', [HomeItemsController::class,'store'])->name('author.home_items.store');
    Route::match(['get', 'post'], 'home-items/delete/{id}', [HomeItemsController::class,'destroy'])
        ->where(['id'=>'[0-9]+'])->name('author.home_items.delete');


    Route::resource('menu-types', 'Author\MenuTypesController',  [
        'names' => [
            'create' => 'author.menu_types.create',
            'edit' => 'author.menu_types.edit',
        ]])->only(['create','edit']);
    Route::match(['get', 'post'], 'menu-types', [MenuTypesController::class,'index'])->name('author.menu_types');
    Route::post('menu-types/store', [MenuTypesController::class,'store'])->name('author.menu_types.store');
    Route::match(['get', 'post'], 'menu-types/delete/{id}', [MenuTypesController::class,'destroy'])
        ->where(['id'=>'[0-9]+'])->name('author.menu_types.delete');



    Route::resource('menu', 'Author\MenuController',  [
        'names' => [
            'create' => 'author.menu.create',
            'edit' => 'author.menu.edit',
        ]])->only(['create','edit']);
    Route::match(['get', 'post'], 'menu', [MenuController::class,'index'])->name('author.menu');
    Route::post('menu/store', [MenuController::class,'store'])->name('author.menu.store');
    Route::match(['get', 'post'], 'menu/delete/{id}', [MenuController::class,'destroy'])
        ->where(['id'=>'[0-9]+'])->name('author.menu.delete');




    Route::resource('newsletter', 'Author\NewsletterController',  [
        'names' => [
            'create' => 'author.newsletter.create',
            'edit' => 'author.newsletter.edit',
            'show' => 'author.newsletter.show',
        ]])->only(['create','edit','show']);
    Route::match(['get', 'post'], 'newsletter', [NewsletterController::class,'index'])->name('author.newsletter');
    Route::post('newsletter/store', [NewsletterController::class,'store'])->name('author.newsletter.store');
    Route::match(['get', 'post'], 'newsletter/delete/{id}', [NewsletterController::class,'destroy'])
        ->where(['id'=>'[0-9]+'])->name('author.newsletter.delete');

    Route::match(['get', 'post'], 'newsletter/email-send/{id}', [NewsletterController::class,'emailSend'])->name('author.newsletter.email_send');



    Route::resource('tags', 'Author\TagsController',  [
        'names' => [
            'create' => 'author.tags.create',
            'edit' => 'author.tags.edit',
        ]])->only(['create','edit']);
    Route::match(['get', 'post'], 'tags', [TagsController::class,'index'])->name('author.tags');
    Route::post('tags/store', [TagsController::class,'store'])->name('author.tags.store');
    Route::match(['get', 'post'], 'tags/delete/{id}', [TagsController::class,'destroy'])
        ->where(['id'=>'[0-9]+'])->name('author.tags.delete');


    Route::group(['prefix'=>'contact-us','as'=>'author.contact_us'], function(){

        $controller = ContactUsController::class;
        Route::match(['get', 'post'], '', [$controller,'index'])->name('');
        Route::get( '/{id}', [$controller,'show'])->name('.show');
        Route::match(['get', 'post'], 'delete/{id}', [$controller,'destroy'])->where(['id'=>'[0-9]+'])->name('.delete');

        Route::match(['get', 'post'], 'email-send/{id}', [$controller,'emailSend'])->name('.email_send');
    });

    Route::group(['prefix'=>'mail-sending','as'=>'author.mail_sending'], function(){
        $controller = Sourcebit\Dprimecms\Http\Controllers\Author\MailSendingController::class;
        Route::get('', [$controller,'index'])->name('');
        Route::get('compose', [$controller,'compose'])->name('.compose');
//        Route::post('send-email', [$controller,'sendMail'])->name('.send_email');
        Route::get('bulk-email', [$controller,'bulkEmail'])->name('.bulk_email');
        Route::post('bulk-send-email', [$controller,'bulkSendEmail'])->name('.bulk_send_email');

        Route::get('newsletter', [$controller,'newsletter'])->name('.newsletter');
        Route::get('newsletter-template', [$controller,'newsletterTemplate'])->name('.newsletter_template');

        Route::get('newsletter-preview', [$controller,'newsletterPreview'])->name('.newsletter_preview');
        Route::post('newsletter-send-email', [$controller,'newsletterSendEmail'])->name('.send_email');
        Route::get( '/{id}', [$controller,'show'])->name('.show');

    });

    Route::group(['prefix'=>'mail-template','as'=>'author.mail_template'], function(){
        $controller = Sourcebit\Dprimecms\Http\Controllers\Author\MailTemplateController::class;
        Route::match(['get', 'post'], '', [$controller,'index'])->name('');
        Route::get('create', [$controller,'create'])->name('.create');
        Route::get('/{id}/edit', [$controller,'edit'])->name('.edit');
        Route::post('/store', [$controller,'store'])->name('.store');
        Route::match(['get', 'post'], '/delete/{id}', [$controller,'destroy'])->name('.delete');
    });



    Route::group(['prefix'=>'file-edit','as'=>'author.file-edit'], function(){
        $controller = Sourcebit\Dprimecms\Http\Controllers\Author\FileEditController::class;
        Route::get('/sitemap', [$controller,'sitemap'])->name('sitemap');
        Route::get('/robots', [$controller,'robots'])->name('robots');
        Route::post('/store', [$controller,'store'])->name('store');

    });

});


Route::group(['middleware' => ['admin'], 'prefix' => 'author'], function (){

    Route::get('/mediamanager/container',[MediaManagerController::class,'container'])->name('author.mediamanager.container');
    Route::get('/mediamanager/links',[MediaManagerController::class,'content_links'])->name('author.mediamanager.links');

    Route::get('/mediamanager',[MediaManagerController::class,'index'])->name('author.mediamanager');

    Route::post('/mediamanager/create', [MediaManagerController::class, 'create'])->name('author.mediamanager.create');
    Route::post('/mediamanager/rename', [MediaManagerController::class, 'rename'])->name('author.mediamanager.rename');
    Route::post('/mediamanager/delete', [MediaManagerController::class, 'delete'])->name('author.mediamanager.delete ');

    Route::match(['get', 'post'], '/mediamanager/upload',[MediaManagerController::class,'upload'])->name('author.mediamanager.upload');

    //route fallback. when post route requested
    // by get route.
    Route::fallback(function () {
        abort('404');
    });
});


/******* Author Ajax Route *******/
Route::group([ 'prefix' => 'author', 'name'=>'author'], static function () {
    Route::get('get-alias', [AjaxJsonController::class,'getAlias']);
    Route::get('order-by', [AjaxJsonController::class,'orderBy']);
//    Route::get('related-articles', [AjaxJsonController::class,'relatedArticles']);
    Route::post('related-articles', [AjaxJsonController::class,'relatedArticles']);


    Route::get('sql-query', function (){
        sqlQuery();
    });
});
