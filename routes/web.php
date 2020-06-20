<?php

use Illuminate\Support\Facades\Auth;
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
})->name('link');
Route::resource('post', 'PostFileController');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('dowload', 'StorageController@download')->name('download');

Route::post('upload-file', 'StorageController@uploadFile')->name('uploadFile');
Route::delete('destroy-file', 'StorageController@destroyFile')->name('destroyFile');

Route::get('check-name-exists', 'StorageController@checkName')->name('CheckNameExists');

Route::post('store-folder', 'StorageController@storeFolder' )->name('storeFolder');
Route::delete('destroy-folder', 'StorageController@destroyFolder')->name('destroyFolder');
route::get('show-folder', 'StorageController@showFolder')->name('showFolder');

Route::get('test', 'UploadController@create');
Route::post('drag-drop-images', 'UploadController@store');

Route::get('move-file', 'StorageController@moveFile')->name('moveFile');
Route::get('merger-pdf', 'MergerPdfController@mergerPdf')->name('mergerPdf');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
