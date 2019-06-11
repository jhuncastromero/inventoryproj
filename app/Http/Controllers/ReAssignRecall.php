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

   public function reassign_equipment() {

        $deployment_it = new deployment_it;
        $load_deptcode = $deployment_it::load_deptcode();
        $load_categories = $deployment_it::load_categories();
        return view('module3.deployment_it.re_assign_deployment',compact('load_categories','load_deptcode'));

   } 
   
   public function ajax_equipment_type(Request $request)
   {
       $display_data = '';
       $deployment_it = new deployment_it;
       $query = $deployment_it::get_hardware_serials($request->type);

       if($query->isEmpty()) {

             $display_data = "<p style='font-style:italic;font-size:13px;font-weight:bold; padding-top:10px;'>No Hardware Equipment Type Found.</p>";
           
       }
       else {

             $display_data='<table class="responsive-table" style="width:50%; font-size:12px;">';
             $display_data.='<thead><tr>';
             $display_data.='<th> <i class="small material-icons">photo</i></th>';
             $display_data.='<th>Serial No.  </th>';
             $display_data.='<th>Type</th>';
             $display_data.='<th>Brand/ Make</th>';
             $display_data.='</thead></tr>';
             
             foreach($query as $list) {
                $display_data.='<tr>';
                if($list->photo_name == '') {

                        $display_data.='<td><i>---no photo---</i></td>';

                }
                else {
                    $display_data.='<td><img src="'. asset(Storage::url('hardware_photo/IT/'.$list->photo_name)).'" width="50px" height="50px"></td>';
                }
                $display_data.="<td><a href='#!'".'onclick=get_hardware_detail("'.$list->serial_no.'");>'.$list->serial_no.'</a></td>';
                $display_data.='<td>'.$list->type.'</td>';
                $display_data.='<td>'.$list->brand.'</td>';
                $display_data.='<tr>';
             }
             $display_data.='</table>';
       }
       return $display_data;

   }
   public function ajax_equipment_detail(Request $request) {

        $display_photo = '';
        $display_data = '';

        $deployment_it = new deployment_it;
        $query = $deployment_it::get_hardware_info($request->serial_no);


        if($query[0]->photo_name == '') {
                
                $display_photo = '<i style="font-size:20px;">-- no photo -- </i>';
        }
        else {
                
                $display_photo ='<img src="'. asset(Storage::url('hardware_photo/IT/'.$query[0]->photo_name)).'" width="250px" height="250px">';
                

        }
         $display_data='<div style="font-weight:bold;padding-bottom:20px;">'.$query[0]->brand. ' '.$query[0]->type.' - S/N:  '.$query[0]->serial_no .'</div>';

        $query = $deployment_it::get_current_user($request->serial_no);
        if($query->isEmpty()) {

            $display_data = "<p style='font-style:italic;font-size:13px;font-weight:bold; padding-top:10px;'>No Current Assignment/ Deployment found.</p>";
        }
        else {

             $display_data.='<div style="font-size:13px; font-weight:bold; padding-bottom:10px;">Hardware Equipment Current User</div>';
             $display_data.='<table class="responsive-table" style="width:90%; font-size:12px;">';
             $display_data.='<thead><tr>';
             $display_data.='<th> <i class="small material-icons">photo</i></th>';
             $display_data.='<th>ID No.</th>';
             $display_data.='<th>Name</th>';
             $display_data.='<th>Department</th>';
             $display_data.='<th>Date Assigned</th>';
             $display_data.='<th><center>Action</center></th>';
             $display_data.='</thead></tr>';
             $display_data.='<tr>';
             $personnel_info = $deployment_it::get_personnel_info($query[0]->emp_id);
             $dept_info = $deployment_it::get_deptname($query[0]->deptcode);

             if(!empty($personnel_info[0]->photo_name)) {

                $display_data.='<td><img src="'. asset(Storage::url('personnel_photo/'.$personnel_info[0]->emp_id.'/'.$personnel_info[0]->photo_name)).'" width="50px" height="50px"></td>';
             }
             else {
                 
                 $display_data.='<td><i class="medium material-icons">person</i></td>';
                             
             }
             $display_data.='<td>'.$query[0]->emp_id.'</td>';
             $display_data.='<td>'.$personnel_info[0]->last_name.', '.$personnel_info[0]->first_name.' '.$personnel_info[0]->middle_initial.'</td>';
             $display_data.='<td>'.$dept_info[0]->deptname.'</td>';
             $display_data.='<td>'.$query[0]->date_deployed.'</td>';
             $display_data.='<td><center><a class="btn btn-small" onclick="open_modal();">Re-Assign</center></a></td>';
             $display_data.='<tr>';
             $display_data.='</table>';
        }

        return [ $display_photo, $display_data ];
   }

   public function ajax_personnel_list(Request $request) {

       $display_data = '';
       $deployment_it = new deployment_it;
       $query = $deployment_it::get_personnel_deptcode($request->deptcode);
       
       if($query->isEmpty()) {

             $display_data = "<p style='font-style:italic;font-size:13px;font-weight:bold; padding-top:10px;'>No Personnel Record Found.</p>";
           
       }
       else {
             
             $dept_info = $deployment_it::get_deptname($query[0]->deptcode);
             $display_data='<table class="responsive-table" style="width:90%; font-size:12px;">';
             $display_data.='<thead><tr>';
             $display_data.='<th> <i class="small material-icons">photo</i></th>';
             $display_data.='<th>ID No.</th>';
             $display_data.='<th>Name </th>';
             $display_data.='<th>Department</th>';
             $display_data.='</thead></tr>';
             
             foreach($query as $list) {
                $display_data.='<tr>';
                if($list->photo_name == '') {

                        $display_data.='<td><i>---no photo---</i></td>';

                }
                else {
                    $display_data.='<td><img src="'. asset(Storage::url('personnel_photo/'.$list->emp_id.'/'.$list->photo_name)).'" width="50px" height="50px"></td>';
                }
                $display_data.='<td>'.$list->emp_id.'</td>';
                $display_data.='<td>'.$list->last_name.', '.$list->first_name.' '.$list->middle_initial.'</td>';
                $display_data.='<td>'.$dept_info[0]->deptname.'</td>';
                $display_data.='<tr>';
             }
             $display_data.='</table>';
       }
       return $display_data;

   }

}
