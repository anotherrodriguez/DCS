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

Route::get('documentData', 'DocumentController@tableData');

Route::get('documentData/{document}', 'DocumentController@tableDataRevisions');

Route::get('revisionData/{revision}', 'RevisionController@tableData');

Route::get('revisions/file/{file}', 'RevisionController@showFile');

Route::get('customerData', 'CustomerController@tableData');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('partData', 'PartController@tableData');

Route::get('typeData', 'TypeController@tableData');

Route::get('processData', 'ProcessController@tableData');

Route::get('partTableData', 'PartController@partTableData');

Route::get('selectPart', 'PartController@selectPart');

Route::get('documents/create/{part_id}', 'DocumentController@create');

Route::get('revisions/create/{document_id}', 'RevisionController@create');

Route::resource('revisions', 'RevisionController');

Route::resource('documents', 'DocumentController');

Route::resource('process', 'ProcessController');

Route::resource('customers', 'CustomerController');

Route::resource('parts', 'PartController');

Route::resource('types', 'TypeController');