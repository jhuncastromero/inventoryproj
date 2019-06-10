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

             //CURRENT USER ---------------------------------------------------------------------->\

            // get hardware details   

             $hardware_info = $deployment_it::get_hardware_info($request->serial_no);
             if($hardware_info[0]->photo_name == '') {
                
                $hardware_photo= '<i style="font-size:20px;">-- no photo -- </i>';
             }
             else {
                
                $hardware_photo='<img src="'. asset(Storage::url('hardware_photo/IT/'.$hardware_info[0]->photo_name)).'" width="180px" height="180px">';
                
             }
                        
            $hardware='<div style="font-weight:bold;padding-top:70px; font-size:20px;">'.$hardware_info[0]->brand. ' '.$hardware_info[0]->type.' - S/N:  '.$hardware_info[0]->serial_no .'</div>';

            //get current user 
             
            $current_user = $deployment_it::get_current_user($request->serial_no);
            if ($current_user[0]->emp_id =='') {

            }
            else {

                $current_user_display = '<div style="color:#ffffff; background-color:#c62828;"> <p style="padding-bottom:5px;padding-left:10px; font-size:13px;">Current User </p></div>';
                $current_user_display .= '<div>';
                $personnel_info = $deployment_it::get_personnel_info($current_user[0]->emp_id);

                if(!empty($personnel_info[0]->photo_name))
                {
                     $current_user_display.='<img src="'. asset(Storage::url('personnel_photo/'.$personnel_info[0]->emp_id.'/'.$personnel_info[0]->photo_name)).'" width="150px" height="150px">';
                }
                else
                {
                     $current_user_display.='<i class="medium material-icons">person</i>';
                 
                } 
                 $current_user_display.='</div>';

                 $current_user_display.='<table class="responsive-table" style="width:80%; font-size:11px;">';
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
                                
           }

           // RE ASSIGN TO--------------------------------------------------------------------------->

                  
          return [$hardware, $hardware_photo, $current_user_display];

                        
        }
        

  
    }

   
}
