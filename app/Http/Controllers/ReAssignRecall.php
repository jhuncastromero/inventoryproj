<?php

namespace App\Http\Controllers;


use App\deployment_it;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Filesystem;
use Intervention\Image\Facades\Image;

class ReAssignRecall extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    //USER DEFINED FUNCTION

    public static function re_assign_deploy() {

        $deployment_it  = new deployment_it;
        $department = $deployment_it::load_deptcode();
        $load_categories = $deployment_it::load_categories();
        return view('module3.deployment_it.re_assign_deployment',compact('load_categories','department'));

    }

    public static function ajax_view_equipment_serials(Request $request) {


        $hardware = '';
        $deployment_it = new deployment_it;
        $query_hardware = $deployment_it::get_hardware_serials($request->type);
        

        if($query_hardware->isEmpty()) {
                 $hardware = "<p style='font-style:italic;font-size:13px;font-weight:bold; padding-top:30px;'>No Hardware Record found for this Type.</p>";
        }
        else {
             $hardware='<table class="responsive-table" style="font-size:12px;" id="tbl_equipment" name="tbl_equipment">';
             $hardware.='<thead><tr>';
             $hardware.='<th> <i class="small material-icons">photo</i> </th>';
             $hardware.=' <th>Serial No.  </th>';
             $hardware.=' <th style="padding-left:30px;">Type  </th>';
             $hardware.=' <th>Brand/ Make  </th>';
             $hardware.='</thead></tr>';
             $hardware.='<tbody>';
             foreach($query_hardware as $list) {
                        
                             $hardware.='<tr>';
                             if($list->photo_name == '') {

                                 $hardware.='<td><i>---no photo---</i></td>';

                             }
                             else {
                                 $hardware.='<td><img src="'. asset(Storage::url('hardware_photo/IT/'.$list->photo_name)).'" width="30px" height="30px"></td>';
                             }
                            
                             $hardware.="<td><a href='#!'".'onclick=view_equipment_deployment_details("'.$list->serial_no.'");>'.$list->serial_no.'</a></td>';
                              $hardware.='<td style="padding-left:20px;">'.$list->type.'</td>';
                             $hardware.='<td>'.$list->brand.'</td>';
                             $hardware.= '</tr>';
             }
             $hardware.='</tbody>';
             $hardware.='</table>';
        }

        return $hardware;
       
    
    }

    public static function ajax_view_equipment_redeployment_details(Request $request) {

        $hardware = '';
        $hardware_photo = '';
        $current_user ='';
        $current_user_display='';
        $re_assign_display = '';
        $deployment_it = new deployment_it;
        $personnel_info = '';
        $hadware_info = '';
        $query_hardware = $deployment_it::ajax_view_deployment_by_equipment($request->serial_no);
        

        if($query_hardware->isEmpty()) {
                 $hardware = "<p style='font-style:italic;font-size:13px;font-weight:bold; padding-top:30px;'>No Record Found.</p>";

                 return [$hardware, 0];
        }
        else {

             //CURRENT USER ---------------------------------------------------------------------->

            // get hardware details   

             $hardware_info = $deployment_it::get_hardware_info($request->serial_no);
             if($hardware_info[0]->photo_name == '') {
                
                $hardware_photo= '<i style="font-size:20px;">-- no photo -- </i>';
             }
             else {
                
                $hardware_photo='<img src="'. asset(Storage::url('hardware_photo/IT/'.$hardware_info[0]->photo_name)).'" width="250px" height="250px" style="padding-top:50px;">';
                
             }
                        
            $hardware='<div style="font-weight:bold;padding-top:20px; font-size:18px;">'.$hardware_info[0]->brand. ' '.$hardware_info[0]->type.' - S/N:  '.$hardware_info[0]->serial_no .'</div>';

            //get current user 
             
            $current_user = $deployment_it::get_current_user($request->serial_no);
            if ($current_user[0]->emp_id =='') {

            }
            else {

                $current_user_display = '<div style="color:#ffffff; background-color:#c62828;"> <p style="padding-bottom:5px;padding-left:5px; font-size:13px;">Current User </p></div>';
                $current_user_display .= '<div class="col s4">';
                $personnel_info = $deployment_it::get_personnel_info($current_user[0]->emp_id);
  
                if(!empty($personnel_info[0]->photo_name))
                {
                     $current_user_display.='<img src="'. asset(Storage::url('personnel_photo/'.$personnel_info[0]->emp_id.'/'.$personnel_info[0]->photo_name)).'" width="175px" height="175px">';
                }
                else
                {
                     $current_user_display.='<i class="medium material-icons">person</i>';
                 
                } 
                 $current_user_display.='</div>';
                 $current_user_display .= '<div class="col s7">';
                 $current_user_display.='<table class="responsive-table" style="width:90%; font-size:11px;">';
                 $current_user_display.= '</tr>';   
                 $current_user_display.= '<tr>';
                 $current_user_display.= '<td>ID No.</td>';
                 $current_user_display.= '<td>'.$personnel_info[0]->emp_id.'</td>';
                 $current_user_display.= '</tr>';   
                 $current_user_display.= '<tr>';
                 $current_user_display.= '<td>Name</td>';
                 $current_user_display.= '<td>'.$personnel_info[0]->last_name.', '.$personnel_info[0]->first_name.' '.$personnel_info[0]->middle_initial.'</td>';
                 $current_user_display.= '<tr>';
                 $current_user_display.= '<td>Position</td>';
                 $current_user_display.= '<td>'.$personnel_info[0]->job_position.'</td>';
                 $current_user_display.= '</tr>';   
                 $current_user_display.= '</tr>'; 
                 $current_user_display.= '<tr>';
                 $current_user_display.= '<td>Department</td>';
                    $deptname = deployment_it::get_deptname($personnel_info[0]->deptcode);
                 $current_user_display.='<td>'.$deptname[0]->deptname.'</td>';
                 $current_user_display.= '</tr>';  
                 $current_user_display.='</table>';
                 $current_user_display.='</div>';
                                
           }

         return [$hardware, $hardware_photo, $current_user_display];
                        
        }
        
 
    }



    public static function ajax_personnel_list_deptcode(Request $request) {



    }

   
}
