<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});
Route::group(['prefix' => '/', 'namespace' => '\App\Http\Controllers'], function () {
    Route::get('/', 'IndexController@index');
    Route::get('/about', 'About\AboutController@index');
    Route::get('/itinerary', function() {
        return view('Itinerary.itinerary');
    });
    Route::get('/t', function() {
        return view('t');
    });
    Route::get('/blog/{key}', 'Blog\BlogController@index');
    Route::get('/blog/{key}/item/{item}', 'Blog\BlogItemController@index');
    Route::POST('/blog/active', 'Blog\BlogController@store');
    Route::PATCH('/blog/{key}', 'Blog\BlogController@search');
    Route::get('/blog', 'Blog\BlogController@index');
//    Route::get('/blog/Search/{key}', 'Blog\BlogController@Search');
    Route::fallback(function () {
        return response()->view('errors.404', [], 404);
    });
});
//Route::group(['prefix' => '/', 'namespace' => '\App\Http\Controllers', 'middleware' => 'ngrok.header'], function () {
//    Route::get('/', function () use ($request) {
//        return app(IndexController::class)->index($request);
//    });
//    Route::get('/about', function () use ($request) {
//        return app(About\AboutController::class)->index($request);
//    });
//    Route::get('/contact', function () use ($request) {
//        return app(ContactController::class)->index($request);
//    });
//});
