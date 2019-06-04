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

//PERSONNEL user defined route
Route::get('/personnel/view','personnelController@listview')->name('personnel.view');
Route::post('/personnel/viewlist','personnelController@ajax_search_view_list')->name('personnel.viewlist');
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



//HARDWARE_EQUIPMENT user defined route

Route::post('/hardware_equipment/preview_qrcode','HardwareEquipmentController@generate_qr_code')->name('hardware_equipment.preview_qrcode');
Route::get('/hardware_equipment/view','HardwareEquipmentController@list_view_equipment_pagination')->name('hardware_equipment.view');
Route::get('/hardware_equipment/filterview','HardwareEquipmentController@filter_view_equipment_pagination')->name('hardware_equipment.filterview');
Route::get('/hardware_equipment/updatelist','HardwareEquipmentController@update_list_equipment')->name('hardware_equipment.updatelist');
Route::get('/hardware_equipment/updatefilterview','HardwareEquipmentController@update_filter_equipment_pagination')->name('hardware_equipment.updatefilterview');
Route::get('/hardware_equipment/updatedetails/{id}','HardwareEquipmentController@update_details_equipment')->name('hardware_equipment.updatedetails');
Route::post('/hardware_equipment/updateqrcode','HardwareEquipmentController@ajax_equipment_update_qr')->name('hardware_equipment.updateqrcode');
Route::post('/hardware_equipment/preview_hardware','HardwareEquipmentController@ajax_equipment_preview_hardware')->name('hardware_equipment.preview_hardware');
Route::post('/hardware_equipment/searchequipment','HardwareEquipmentController@search_equipment')->name('hardware_equipment.searchequipment');


//IT EQUIPMENT ASSIGN DEPLOY user defined route

Route::get('/deployment_it/deployequipment','DeploymentItController@deploy_equipment')->name('deployment_it.deployequipment');
Route::post('/deployment_it/equipmentlist','DeploymentItController@ajax_equipment_list')->name('deployment_it.equipmentlist');
Route::post('/deployment_it/deploydetails','DeploymentItController@deploy_details')->name('deployment_it.deploydetails');
Route::post('/deployment_it/employeedetails','DeploymentItController@ajax_find_employee')->name('deployment_it.employeedetails');
Route::get('/deployment_it/viewdeployment','DeploymentItController@view_deployment')->name('deployment_it.viewdeployment');
Route::get('/deployment_it/viewpersonneldeployment','DeploymentItController@view_personnel_deployment')->name('deployment_it.viewpersonneldeployment');
Route::post('/deployment_it/viewpersonneldeploymentdetails','DeploymentItController@ajax_view_personnel_deployment_details')->name('deployment_it.viewpersonneldeploymentdetails');
Route::get('/deployment_it/viewequipmentdeployment','DeploymentItController@view_equipment_deployment')->name('deployment_it.viewequipmentdeployment');
Route::post('/deployment_it/viewequipmentdeploymentdetails','DeploymentItController@ajax_view_equipment_deployment_details')->name('deployment_it.viewpersonnelequipmentdetails');




//Resource Route
Route::resource('personnel','personnelController');
Route::resource('hardware_equipment','HardwareEquipmentController');
Route::resource('deployment_it','DeploymentItController');
