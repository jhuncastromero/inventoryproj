<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\type_tbl;
use App\hardware_equipment;
use App\personnel;
use App\department;
use Carbon\Carbon;

class deployment_it extends Model
{
    //

   protected $dates =['deleted_at'];
   protected $fillable = ['serial_no','emp_id','deptcode','roomno','remarks','recalled_reason','date_deployed','date_recalled'];

    public static function load_categories() {  /// this searches or uses TYPE table 

    	$types = new type_tbl;
    	$query = $types::whereNull('deleted_at')->get();
    	return $query;
    }

    public static function ajax_equipment_generate_list($type) {

    	$query='';
        $hardware_equipment = new hardware_equipment;
    	$query = $hardware_equipment::where('type','=',$type)->where('status','=','unassigned')->whereNull('deleted_at')->get();
    	return $query;
    }
     public static function flexible_search_hardware($criteria, $value) {

        $hardware_equipment = new hardware_equipment;
        $query = $hardware_equipment::where($criteria, '=',$value)->get();
        return $query;
    }

    public static function search_employee_details($emp_id, $emp_lastname) {

        $query = '';        
        $personnel = new personnel;
        if($emp_id !='') {
            $query = $personnel::where('emp_id', '=', $emp_id)->get();
        }
        else if($emp_lastname != '') {
            $query = $personnel::where('last_name', '=', $emp_lastname)->get();
        }

        return $query;
        
    }
    

    public static function save_data($serial_no, $emp_id, $deptcode, $roomno, $remarks) {

        $date_today = Carbon::now('UTC');
        deployment_it::create(['serial_no' => $serial_no, 'emp_id' => $emp_id, 'deptcode' => $deptcode, 'roomno' => $roomno, 'date_deployed' =>$date_today,'remarks' => $remarks]);
        
        //change the status of hardware deployed to ASSIGNED
        $hardware_change_status = deployment_it::hardware_equipment_change_status($serial_no);

        return 1;
    }

    public static function hardware_equipment_change_status($serial_no) {

        $hardware_equipment = new hardware_equipment;
        $id = $hardware_equipment::where('serial_no', '=', $serial_no)->get();

        $query = $hardware_equipment::find($id[0]->id);
        $query->status = 'assigned';
        $query->save();
    }

    public static function deployment_it_reassign_update($serial_no) {
        
        $deployment_it = new deployment_it;
        $query = $deployment_it::get_current_user($serial_no);
        $update_deploy_it_reassignment = $deployment_it::find($query[0]->id);
        $update_deploy_it_reassignment->date_recalled = now();
        $update_deploy_it_reassignment->save();


    }

    public static function hardware_equipment_change_status_unassigned($serial_no) {

        $hardware_equipment = new hardware_equipment;
        $id = $hardware_equipment::where('serial_no', '=', $serial_no)->get();

        $query = $hardware_equipment::find($id[0]->id);
        $query->status = 'unassigned';
        $query->save();
    }
     public static function deployment_it_recall_update($serial_no) {

      $deployment_it = new deployment_it;
      $query1 = $deployment_it::where('serial_no','=',$serial_no)->whereNull('date_recalled')->whereNull('deleted_at')->orderBy('created_at','DESC')->get();

      $query2 = $deployment_it::find($query1[0]->id);
      $query2->date_recalled = now();
      $query2->save();

    }

       public static function ajax_view_deployment_by_personnel($emp_id, $lastname) {

        if($emp_id !='') {
            $personnel = new personnel;
            $personal_info = $personnel::where('emp_id','=',$emp_id)->get();
          
            if($personal_info->count())
            {
                 return $personal_info;
            }

           
        }
        else {

            $personal_info = '';
            $deployment_query ='';

            $personnel = new personnel;
            $personal_info = $personnel::where('last_name','=',$lastname)->get();
          
            if($personal_info->count())
            {
                 return $personal_info;
            }

        }
    }

    public static function get_deptname($deptcode)  {

        $department = new department;
        $query = $department::where('deptcode','=',$deptcode)->get();
        return $query;

    }

    public static function get_assigned_hardware($emp_id) {

      
      $deployment_it  = new deployment_it;
      $query = $deployment_it::where('emp_id', '=', $emp_id)->OrderBy('created_at','DESC')->get();
      return $query;

    }
    public static function get_assigned_personnel_by_month_year($emp_id, $month, $year) {

      
      $deployment_it  = new deployment_it;
      $query ='';
      if(($month != '') && ($year != '')) {
         $query = $deployment_it::where('emp_id', '=', $emp_id)->whereMonth('created_at',$month)->whereYear('created_at',$year)->OrderBy('created_at','DESC')->get();

      }
      else if($month != '') {
        $query = $deployment_it::where('emp_id', '=', $emp_id)->whereMonth('created_at',$month)->OrderBy('created_at','DESC')->get();
      }
      else if ($year !='') {
        $query = $deployment_it::where('emp_id', '=', $emp_id)->whereYear('created_at',$year)->OrderBy('created_at','DESC')->get();
      }
      
      return $query;

    }

    public static function get_assigned_hardware_by_month_year($serial_no, $month, $year) {

          $deployment_it = new deployment_it;
          $query ='';
          if(($month != '') && ($year != '')) {
             $query = $deployment_it::where('serial_no', '=', $serial_no)->whereMonth('created_at',$month)->whereYear('created_at',$year)->OrderBy('created_at','DESC')->get();

          }
          else if($month != '') {
            $query = $deployment_it::where('serial_no', '=', $serial_no)->whereMonth('created_at',$month)->OrderBy('created_at','DESC')->get();
          }
          else if ($year !='') {
            $query = $deployment_it::where('serial_no', '=', $serial_no)->whereYear('created_at',$year)->OrderBy('created_at','DESC')->get();
          }
          
          return $query;
       
    }
    public static function get_hardware_info($serial_no) {

        $hardware_equipment = new hardware_equipment;
        $query = $hardware_equipment::where('serial_no', '=',$serial_no)->whereNull('deleted_at')->get();
        return $query;
    }
   
    public static function get_hardware_serials($type) {

        $hardware_equipment = new hardware_equipment;
        $query = $hardware_equipment::where('type', '=',$type)->whereNull('deleted_at')->get();
        return $query;

    }


    public static function ajax_view_deployment_by_equipment($serial_no) {
        
        $deployment_it = new deployment_it;
        $query = $deployment_it::where('serial_no','=',$serial_no)->whereNull('deleted_at')->OrderBy('created_at','DESC')->get();
        return $query;
       
   }      

    public static function get_personnel_info($emp_id) {

        $personnel = new personnel;
        $query = $personnel::where('emp_id', '=',$emp_id)->whereNull('deleted_at')->get();
        return $query;
    }

    public static function get_all_personnel() {

        $personnel = new personnel;
        $query = $personnel::whereNull('deleted_at')->get();
        return $query;
    }

    public static function get_all_hardware() {

        $hardware_equipment = new hardware_equipment;
        $query = $hardware_equipment::whereNull('deleted_at')->get();
        return $query;
    }

    public static function get_current_user($serial_no) {

        $deployment_it = new deployment_it;
        $query = $deployment_it::where('serial_no','=',$serial_no)->whereNull('date_recalled')->whereNull('deleted_at')->orderBy('created_at','DESC')->get();
        return $query;
    }
     public static function get_current_user_recall_code($serial_no) {

        $deployment_it = new deployment_it;
        $query = $deployment_it::where('serial_no','=',$serial_no)->whereNull('date_recalled')->whereNull('deleted_at')->orderBy('created_at','DESC')->get();
        return $query;
    }

    public static function load_deptcode() {

        $department = new department;
        $query = $department::whereNull('deleted_at')->get();
        return $query;
    }
    public static function get_personnel_deptcode($deptcode) {

        $personnel = new personnel;
        $query = $personnel::where('deptcode','=',$deptcode)->whereNull('deleted_at')->get();
        return $query;

    }
      public static function save_data_reassignment($serial_no, $emp_id, $deptcode, $roomno, $remarks) {

        $date_today = Carbon::now('utc');
        $query = '';
        $query_previous_user = '';

        //change the status of hardware deployed to ASSIGNED and update deployment_it table field date recalled
        $hardware_change_status = deployment_it::hardware_equipment_change_status($serial_no);
        $update_deployment_it_reassignment = deployment_it::deployment_it_reassign_update($serial_no);

        deployment_it::create(['serial_no' => $serial_no, 'emp_id' => $emp_id, 'deptcode' => $deptcode, 'roomno' => $roomno, 'date_deployed' =>$date_today,'remarks' => $remarks]);
        
        

       
        return $date_today;
    }



    // --------REPORT GENERATION ----->

     public static function deployment_it_personnel_print_all_data($emp_id, $lastname)
    {
      
        
        if($emp_id !='') {
              $personnel = new personnel;
              $personal_info = $personnel::where('emp_id','=',$emp_id)->get();
            
              if($personal_info->count())
              {
                   return $personal_info;
              }

             
          }
          else {

              $personal_info = '';
              $deployment_query ='';

              $personnel = new personnel;
              $personal_info = $personnel::where('last_name','=',$lastname)->get();
            
              if($personal_info->count())
              {
                   return $personal_info;
              }

          }
        return $query;
    }


}
