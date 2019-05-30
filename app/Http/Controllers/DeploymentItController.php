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



}
