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

Route::get( '/', 'SiteController@index' );

Route::get( 'stores', 'StoreController@index' );

Route::get( 'stores/storenumber/{id?}', 'StoreController@storenumber' );

Route::post( 'stores/download', 'StoreController@download' );

Route::post( 'stores/clear', 'StoreController@clear' );
