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

Route::get('/', function () {
    return view('welcome');
})->name('/');


Auth::routes();
//Route::get('/home', 'HomeController@index')->name('home');


Route::group(['namespace' => 'Blog', 'prefix' => 'blog'], function() {
    Route::resource('posts', 'PostController')->names('blog.posts');
});

//Route::group(['namespace' => 'User' 'prefix' => 'user'], function() {
//    Route::resource('posts', 'PostController')->names('blog.posts');
//});

Route::resource('user', 'UserController')
    ->only('show')
    ->names('user');

Route::resource('user', 'UserController')
    ->only('update')
    ->names('user')
    ->middleware('user');

// Админка блога
Route::group(['namespace' => 'Blog\Admin', 'prefix' => 'admin/blog', 'middleware' => ['admin', 'auth']], function () {
    // BlogCategory
    Route::resource('categories', 'CategoryController')
        ->only(['index', 'edit', 'store', 'update', 'create'])
        ->names('blog.admin.categories');

    // BlogPost
    Route::resource('posts', 'PostController')
        ->except(['show'])
        ->names('blog.admin.posts');
});


// Работа с коллекциями
Route::group(['prefix' => 'digging_deeper'], function () {
    Route::get('collections', "DiggingDeeperController@collections")
        ->name('digging_deeper.collections');
});


//Route::resource('rest', 'RestTestController')->names('restTest');

