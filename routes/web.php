<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Mews\Captcha\Captcha;
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
    Route::get('/getTrip', 'IndexController@getTrip');
    Route::get('/about', 'About\AboutController@index');
    Route::get('/itinerary', 'Itinerary\ItryController@index');
    Route::get('/itinerary/{key}', 'Itinerary\ItryController@index')->where(['key' => '[a-zA-Z0-9_-]+']);;
    Route::get('/itinerary/{key}/trip/{trip}', 'Itinerary\TripController@index')->where(['key' => '[a-zA-Z0-9_-]+', 'trip' => '[a-zA-Z0-9_-]+']);;
    Route::patch('/itinerary/{key}', 'Itinerary\ItryController@search')->where(['key' => '[a-zA-Z0-9_-]+']);
    Route::patch('/itinerary/{key}', 'Itinerary\ItryController@search')->where(['key' => '[a-zA-Z0-9_-]+']);
    Route::post('/update-trip-time', 'Itinerary\TripController@update');
    Route::post('/itinerary/{key}/trip/{trip}/apply', 'Itinerary\TripController@create');
    Route::get('/ttt', function () {
        return view('t');
    });
    Route::get('/blog', 'Blog\BlogController@index');
    Route::get('/blog/{key}', 'Blog\BlogController@index')->where(['key' => '[a-zA-Z0-9_-]+']);
    Route::get('/blog/{key}/item/{item}', 'Blog\BlogItemController@index')->where(['key' => '[a-zA-Z0-9_-]+', 'trip' => '[a-zA-Z0-9_-]+']);
    Route::post('/blog/active', 'Blog\BlogController@store');
    Route::patch('/blog/{key}', 'Blog\BlogController@search')->where(['key' => '[a-zA-Z0-9_-]+']);
//    Route::get('/blog/Search/{key}', 'Blog\BlogController@Search');
    Route::fallback(function () {
        return response()->view('errors.404', [], 404);
    });
});


Route::get('/captcha', function () {
    return captcha();
});
Route::post('/verify-captcha', function (Request $request) {


//    return response()->json(['message' => '驗證成功！']);
});
//// routes/web.php
//Route::group(['middleware' => ['cookie-consent']], function () {
//    Route::get('/ta', function () {
//        return response()->view('errors.404', [], 404);
//    });
//});
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
