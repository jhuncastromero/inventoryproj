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

/*Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes();

//personnel user defined route
Route::get('/personnel/view','personnelController@listview')->name('personnel.view');
Route::post('/personnel/viewlist','personnelController@ajax_search_view_list')->name('personnel.viewdlist');
Route::get('/personnel/updatepersonnel','personnelController@personnelUpdate')->name('personnel.updatepersonnel');
Route::get('/personnel/updatedetails/{id}','personnelController@personnelUpdate_details')->name('personnel.updatedetails');
Route::post('/personnel/searchpersonnel','personnelController@personnel_query')->name('personnel.searchpersonnel');
Route::get('/personnel/showdetails/{id}','personnelController@personnel_show_details')->name('personnel.showdetails');
Route::get('/personnel/message','personnelController@personnel_post_message')->name('personnel.message');
Route::post('/personnel/previewdelete','personnelController@ajax_preview_delete')->name('personnel.previewdelete');
Route::post('/personnel/updatesearchpreview','personnelController@ajax_search_preview_delete_list')->name('personnel.updatesearchpreview');
Route::post('/personnel/updatesearchdetails','personnelController@ajax_search_preview_delete_details')->name('personnel.updatesearchdetails');

// personnel report related routes
Route::get('/personnel/generatereport','DynamicPDFController@index')->name('personnel.generatereport');
Route::get('/personnel/generatereport/pdf','DynamicPDFController@pdf')->name('personnel.pdf');

//Resource Route
Route::resource('personnel','personnelController');