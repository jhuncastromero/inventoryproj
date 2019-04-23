<?php

namespace App\Http\Controllers;

use App\hardware_equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Filesystem;
use Intervention\Image\Facades\Image;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class HardwareEquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('module2.equipment.home');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $createvalue = '';
        $error_title = '';
        $error_message = '';
        $error_icon = '';

        return view('module2.equipment.create',compact('createvalue', 'error_title','error_message','error_icon'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $error_warning = 0;
        $createvalue = '';
        $error_title = '';
        $error_message = '';
        $error_icon = '';
        $qr_code ='';

        $hardware_equipment = new hardware_equipment;
        $error_warning = $hardware_equipment::get_error_warning($request->tag_no, $request->serial_no, $request->mac_address);
        
        if($error_warning == 0) { //no duplicated values detected

                if ($request->category == 'IT'){

                    $photo_name = $request->serial_no.'.jpg';
                    $qrcode_name= $request->serial_no.'.png';
                }
                else {

                    $photo_name = $request->tag_no.'.jpg';
                    $qrcode_name= $request->tag_no.'.png';

                }   

                $photofile = $request->file('photofile'); //save photo
                
                if($photofile) {

                     $file_name = $photo_name;  
                     $file_category = $request->category;
                     $photofile->storeAs('public/hardware_photo/'.$file_category, $file_name);

                    
                }
                else {

                    $photo_name = '';
                }

            $add_new = $hardware_equipment::save_data($request->tag_no, $request->serial_no,$request->category,$request->type,$request->origin,$request->mac_addres,$request->description,$photo_name,$request->status,$request->date_acquired, $qrcode_name, $request->brand);

            $qr_code = $this->save_qr_code($request->category,$request->tag_no, $request->serial_no); 

            $createvalue = 2;
        }
        else {

            if($error_warning == 1 ){

                 $error_title = "Duplicated Entry";
                 $error_icon = "warning";
                 $error_message = "An Existing Entry was found. Please check the following: Equipment Tag No and Equipment Serial No.";

            }
            else if($error_warning == 2){

                 $error_title = "Existing Equipment Tag No.";
                 $error_icon = "warning";
                 $error_message ="You have entered an existing Equipment Tag No. Please check your entry.";

            }
            else if($error_warning == 3) {

                 $error_title = "Existing Equipment Serial No.";
                 $error_icon = "warning";
                 $error_message ="You have entered an existing Equipment Serial No. Please check your entry.";

            }
            else if($error_warning == 4) {

                 $error_title = "Existing MAC Address/ Code.";
                 $error_icon = "warning";
                 $error_message ="You have entered an existing MAC Address/ Code. Please check your entry.";

            }

            $createvalue=1;
            
        }

        return view('module2.equipment.create',compact('createvalue', 'error_title','error_message','error_icon'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\hardware_equipment  $hardware_equipment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $query_result = '';
        $hardware_equipment = new hardware_equipment;
        $query_result = $hardware_equipment::show_details($id);

        return view('module2.equipment.show',compact('query_result'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\hardware_equipment  $hardware_equipment
     * @return \Illuminate\Http\Response
     */
    public function edit(hardware_equipment $hardware_equipment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\hardware_equipment  $hardware_equipment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $photo_name  = '';
        $photo_status = '';
        $error_title ='';
        $error_message = '';
        $error_icon = '';
        $error_warning = '';


        $hardware_equipment = new hardware_equipment;
        $query_results = $hardware_equipment::flexible_search('id', $id);

        if($request->tag_no != $query_results[0]->tag_no || $request->serial_no != $query_results[0]->serial_no || $request->mac_address != $query_results[0]->mac_address ) {   //user has changed value in tagno, serialno or mac_address

                $error_warning = $hardware_equipment::get_update_error_warning($request->tag_no, $request->serial_no, $request->mac_address, $id);
         
              if($error_warning[0] == 0 && $error_warning[1] == 0 && $error_warning[2] == 0 ) {


                    if($request->hiddentext =='new_photo' ) { //user has opted to change  the photo
                    
                            $photofile = $request->file('photofile');
                            if($photofile) { //user has selected a new photo/picture
                                                
                                 if(Storage::exists('public/hardware_photo/IT/'.$query_results[0]->photo_name)) {
                                    Storage::delete('public/hardware_photo/IT/'.$query_results[0]->photo_name);
                                 }
                                 else{
                                    Storage::delete('public/hardware_photo/Non-IT/'.$query_results[0]->photo_name);  
                                 }

                                 if ($request->category == 'IT'){ //test what category will the photo will be saved
                                      $photo_name = $request->serial_no.'.jpg';
                                       //$qrcode_name= $request->serial_no.'.png';
                                 }
                                 else {
                                      $photo_name = $request->tag_no.'.jpg';
                                        //$qrcode_name= $request->tag_no.'.png';
                                 }

                                 $photofile->storeAs('public/hardware_photo/'.$request->category, $photo_name); //save photo


                                
                            }
                            else {  //no photo was selected
                                $photo_name = $query_results[0]->photo_name; //retain the old photo name saved in dbase

                            }
                    }
                    else if ($request->hiddentext == 'no_photo') {

                        if(Storage::exists('public/hardware_photo/IT/'.$query_results[0]->photo_name)) {
                               Storage::delete('public/hardware_photo/IT/'.$query_results[0]->photo_name);
                        }
                        else{
                              Storage::delete('public/hardware_photo/Non-IT/'.$query_results[0]->photo_name);  
                        }

                        $photo_name = ''; //set null to photo_name since photo is removed or there is no photo at all from the start

                    } 
                    else { //user has not selected any of the option regarding photos

                        $photo_name = $query_results[0]->photo_name; //retain the old photo name saved in dbase
                    }
                
                    //save changes made
                    $update_data = $hardware_equipment::update_data($id, $request->tag_no, $request->serial_no,$request->category,$request->type,$request->origin,$request->mac_addres,$request->description,$photo_name,$request->status,$request->date_acquired, $query_results[0]->qrcode_name, $request->brand);

                   $updatevalue = 2;
              }
              else {  //error warning was detected

                    if($error_warning[0] != 0 && $error_warning[1] != 0 && $error_warning[2] != 0 ){

                         $error_title = "Duplicated Entry";
                         $error_icon = "warning";
                         $error_message = "An Existing Entry was found. Please check the following: Equipment Tag No and Equipment Serial No.";

                    }
                    else if($error_warning[0] != 0){

                         $error_title = "Existing Equipment Tag No.";
                         $error_icon = "warning";
                         $error_message ="You have entered an existing Equipment Tag No. Please check your entry.";

                    }
                    else if($error_warning[1] != 0) {

                         $error_title = "Existing Equipment Serial No.";
                         $error_icon = "warning";
                         $error_message ="You have entered an existing Equipment Serial No. Please check your entry.";

                    }
                    else if($error_warning[2] != 0) {

                         $error_title = "Existing MAC Address/ Code.";
                         $error_icon = "warning";
                         $error_message ="You have entered an existing MAC Address/ Code. Please check your entry.";

                    }

                    $updatevalue = 1;

                }
        }
        else { // no changes has been made in serialno, tagno or mac_address

                   if($request->hiddentext =='new_photo' ) { //user has opted to change  the photo
                    
                            $photofile = $request->file('photofile');
                            if($photofile) { //user has selected a new photo/picture
                                                
                                 if(Storage::exists('public/hardware_photo/IT/'.$query_results[0]->photo_name)) {
                                    Storage::delete('public/hardware_photo/IT/'.$query_results[0]->photo_name);
                                 }
                                 else{
                                    Storage::delete('public/hardware_photo/Non-IT/'.$query_results[0]->photo_name);  
                                 }

                                 if ($request->category == 'IT'){ //test what category will the photo will be saved
                                      $photo_name = $request->serial_no.'.jpg';
                                       //$qrcode_name= $request->serial_no.'.png';
                                 }
                                 else {
                                      $photo_name = $request->tag_no.'.jpg';
                                        //$qrcode_name= $request->tag_no.'.png';
                                 }

                                 $photofile->storeAs('public/hardware_photo/'.$request->category, $photo_name); //save photo


                                
                            }
                            else {  //no photo was selected
                                $photo_name = $query_results[0]->photo_name; //retain the old photo name saved in dbase

                            }
                    }
                    else if ($request->hiddentext == 'no_photo') {

                        if(Storage::exists('public/hardware_photo/IT/'.$query_results[0]->photo_name)) {
                               Storage::delete('public/hardware_photo/IT/'.$query_results[0]->photo_name);
                        }
                        else{
                              Storage::delete('public/hardware_photo/Non-IT/'.$query_results[0]->photo_name);  
                        }

                        $photo_name = ''; //set null to photo_name since photo is removed or there is no photo at all from the start

                    } 
                    else { //user has not selected any of the option regarding photos

                        $photo_name = $query_results[0]->photo_name; //retain the old photo name saved in dbase
                    }
                
                    //save changes made
                    $update_data = $hardware_equipment::update_data($id, $request->tag_no, $request->serial_no,$request->category,$request->type,$request->origin,$request->mac_addres,$request->description,$photo_name,$request->status,$request->date_acquired, $query_results[0]->qrcode_name, $request->brand);

                    $updatevalue = 2;

        }

         $query_results = $hardware_equipment::flexible_search('id', $id);

         return view('module2.equipment.update_details_equipment',compact('query_results','updatevalue', 'error_title','error_message','error_icon','photo_status'));
         
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\hardware_equipment  $hardware_equipment
     * @return \Illuminate\Http\Response
     */
    public function destroy(hardware_equipment $hardware_equipment)
    {
        //
    }


    //defined function

    
    /* 1 */ public function generate_qr_code(Request $request)
    {
       
    
    $qr_data = '';

    if($request->category == 'IT') {
        $qr_data = $request->serial_no;    
    }
    else if($request->category == 'Non-IT') {
        $qr_data = $request->tag_no;
    }
    
    if($qr_data == '') {
        $output ='<i style="font-size: 14px;">(Please Choose a Category and Input Serial and Tag No for QR Code Generation) </i>';
    }
    else {

        $qr_code = QrCode::format('png')->size(100)->generate($qr_data);
        $output = '<img class="" src="data:image/png;base64,'.base64_encode($qr_code).'" width="90px" height="90px">';
        $output.='&nbsp;<i style="font-size:14px;">(QR Code: &nbsp;'.$qr_data.')</i>';    
    }
    
    return $output;
    

    }

    /* 2 */public function save_qr_code($category, $tag_no, $serial_no)
    {
      
        $qr_data = '';

        if($category == 'IT') {
            $qr_data = $serial_no;    
        }
        else if($request->category == 'Non-IT') {
            $qr_data = $tag_no;
        }
        
        if($qr_data != '') {
      
          Storage::makeDirectory('public/qrcode');   
          $qr_code = QrCode::format('png')->size(100)->generate($qr_data, storage_path().'/app/public/qrcode/'.$qr_data.'.png');
        }   
    }
    /*3 */public function list_view_equipment()
    {
        $hardware_equipment = new hardware_equipment;
        $query_results = $hardware_equipment::list_view_equipment();
        return view('module2.equipment.view',compact('query_results'));
       
    }
    /* 4 */ public function update_list_equipment()
    {
       $hardware_equipment = new hardware_equipment;
        $query_results = $hardware_equipment::list_view_equipment();
        return view('module2.equipment.update_list_equipment',compact('query_results'));
    }
    /* 5 */ public function update_details_equipment($id)
    {
        $error_title ='';
        $error_message = '';
        $error_icon = '';
        $updatevalue = '';
        $photo_status = '';


        $hardware_equipment = new hardware_equipment;
        $query_results = $hardware_equipment::show_details($id);
        return view('module2.equipment.update_details_equipment',compact('query_results','photo_status','error_title','error_icon','error_message','updatevalue'));
    }
}
