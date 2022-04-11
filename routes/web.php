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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('articles')->group(function() {
    Route::get('', 'App\Http\Controllers\ArticleController@index')->name('article.index');
    Route::get('create', 'App\Http\Controllers\ArticleController@create')->name('article.create');
    Route::post('store', 'App\Http\Controllers\ArticleController@store')->name('article.store');
    Route::get('edit/{article}', 'App\Http\Controllers\ArticleController@edit')->name('article.edit');
    Route::post('update/{article}', 'App\Http\Controllers\ArticleController@update')->name('article.update');
    Route::post('destroy/{article}', 'App\Http\Controllers\ArticleController@destroy' )->name('article.destroy');
    Route::get('show/{article}', 'App\Http\Controllers\ArticleController@show')->name('article.show');
    Route::get('search', 'App\Http\Controllers\ArticleController@search')->name('article.search');
    Route::get('filter', 'App\Http\Controllers\ArticleController@filter')->name('article.filter');
    Route::get('indexAjax', 'App\Http\Controllers\ArticleController@indexAjax')->name('article.indexAjax');

    

});

Route::prefix('types')->group(function() {
    Route::get('', 'App\Http\Controllers\TypeController@index')->name('type.index');
    Route::get('create', 'App\Http\Controllers\TypeController@create')->name('type.create');
    Route::post('store', 'App\Http\Controllers\TypeController@store')->name('type.store');
    Route::get('edit/{type}', 'App\Http\Controllers\TypeController@edit')->name('type.edit');
    Route::post('update/{type}', 'App\Http\Controllers\TypeController@update')->name('type.update');
    Route::post('destroy/{type}', 'App\Http\Controllers\TypeController@destroy' )->name('type.destroy');
    Route::get('show/{type}', 'App\Http\Controllers\TypeController@show')->name('type.show');
    Route::get('search', 'App\Http\Controllers\TypeController@search')->name('type.search');
    Route::get('indexAjax', 'App\Http\Controllers\TypeController@indexAjax')->name('type.indexAjax');
});
