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

class DeploymentItController extends Controller
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
        
        $serial_no = '';
        $flag = '';
        $resultSet = '';
        $remarks = '';

        $deployment_it = new deployment_it;
        $resultSet = $deployment_it::load_categories();
        $query_result = $deployment_it::save_data($request->hidden_serial_no, $request->hidden_emp_id, $request->hidden_deptcode, $request->hidden_room_no, $request->remarks );
        $flag = $query_result;
        return view('module3.deployment_it.deploy',compact('resultSet','serial_no','flag', 'remarks'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\deployment_it  $deployment_it
     * @return \Illuminate\Http\Response
     */
    public function show(deployment_it $deployment_it)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\deployment_it  $deployment_it
     * @return \Illuminate\Http\Response
     */
    public function edit(deployment_it $deployment_it)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\deployment_it  $deployment_it
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, deployment_it $deployment_it)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\deployment_it  $deployment_it
     * @return \Illuminate\Http\Response
     */
    public function destroy(deployment_it $deployment_it)
    {
        //
    }

    //USER DEFINED FUNCTIONS
    public function deploy_equipment() { // assign-deploy IT equipment function
  
        $deployment_it = new deployment_it;
        $resultSet = $deployment_it::load_categories();
        $serial_no = '';
        $flag = '';
        $remarks = '';
        return view('module3.deployment_it.deploy',compact('resultSet','serial_no','flag','remarks'));

    }

    public function ajax_equipment_list(Request $request) {

        
     $deployment_it = new deployment_it;
     $ajax_result = $deployment_it::ajax_equipment_generate_list($request->type);

        if ($ajax_result->count()) {
                $output = '<div style="font-size:13px;font-weight:bold;"><i class="material-icons">view_comfy</i>&nbsp;&nbsp;'.$request->type.' Available for Deployment</div>';
                $output.='<table class="responsive-table" style="width: 50%; font-size:12px;">';
                foreach($ajax_result as $list) {

                            $output.='<tr>';  
                             $output.='<td><img src="'.asset(Storage::url('hardware_photo/'.$ajax_result[0]->category.'/'.$ajax_result[0]->photo_name)).'" width="40px" height="40px">';
                            $output.='</td>';
                            $output.='<td style="color:#0288d1;">Serial No.</td>';        
                            $output.='<td>'.$list->serial_no.'</td>';
                            $output.='<td><button class="btn-small" type="" name="" onclick=get_serial_no("'.$list->serial_no.'");><i class="material-icons">chevron_right</i> </button></td>';
                            $output.= '</tr>'; 

                 }

        } 
        else {

                    $output = '<p style="font-size:13px;"><i> All '. $request->type. ' were already assigned/ deployed.</i></p>';
                
        }

                    
     return $output;

    }

    public static function deploy_details(Request $request) {

         $remarks = '';
         $deployment_it = new deployment_it;
         $query_result = $deployment_it::flexible_search_hardware('serial_no',$request->hidden_serial_no);
         return view('module3.deployment_it.deploy_details',compact('query_result','remarks'));
    }

    public static function ajax_find_employee(Request $request)
    {
        
        $query_result = '';
        $output = '';
        $emp_id ='';
        $deptcode ='';
        $deptname = '';
        $roomno = '';

        $deployment_it = new deployment_it;
        $query_result = $deployment_it::search_employee_details($request->emp_id, $request->emp_lastname);

        if($query_result->isEmpty())
        {
             $output = "<p><i>Employee Lastname / ID No. not found. Please check your entry</i></p>";
        }
        else
        {
            $emp_id =  $query_result[0]->emp_id;
            $deptcode = $query_result[0]->deptcode;
            
            $output = '<div class="row">';
            $output .= '<div style="font-size:13px;"> You are Assigning the Hardware Equipment to this Personnel. Are you sure you want to proceed?</div>';
            $output .= '<div class="col s3 m3 l3">';
                $output .= '<div style="padding-top:10px;">';

                    if(!empty($query_result[0]->photo_name))
                    {
                         $output.='<img src="'. asset(Storage::url('personnel_photo/'.$query_result[0]->emp_id.'/'.$query_result[0]->photo_name)).'" width="100px" height="100px">';
                    }
                    else
                    {
                         $output.='<i class="medium material-icons">person</i>';
                     
                    }
                $output .= '</div>';
             $output .= '</div>';

             $output .= '<div class="col s10 m10 l10">';
                        $output .= '<div style="font-size:24px;padding-top:18px;">'.$query_result[0]->first_name.' '.$query_result[0]->middle_initial.'. '.$query_result[0]->last_name.'</div>';
                        $output .= '<div style="font-size:16; font-style:italic;">'.$query_result[0]->job_position.'</div>';

                        $deptname = $deployment_it::get_deptname($query_result[0]->deptcode);
                        $roomno = $deptname[0]->roomno; //get room no. of the department
                        $output .= '<div style="font-size:13px; font-style:italic;">'.$deptname[0]->deptname.' Department</div>';
             $output .= '</div>';
            
             $output .= '</div>';
          
        }

        return [$output, $emp_id, $deptcode, $roomno];
            //  0           1        2          3
        

      
    }

    public static function view_deployment() {

        return view('module3.deployment_it.view_deployment');
    }

    public static function view_personnel_deployment () {

        return view('module3.deployment_it.deploy_personnel_view');
    }
    
    public static function ajax_view_personnel_deployment_details(Request $request) {

        $output = '';
        $personal = '';
        $hardware ='';
        $hardware_info = '';
        $emp_id='';

    

        $deployment_it = new deployment_it;
        $query_result = $deployment_it::ajax_view_deployment_by_personnel($request->emp_id, $request->lastname);

        
        if(empty($query_result)){
        
            $output = "<p style='font-style:italic;font-size:13px;font-weight:bold;'>Personnel ID/ Lastname not found. Please check your entry.</p>";

             return [$output, 0]; 

        }
        else {
             
             $emp_id = $query_result[0]->emp_id;
            //for column 1 - picture of personnel and brief info

            $personal = '<div class="row">';
            $personal .= '<div style="padding-top:10px;">';

                if(!empty($query_result[0]->photo_name))
                {
                     $personal.='<img src="'. asset(Storage::url('personnel_photo/'.$query_result[0]->emp_id.'/'.$query_result[0]->photo_name)).'" width="150px" height="150px">';
                }
                else
                {
                     $personal.='<i class="medium material-icons">person</i>';
                 
                }
            $personal .= '</div>';
            $personal .= '</div>';

            $personal .= '<div class="row">';
            $personal .= '<div style="font-size:15px;padding-top:5px; font-weight:bold;">'.$query_result[0]->first_name.' '.$query_result[0]->middle_initial.'. '.$query_result[0]->last_name.'</div>';
            $personal .= '<div style="font-size:12px; font-style:italic;">'.$query_result[0]->emp_id.'</div>';
            $personal .= '<div style="font-size:12px; font-style:italic;">'.$query_result[0]->job_position.'</div>';
            $personal .= '</div>';


             //for column 2 - Assigned/ Deployed Hardware Equipment
            $query_hardware = $deployment_it::get_assigned_hardware($emp_id);

            if($query_hardware->isEmpty()) {
                 $hardware = "<p style='font-style:italic;font-size:13px;font-weight:bold; padding-top:30px;'>No Hardware Equipment deployed/assigned to this personnel.</p>";
            }
            else {

                 $hardware='<div style="font-weight:bold;padding-bottom:10px; font-size:14px;">Assigned/ Deployed Hardware Equipment</div>';
                 $hardware.='<table class="responsive-table" style="width:80%; font-size:12px;">';
                 $hardware.='<thead><tr>';
                 $hardware.='<th> <i class="small material-icons">photo</i> </th>';
                 $hardware.=' <th> Serial No.  </th>';
                 $hardware.=' <th> Tag No.  </th>';
                 $hardware.=' <th> Brand/ Make  </th>';
                 $hardware.=' <th> Date Assigned</th>';
                 $hardware.='</thead></tr>';

                 foreach($query_hardware as $list) {
                             $hardware_info = $deployment_it::get_hardware_info($list->serial_no);
                             $hardware.='<tr>';
                                 if($hardware_info[0]->photo_name == '') {

                                     $hardware.='<td><i>---no photo---</i></td>';

                                 }
                                 else {
                                     $hardware.='<td><img src="'. asset(Storage::url('hardware_photo/IT/'.$hardware_info[0]->photo_name)).'" width="30px" height="30px"></td>';
                                 }
                                
                                 $hardware.='<td>'.$list->serial_no.'</td>';
                                 $hardware.='<td>'.$hardware_info[0]->tag_no.'</td>';
                                 $hardware.='<td>'.$hardware_info[0]->brand.'</td>';
                                 $hardware.='<td>'.$list->date_deployed.'</td>';
                             $hardware.= '</tr>';
                 }

                 $hardware.='</table>';
            }

            return [$personal, $hardware];
          }

    }

    public static function  view_equipment_deployment () {

        $deployment_it = new deployment_it;
        $load_categories = $deployment_it::load_categories();

        return view('module3.deployment_it.deploy_equipment_view',compact('load_categories'));
    }

    public static function ajax_view_equipment_serials(Request $request) {


        $hardware = '';
        $deployment_it = new deployment_it;
        $query_hardware = $deployment_it::get_hardware_serials($request->type);
        

        if($query_hardware->isEmpty()) {
                 $hardware = "<p style='font-style:italic;font-size:13px;font-weight:bold; padding-top:30px;'>No Hardware Record found for this Type.</p>";
        }
        else {
             $hardware='<table class="responsive-table" style="width:70%; font-size:12px;">';
             $hardware.='<thead><tr>';
             $hardware.='<th> <i class="small material-icons">photo</i></th>';
             $hardware.=' <th> Serial No.  </th>';
             $hardware.=' <th> Type  </th>';
             $hardware.=' <th> Brand/ Make  </th>';
             $hardware.='</thead></tr>';

             foreach($query_hardware as $list) {
                        
                             $hardware.='<tr>';
                             if($list->photo_name == '') {

                                 $hardware.='<td><i>---no photo---</i></td>';

                             }
                             else {
                                 $hardware.='<td><img src="'. asset(Storage::url('hardware_photo/IT/'.$list->photo_name)).'" width="30px" height="30px"></td>';
                             }
                            
                             $hardware.="<td><a href='#!'".'onclick=view_equipment_deployment_details("'.$list->serial_no.'");>'.$list->serial_no.'</a></td>';
                              $hardware.='<td>'.$list->type.'</td>';
                             $hardware.='<td>'.$list->brand.'</td>';
                             $hardware.= '</tr>';
             }

             $hardware.='</table>';
        }

        return $hardware;
       
    
    }


    public static function ajax_view_equipment_deployment_details(Request $request) {

        $hardware = '';
        $hardware_photo = '';
        $deployment_it = new deployment_it;
        $personnel_info = '';
        $hadware_info = '';
        $query_hardware = $deployment_it::ajax_view_deployment_by_equipment($request->serial_no);
        

        if($query_hardware->isEmpty()) {
                 $hardware = "<p style='font-style:italic;font-size:13px;font-weight:bold; padding-top:30px;'>No Record Found.</p>";

                 return [$hardware, 0];
        }
        else {

            
             $hardware_info = $deployment_it::get_hardware_info($request->serial_no);

             if($hardware_info[0]->photo_name == '') {
                
                $hardware_photo = '<i style="font-size:20px;">-- no photo -- </i>';
             }
             else {
                
                $hardware_photo ='<img src="'. asset(Storage::url('hardware_photo/IT/'.$hardware_info[0]->photo_name)).'" width="250px" height="250px">';
                

             }
             

             
             $hardware='<div style="font-weight:bold;padding-bottom:10px;">'.$hardware_info[0]->brand. ' '.$hardware_info[0]->type.' - S/N:  '.$hardware_info[0]->serial_no .'</div>';
             $hardware.='<div style="font-weight:bold;font-size:12px; padding-bottom:10px;padding-top:20px">Deployment/ Assignment Record</div>';
             $hardware.='<table class="responsive-table" style="width:80%; font-size:12px;">';
             $hardware.='<thead><tr>';
             $hardware.='<th> <i class="small material-icons">photo</i> </th>';
             $hardware.=' <th> ID No.  </th>';
             $hardware.=' <th> Assigned to  </th>';
             $hardware.=' <th> Department  </th>';
             $hardware.=' <th> Date Assigned </th>';
             $hardware.='</thead></tr>';

             foreach($query_hardware as $list) {
                        
                            $personnel_info = $deployment_it::get_personnel_info($list->emp_id);
                            $hardware.='<tr>';
                            if(!empty($personnel_info[0]->photo_name))
                            {
                                 $hardware.='<td><img src="'. asset(Storage::url('personnel_photo/'.$personnel_info[0]->emp_id.'/'.$personnel_info[0]->photo_name)).'" width="30px" height="30px"></td>';
                            }
                            else
                            {
                                 $hardware.='<td><i class="medium material-icons">person</i></td>';
                             
                            }
                            
                             $hardware.='<td><a href="#!" onclick=personnel_details("'.$personnel_info[0]->emp_id.'");>'.$personnel_info[0]->emp_id.'</a></td>';
                            
                             $hardware.='<td>'.$personnel_info[0]->last_name.', '.$personnel_info[0]->first_name.' '.$personnel_info[0]->middle_initial.'.' .'</td>';
                             $deptname = deployment_it::get_deptname($personnel_info[0]->deptcode);
                             $hardware.='<td>'.$deptname[0]->deptname.'</td>';
                             $hardware.='<td>'.$list->date_deployed.'</td>';
                             $hardware.= '</tr>';
             }

             $hardware.='</table>';

            return [$hardware, $hardware_photo];
             
        }

  
    }

   
    public static function ajax_view_personnel_details(Request $request) {

        $deployment_it = new deployment_it;
        $query_personnels = $deployment_it::get_personnel_info($request->emp_id);

        $output = '<div class="row">';
        $output .= '<div class="col s3 m3 l3">';
           
                if(!empty($query_personnels[0]->photo_name))
                {
                     $output.='<img src="'. asset(Storage::url('personnel_photo/'.$query_personnels[0]->emp_id.'/'.$query_personnels[0]->photo_name)).'" width="150x" height="150px">';
                }
                else
                {
                     $output.='<i class="medium material-icons">person</i>';
                 
                }
           // $output .= '</div>';
        $output .= '</div>';

        $output .= '<div class="col s9 m9 l9">';
                    $output .= '<div style="font-size:26px;padding-top:40px;">'.$query_personnels[0]->first_name.' '.$query_personnels[0]->middle_initial.'. '.$query_personnels[0]->last_name.'</div>';
                    $output .= '<div style="font-size:18; font-style:italic;">'.$query_personnels[0]->job_position.'</div>';
                    $deptname = deployment_it::get_deptname($query_personnels[0]->deptcode);
                    $output .= '<div style="font-size:14px;font-style:italic;">'.$deptname[0]->deptname.' Department</div>';
        $output .= '</div>';
        
        $output .= '</div>';
        return $output;

    }

 
}
