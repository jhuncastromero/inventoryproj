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

        $date_today = Carbon::now();
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
      $query = $deployment_it::where('emp_id', '=', $emp_id)->get();
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
        $query = $deployment_it::where('serial_no','=',$serial_no)->whereNull('deleted_at')->get();
        return $query;
       
   }      

     public static function get_personnel_info($emp_id) {

        $personnel = new personnel;
        $query = $personnel::where('emp_id', '=',$emp_id)->whereNull('deleted_at')->get();
        return $query;
    }

    public static function get_current_user($serial_no) {

        $deployment_it = new deployment_it;
        $query = $deployment_it::where('serial_no','=',$serial_no)->whereNull('deleted_at')->orderBy('date_deployed','DESC')->get();
        return $query;
    }

    public static function load_deptcode() {

        $department = new department;
        $query = $department::whereNull('deleted_at')->get();
        return $query;
    }

}
