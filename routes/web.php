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

Route::get('/test', function () {
	$tableColumns = ['Document', 'Revision', 'Description', 'Customer'];
	$dataColumns = ['document_number', 'revision', 'description', 'customer'];

	$columns = ['tableColumns' => $tableColumns, 'dataColumns' => $dataColumns];
    return view('test', $columns);
});

Route::resource('revisions', 'RevisionController');

Route::resource('document', 'DocumentController');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
