<?php

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
Route::group(['prefix' => 'backend', 'namespace' => 'BackEnd'], function () {
    // Auth Routes
    Route::get('login', array('as' => 'backend.prelogin',
        'uses' => 'AuthController@displayLoginForm'));
    Route::post('login', array('as' => 'backend.login',
        'uses' => 'AuthController@login'));
    Route::get('logout', array('as' => 'backend.logout',
        'uses' => 'AuthController@logout'));
    Route::any('/', array('as' => 'dashboard', 'uses' => 'DashboardController@index'));
    Route::any('categories', array('as' => 'categories.index', 'uses' => 'CategoriesController@index'));
    Route::any('posts', array('as' => 'posts.index', 'uses' => 'PostsController@index'));
    Route::any('pages', array('as' => 'pages.index', 'uses' => 'PagesController@index'));
    Route::any('comments', array('as' => 'comments.index', 'uses' => 'CommentsController@index'));
    Route::post('comments/bulk', array('as' => 'comments.bulk', 'uses' => 'CommentsController@bulk'));
    Route::any('tags', array('as' => 'tags.index', 'uses' => 'TagsController@index'));
    Route::any('users', array('as' => 'users.index', 'uses' => 'AdminsController@index'));
    Route::any('medias', array('as' => 'users.index', 'uses' => 'MediasController@index'));
    Route::get('generate-sitemap', array('as' => 'sitemap.generator', 'uses' => 'DashboardController@sitemapGenerator'));
    Route::get('menus', array('as' => 'menus.index', 'uses' => 'MenusController@index'));
    Route::post('menus', array('as' => 'menus.save', 'uses' => 'MenusController@save'));
    Route::get('settings', array('as' => 'settings.edit', 'uses' => 'SettingsController@edit'));
    Route::put('settings', array('as' => 'settings.update', 'uses' => 'SettingsController@update'));
});
Route::group(['namespace' => 'FrontEnd'], function () {
    Route::get('/', array('as' => 'homepage', 'uses' => 'BlogController@homepage'));
    Route::get('rss', array('as' => 'rss', 'uses' => 'BlogController@rss'));
    Route::get('category/{category}', array('as' => 'category', 'uses' => 'BlogController@category'));
    Route::get('category/{category}/page/{page}', array('as' => 'category', 'uses' => 'BlogController@category'));
    Route::get('search/{search}', array('as' => 'search', 'uses' => 'BlogController@search'));
    Route::get('search/{search}/page/{page}', array('as' => 'search', 'uses' => 'BlogController@search'));
    Route::get('tag/{tag}', array('as' => 'tag', 'uses' => 'BlogController@tag'));
    Route::get('tag/{tag}/page/{page}', array('as' => 'tag', 'uses' => 'BlogController@tag'));
    Route::get('{page}/page/{number}', array('as' => 'blog', 'uses' => 'BlogController@page'));
    Route::get('{page}', array('as' => 'page', 'uses' => 'BlogController@page'));
    Route::post('{page}', array('as' => 'page', 'uses' => 'BlogController@page'));
    Route::get('{page}/{post}', array('as' => 'post', 'uses' => 'BlogController@single'));
    Route::post('{page}/{post}', array('as' => 'post', 'uses' => 'BlogController@single'));
});
