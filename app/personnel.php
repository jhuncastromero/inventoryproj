<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\department;
use App\paginationsetting;

class personnel extends Model
{
    //
    use SoftDeletes;
    protected $dates =['deleted_at'];
    protected $fillable = ['emp_id','last_name','first_name','middle_initial','deptcode','job_position','photo_name','email_add'];

  public static function create_new($emp_id, $last_name, $first_name, $middle_initial, $deptcode, $job_position, $photo_name, $email_add)
    {
       $countDuplicate = personnel::getDuplicate($emp_id, $last_name, $first_name, $middle_initial, $email_add);

      if($countDuplicate[0]!='0' && $countDuplicate[1]!='0' && $countDuplicate[2]!='0')
      {
        
        return 1; //ALL criteria (ID No, Personnel Whole Name and email address inputted have duplicates)
      }

      if($countDuplicate[0] != 0)
      {
        return 2; //Duplicate ID No. Found
      }

      if($countDuplicate[1] !=0 )
      {
        return 3; //Duplicate Name (Last name, First name, Middle initial was found
      }

      if ($countDuplicate[2] !=0 )
      {
        return 4; //Duplicate Email Address Found
      }

      //save data if no duplicates were found

      personnel::create(['emp_id' => $emp_id,'last_name' => $last_name,'first_name' => $first_name,'middle_initial' => $middle_initial,'deptcode' => $deptcode,'job_position' => $job_position,'photo_name'=> $photo_name,'email_add' => $email_add]);
            
    }

    public static function update_photo_dbase($id, $photo_filename) //will be called in the NO_PHOTO or user has removed the photo of the profile
    {
        $personnel = personnel::find($id);
        $personnel->photo_name = $photo_filename;
        $personnel->save();        
    }

    public static function update_profile($id, $emp_id, $last_name, $first_name, $middle_initial, $deptcode, $job_position, $email_add, $old_emp_id, $old_last_name, $old_first_name, $old_middile_initial, $old_email_add)
    {
      
         $countID = 0;
         $countName = 0;
         $countEmail = 0;  

         if( $old_emp_id != $emp_id ) 
         {
           $countID = personnel::where(['emp_id' =>$emp_id])->count();
         }

         if( $old_last_name != $last_name || $old_first_name != $first_name || $old_middile_initial != $middle_initial)
         {
            $countName = personnel::where(['last_name' => $last_name])
            ->where(['first_name' => $first_name])
            ->where(['middle_initial' => $middle_initial])
            ->count();
         }

         if( $old_email_add != $email_add)
         {
             $countEmail = personnel::where(['email_add' => $email_add])->count();       
         }
          

          if($countID=='0' && $countName=='0' && $countEmail=='0')
          {
            
            
            $personnel = personnel::find($id);
            $personnel->emp_id = $emp_id;
            $personnel->last_name = $last_name;
            $personnel->first_name = $first_name;
            $personnel->middle_initial = $middle_initial;
            $personnel->deptcode = $deptcode;
            $personnel->job_position = $job_position;
            $personnel->email_add = $email_add;
            $personnel->save();        

          }

          return[ $countID, $countName, $countEmail];

            
    }

    public static function personnel_delete($id)
    {
      $delete_query = personnel::find($id);
      $delete_query->delete();
      return 3;
      
    }

    public static function listview()
    {
      $paginate = paginationsetting::whereNull('deleted_at')->where('pagination_module','=','personnel')->get();
      if ($paginate=='' || $paginate[0]->pagination_number ==0)
      {
        $query = personnel::whereNull('deleted_at')->orderBy('last_name')->get();  
      }
      else
      {
        $query = personnel::whereNull('deleted_at')->orderBy('last_name')->Paginate($paginate[0]->pagination_number);  
      }
      
      return $query;
    }

    public static function ajax_search_delete_query($emp_id, $last_name)
    {
         $query=''; 
        if(!empty($emp_id) && !empty($last_name))
        {

            $query = personnel::whereNull('deleted_at')
              ->where(['emp_id' => $emp_id])
              ->where(['last_name' => $last_name])
              ->get();
        }
        elseif (!empty($emp_id)) {
          $query = personnel::whereNull('deleted_at')
              ->where(['emp_id' => $emp_id])->get();
        }
        elseif(!empty($last_name)){

          $query = personnel::where('last_name','like',$last_name.'%')->whereNull('deleted_at')->get(); 
        }

         return $query;
    }
    public static function personnel_query($emp_id, $last_name)
    {
         $query=''; 
        if(!empty($emp_id) && !empty($last_name))
        {

            $query = personnel::whereNull('deleted_at')
              ->where(['emp_id' => $emp_id])
              ->where(['last_name' => $last_name])
              ->get();
        }
        elseif (!empty($emp_id)) {
          $query = personnel::whereNull('deleted_at')
              ->where(['emp_id' => $emp_id])->get();
        }
        elseif(!empty($last_name)){

            $query = personnel::where('last_name','like',$last_name.'%')->whereNull('deleted_at')->get();    
        
        }

         return $query;
    }

    public static function getdetails($id)
    {
      $query = personnel::where(['id' => $id])->whereNull('deleted_at')->get();
      return $query;
    }
     public static function getdetails_2($id) //for module1.personnel.show2 and update personnel details
    {
      $query = personnel::where(['id' => $id])->whereNull('deleted_at')->get();
      return $query;
    }

    public static function getDuplicate($emp_id, $last_name, $first_name, $middle_initial, $email_add)
    {
      //check if there is a duplicated employee id number
      $countID = 0;
      $countName = 0;
      $countEmail = 0;

      $countID = personnel::where(['emp_id' =>$emp_id])->count();

      //check if there is a duplicated name of employee (lastname, firstname and middle initial)
      $countName = personnel::where(['last_name' => $last_name])
            ->where(['first_name' => $first_name])
            ->where(['middle_initial' => $middle_initial])
            ->count();

      //check if there is a duplicated email address or email add already exist     
        $countEmail = personnel::where(['email_add' => $email_add])->count(); 

    return [$countID, $countName, $countEmail];

    }

    public static function list_update()
    {
      $paginate = paginationsetting::whereNull('deleted_at')->where('pagination_module','=','personnel')->get();
      if ($paginate=='' || $paginate[0]->pagination_number ==0)
      {
        $query = personnel::whereNull('deleted_at')->orderBy('last_name')->get();  
      }
      else
      {
        $query = personnel::whereNull('deleted_at')->orderBy('last_name')->Paginate($paginate[0]->pagination_number);  
      }
      return $query; 
    }

    public static function report_all_data()
    {
      
      //$query = personnel::whereNull('deleted_at')->orderBy('last_name')->Paginate(10);
      $query = DB::table('personnels')
      ->join('departments','personnels.deptcode','=','departments.deptcode')
      ->select('personnels.*','departments.deptname')
      ->whereNull('personnels.deleted_at')
      ->orderBy('last_name')
      ->Paginate(5);
      return $query;
    }

    public static function print_all_data()
    {
      
      //$query = personnel::whereNull('deleted_at')->orderBy('last_name')->Paginate(10);
      $query = DB::table('personnels')
      ->join('departments','personnels.deptcode','=','departments.deptcode')
      ->select('personnels.*','departments.deptname')
      ->whereNull('personnels.deleted_at')
      ->orderBy('last_name')
      ->get();
      return $query;
    }

    public static function pagination_setting($module)
    {
      $pagination_number = '';

      $pagination = paginationsetting::whereNull('deleted_at')->where('pagination_module','=',$module)->get();
      if($pagination=='')
      {
        $pagination_number = 0;
      }
      else
      {
        $pagination_number = $pagination[0]->pagination_number;
      }
      return $pagination_number;
    }

}