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

Auth::routes();

Auth::routes();


Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/about', 'AboutController@index')->name('about');
Route::get('/contact', 'ContactController@index')->name('contact');
Route::get('/dcs', 'DcsController@index')->name('dcs');
Route::get('/reports', 'ReportController@index')->name('report');
Route::get('/reports/export', 'ReportController@export')->name('report');
Route::get('/profile', 'UserController@profile')->name('profile');
Route::get('/subject/by-course', 'SubjectController@byCourse');
Route::get('/bib/by-course', 'BibController@byCourse');
Route::patch('/bib/update-bib-view/{id}', 'BibController@updateBibViews');
Route::resource('/department', 'DepartmentController');
Route::resource('/search', 'SearchController');
Route::resource('/search-history', 'SearchHistoryController');
Route::resource('/course', 'CourseController');
Route::resource('/subject', 'SubjectController');
Route::resource('/user', 'UserController');
Route::resource('/bib', 'BibController');
Route::resource('/marc-tag', 'MarcTagController');
