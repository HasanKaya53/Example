<?php

use App\Http\Middleware\AuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {

})->middleware();


//todo: add the middleware to the routes

Route::middleware(AuthMiddleware::class)->group(function () {
    Route::get('/get/orders', 'App\Http\Controllers\OrderController@getOrders');
    Route::get('/get/order/{id}', 'App\Http\Controllers\OrderController@getOrder');
    Route::post('/add/order', 'App\Http\Controllers\OrderController@addOrder');
    Route::post('/delete/order/{id}', 'App\Http\Controllers\OrderController@deleteOrder');

    Route::post('/discount/list/{id}', 'App\Http\Controllers\DiscountController@list');
});

