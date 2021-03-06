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
});



// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')
    ->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')
    ->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')
    ->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('email-verification/error', 'EmailVerificationController@getVerificationError')
    ->name('email-verification.error');
Route::get('email-verification/check/{token}', 'EmailVerificationController@getVerification')
    ->name('email-verification.check');

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin\\'], function(){
    Route::group(['middleware' => ['isVerified', 'can:admin']], function(){
        Route::post('logout', 'Auth\LoginController@logout')->name('logout');
        Route::get('dashboard', function(){
           return view('admin.dashboard');
        });

        Route::get('users/change-password', 'UsersController@changePassword')->name('users.change-password');
        Route::put('users/change-password', 'UsersController@updatePassword')->name('users.update-password');
        Route::resource('users', 'UsersController');
        Route::resource('categories', 'CategoriesController');

        Route::get('series/{serie}/thumb-asset', 'SeriesController@thumbAsset')->name('series.thumb-asset');
        Route::get('series/{serie}/thumb-small-asset', 'SeriesController@thumbSmallAsset')
            ->name('series.thumb-small-asset');
        Route::resource('series', 'SeriesController');
        Route::group(['prefix' => 'videos', 'as' => 'videos.'], function(){
            Route::get('{video}/relations', 'VideoRelationsController@create')->name('relations.create');
            Route::post('{video}/relations', 'VideoRelationsController@store')->name('relations.store');
            Route::get('{video}/uploads', 'VideoUploadsController@create')->name('uploads.create');
            Route::post('{video}/uploads', 'VideoUploadsController@store')->name('uploads.store');
        });


        Route::resource('videos', 'VideosController');
        Route::resource('plans', 'PlansController');
        Route::resource('web_profiles', 'PayPalWebProfilesController');

        Route::get('subscriptions', 'SubscriptionsController@index')->name('subscriptions.index');

    });
    Route::get('videos/{video}/file-asset', 'VideosController@fileAsset')
        ->name('videos.file-asset');
    Route::get('videos/{video}/thumb-asset', 'VideosController@thumbAsset')->name('videos.thumb-asset');
    Route::get('videos/{video}/thumb-small-asset', 'VideosController@thumbSmallAsset')
        ->name('videos.thumb-small-asset');

    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
});
