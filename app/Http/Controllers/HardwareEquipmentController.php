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
        $error_warning = $hardware_equipment::get_error_warning($request->tag_no, $request->serial_no);
        
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

            $add_new = $hardware_equipment::create_new($request->tag_no, $request->serial_no,$request->category,$request->type,$request->origin,$request->mac_addres,$request->description,$photo_name,$request->status,$request->date_acquired, $qrcode_name, $request->brand);

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
    public function update(Request $request, hardware_equipment $hardware_equipment)
    {
        //
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
    public function list_view_equipment()
    {
        $hardware_equipment = new hardware_equipment;
        $query_results = $hardware_equipment::list_view_equipment();
        return view('module2.equipment.view',compact('query_results'));
       
    }
    
}
