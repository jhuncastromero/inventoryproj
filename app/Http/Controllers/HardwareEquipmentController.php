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

use App\paginationsetting;

class HardwareEquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
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
           /* else if($error_warning == 4) {

                 $error_title = "Existing MAC Address/ Code.";
                 $error_icon = "warning";
                 $error_message ="You have entered an existing MAC Address/ Code. Please check your entry.";

            }*/

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
        $qr_code ='';
        $qr_notice = '';
        
        

        $hardware_equipment = new hardware_equipment;
        $query_results = $hardware_equipment::flexible_search('id', $id);

        if($request->tag_no != $query_results[0]->tag_no || $request->serial_no != $query_results[0]->serial_no) {   //user has changed value in tagno, serialno

                $error_warning = $hardware_equipment::get_update_error_warning($request->tag_no, $request->serial_no, $request->mac_address, $id);

         
              if($error_warning[0] == 0 && $error_warning[1] == 0 ) {

                    $temp = $this->update_qr_code($request->category, $request->tag_no, $request->serial_no, $query_results[0]->QRCode_name); //update qr code since user made a change in serial, tagno)

                    if($request->category =='IT') {
                                
                                if($request->serial_no !=''){
                                    $qr_code  = $request->serial_no.'.png';
                                }
                                else {
                                 $qr_code  = '';   
                                }


                        }
                        else if($request->category == 'Non-IT') {

                                if($request->tag_no !=''){
                                    $qr_code  = $request->tag_no.'.png';
                                }
                                else {
                                 $qr_code  = '';   
                                }  


                        }

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

                        if($request->category != $query_results[0]->category){
                                if(Storage::exists('public/hardware_photo/IT/'.$query_results[0]->photo_name)) {
                                       Storage::move('public/hardware_photo/IT/'.$query_results[0]->photo_name, 'public/hardware_photo/Non-IT/'.$request->tag_no.'.jpg');

                                       
                                            $photo_name = $request->tag_no.'.jpg';    

                                }
                                else if(Storage::exists('public/hardware_photo/Non-IT/'.$query_results[0]->photo_name)) {
                                     Storage::move('public/hardware_photo/Non-IT/'.$query_results[0]->photo_name, 'public/hardware_photo/IT/'.$request->serial_no.'.jpg');  
                                        
                                             $photo_name = $request->serial_no.'.jpg';    
                                       
                                }

                         }
                         else {

                             $photo_name = $query_results[0]->photo_name; //retain the old photo name saved in dbase
                         }

                    }
                
                    //SAVE CHANGES MADE

                    //qr code generation
                    $qr_notice = session('qr_notice');

                    if($qr_notice =='generated') { //test if user has updated the qrcode of the item
                        if($request->category =='IT') {

                                if($request->serial_no !=''){
                                    $qr_code  = $request->serial_no.'.png';
                                }
                                else {
                                 $qr_code  = '';   
                                }

                        }
                        else if($request->category == 'Non-IT') {

                               if($request->tag_no !=''){
                                    $qr_code  = $request->tag_no.'.png';
                                }
                                else {
                                 $qr_code  = '';   
                                }  

                        }
                        $temp = $this->update_qr_code($request->category,$request->tag_no, $request->serial_no, $query_results[0]->QRCode_name); 
                    }
                    else {
                         $qr_code  = $query_results[0]->QRCode_name; 
                    }

                    //save operation
                    $update_data = $hardware_equipment::update_data($id, $request->tag_no, $request->serial_no,$request->category,$request->type,$request->origin,$request->mac_address,$request->description,$photo_name,$request->status,$request->date_acquired, $qr_code, $request->brand);

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
                    /*else if($error_warning[2] != 0) {

                         $error_title = "Existing MAC Address/ Code.";
                         $error_icon = "warning";
                         $error_message ="You have entered an existing MAC Address/ Code. Please check your entry.";

                    }*/

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

                        if($request->category != $query_results[0]->category){
                                if(Storage::exists('public/hardware_photo/IT/'.$query_results[0]->photo_name)) {
                                       Storage::move('public/hardware_photo/IT/'.$query_results[0]->photo_name, 'public/hardware_photo/Non-IT/'.$request->tag_no.'.jpg');

                                            $photo_name = $request->tag_no.'.jpg';    
                                        

                                }
                                else if(Storage::exists('public/hardware_photo/Non-IT/'.$query_results[0]->photo_name)) {
                                     Storage::move('public/hardware_photo/Non-IT/'.$query_results[0]->photo_name, 'public/hardware_photo/IT/'.$request->serial_no.'.jpg');  
                                     
                                         $photo_name = $request->serial_no.'.jpg';    
                                       
                                }

                         }
                         else {

                             $photo_name = $query_results[0]->photo_name; //retain the old photo name saved in dbase
                         }

                       
                    }
                
                    //SAVE CHANGES MADE

                   //qr code generation
                    $qr_notice = session('qr_notice');

                    if($qr_notice =='generated') { //test if user has updated the qrcode of the item
                        if($request->category =='IT') {
                                
                                if($request->serial_no !=''){
                                    $qr_code  = $request->serial_no.'.png';
                                }
                                else {
                                 $qr_code  = '';   
                                }


                        }
                        else if($request->category == 'Non-IT') {

                                if($request->tag_no !=''){
                                    $qr_code  = $request->tag_no.'.png';
                                }
                                else {
                                 $qr_code  = '';   
                                }  


                        }
                        $temp = $this->update_qr_code($request->category,$request->tag_no, $request->serial_no, $query_results[0]->QRCode_name); 
                    }
                    else {
                         $qr_code  = $query_results[0]->QRCode_name; 
                    }

                    //save operation
                    $update_data = $hardware_equipment::update_data($id, $request->tag_no, $request->serial_no,$request->category,$request->type,$request->origin,$request->mac_address,$request->description,$photo_name,$request->status,$request->date_acquired, $qr_code, $request->brand);



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
    public function destroy(Request $request)
    {
        //
       $action = '';
       $deletevalue = 3;
       $id = '';
       
       $id = $request->hidden_r_index;
           
       $hardware_equipment = new hardware_equipment;
       $pagination_number = $hardware_equipment::pagination_setting('hardware_equipments');
       $delete_query= $hardware_equipment::delete_data($id);
        $query_results = $hardware_equipment::list_view_equipment_pagination();

       return view('module2.equipment.update_list_equipment',compact('query_results','deletevalue','pagination_number','action'));
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
        else if($category == 'Non-IT') {
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
     /*3-b */public function list_view_equipment_pagination()
    {
        $action ='';
        $hardware_equipment = new hardware_equipment;
        $pagination_number = $hardware_equipment::pagination_setting('hardware_equipments');
        $query_results = $hardware_equipment::list_view_equipment_pagination();

        return view('module2.equipment.view',compact('query_results','pagination_number','action'));
       
    }
      /*3-c */public function filter_view_equipment_pagination(Request $request)
    {
        $hardware_equipment = new hardware_equipment;
        $pagination_number = 0;
        $action ='filter';
        $query_results = $hardware_equipment::filter_view_equipment_pagination($request->type, $request->category);

        return view('module2.equipment.view',compact('query_results','pagination_number','action'));
       
    }
    /* 4 */ public function update_list_equipment()
    {
        $deletevalue ='';
        $action ='';
        $hardware_equipment = new hardware_equipment;
        $pagination_number = $hardware_equipment::pagination_setting('hardware_equipments');
        $query_results = $hardware_equipment::list_view_equipment_pagination();
        return view('module2.equipment.update_list_equipment',compact('query_results','deletevalue','pagination_number','action'));
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
    //update_filter_equipment_pagination
     /*5-b */public function update_filter_equipment_pagination(Request $request)
    {
        
        $deletevalue ='';
        $query_results = '';
        $hardware_equipment = new hardware_equipment;
        $action = 'filter';
        $pagination_number = 0;
        $query_results = $hardware_equipment::update_filter_equipment_pagination($request->type, $request->category);


        return view('module2.equipment.update_list_equipment',compact('query_results','deletevalue','action','pagination_number'));
       
    }

    /* 6 */ public function ajax_equipment_update_qr(Request $request)
    {
        
        if($request->category == 'IT'){
            if($request->serial_no != '') {
                 $qr_code = QrCode::format('png')->size(100)->generate($request->serial_no);
            }
            else {
                 
                 $qr_code ='';
            }
        }
        else if($request->category =='Non-IT') {
          
            if($request->tag_no !='') {
              $qr_code = QrCode::format('png')->size(100)->generate($request->tag_no);   
            }
            else{

                $qr_code ='';
            }
        }
        
        
        if ($qr_code == '')
        {
            $output ='';
        }
        else {

            $output = '<img class="" src="data:image/png;base64,'.base64_encode($qr_code).'" width="100px" height="100px">'; 

        }
        

        session(['qr_notice' =>'generated']);
        
        return $output;
    }
    /* 7 */public function update_qr_code($category, $tag_no, $serial_no, $qrcode_name)
    {
      
        $qr_data = '';
        
        if(Storage::exists('public/qrcode/'.$qrcode_name)) {
            Storage::delete('public/qrcode/'.$qrcode_name);
        }

        if($category == 'IT') {
            $qr_data = $serial_no;    
        }
        else if($category == 'Non-IT') {
            $qr_data = $tag_no;
        }
        
        if($qr_data != '') {
      
          Storage::makeDirectory('public/qrcode');   
          $qr_code = QrCode::format('png')->size(100)->generate($qr_data, storage_path().'/app/public/qrcode/'.$qr_data.'.png');
        }   
    }
    /* 8*/ public function ajax_equipment_preview_hardware(Request $request)
    {
        

        $hardware_equipment = new hardware_equipment;
        $query_results = $hardware_equipment::flexible_search('id', $request->hidden_r_index);
        
        $output = '<div class="row">';
        $output.= '<div class="col s3 m3 l3">';
        $output.= '<div style="padding-top:10px;">';

                if(!empty($query_results[0]->photo_name))
                {
                     $output.='<img src="'. asset(Storage::url('hardware_photo/'.$query_results[0]->category.'/'.$query_results[0]->photo_name)).'" width="100px" height="100px">';
                }
                else
                {
                     $output.='<i class="medium material-icons">photo</i>';
                 
                }
        $output.= '</div>';
        $output.= '</div>';
        
        $output.= '<div class="col s10 m10 l10">';
        $output.='<table class="responsive-table" style="width: 40%; font-size:14px;">';
        $output.='<tr>';
                  $output.='<td style="font-weight: bold;">Serial No.</td>';
                  if($query_results[0]->serial_no !=''){
                     $output.='<td>'.$query_results[0]->serial_no.'</td>';
                  }
                  else{
                     $output.='<td> ------ </td>';
                  }
        $output.= '</tr>';
        $output.='<tr>';
                  $output.='<td style="font-weight: bold;">Tag No.</td>';
                  if($query_results[0]->tag_no !=''){
                     $output.='<td>'.$query_results[0]->tag_no.'</td>';
                  }
                  else{
                     $output.='<td> ------ </td>';
                  }
        $output.= '</tr>';
        $output.='<tr>';
        $output.='<td style="font-weight: bold;">Type</td>';
        $output.='<td>'.$query_results[0]->type.'</td>';
        $output.='<tr>';
                  $output.='<td style="font-weight: bold;">Brand/ Make</td>';
                  if($query_results[0]->brand !=''){
                     $output.='<td>'.$query_results[0]->brand.'</td>';
                  }
                  else{
                     $output.='<td> ------ </td>';
                  }
        $output.= '</tr>';
        $output.= '</tr>';
        $output.='</table>';
        $output.= '</div>';
        $output.= '</div>';
        return $output;
    }
   /* 8*/ public function search_equipment(Request $request)
    {
        $query_results = '';
        $hardware_equipment = new hardware_equipment;

        if (!empty($request->tag_no)) { 
            $query_results = $hardware_equipment::flexible_search_non_sensitive('tag_no',$request->tag_no); 
        }
        else if (!empty($request->serial_no)) { 
            $query_results = $hardware_equipment::flexible_search_non_sensitive('serial_no',$request->serial_no);
        }
        else if (!empty($request->type)) { 
            $query_results = $hardware_equipment::flexible_search_non_sensitive('type',$request->type);
        }
        else if (!empty($request->category)) { 
            $query_results = $hardware_equipment::flexible_search_non_sensitive('category',$request->category);
        }
        return view('module2.equipment.home',compact('query_results'));
    }
}
