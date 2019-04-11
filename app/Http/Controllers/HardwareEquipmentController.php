<?php

namespace App\Http\Controllers;

use App\hardware_equipment;
use Illuminate\Http\Request;

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

        if ($request->category == 'IT'){

            $photo_name = $request->serial_no;
            $qrcode_name= $request->serial_no;
        }
        else {

            $photo_name = $request->tag_no;
            $qrcode_name= $request->tag_no;

        }   
        
        $hardware_equipment = new hardware_equipment;

        $error_warning = $hardware_equipment::create_new($request->tag_no, $request->serial_no,$request->category,$request->type,$request->origin,$request->mac_addres,$request->description,$photo_name,$request->status,$request->date_acquired, $qrcode_name, $request->brand);

        if($error_warning == 0) {

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
    public function show(hardware_equipment $hardware_equipment)
    {
        //
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
}
