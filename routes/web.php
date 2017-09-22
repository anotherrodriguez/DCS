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

Route::get('revisionData/{document}', 'RevisionController@tableData');

Route::get('showFile/{revision}', 'RevisionController@showFile');

Route::get('downloadFile/{file_revision}', 'RevisionController@downloadFile');

Route::get('customerData', 'CustomerController@tableData');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('partData', 'PartController@tableData');

Route::get('typeData', 'TypeController@tableData');

Route::get('fileData', 'FileController@tableData');

Route::get('processData', 'ProcessController@tableData');

Route::get('collectionData', 'CollectionController@tableData');

Route::get('showCollectionData/{collection}', 'CollectionController@showTableData');

Route::get('showAllCollectionData/{collection}', 'CollectionController@showAllTableData');

Route::get('collections/add/{collection}', 'CollectionController@addDocumentsView');

Route::post('collections/addDocument', 'CollectionController@addDocument');

Route::post('collections/removeDocument', 'CollectionController@removeDocument');

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

Route::resource('files', 'FileController');

Route::resource('collections', 'CollectionController');