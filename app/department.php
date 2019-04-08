<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class department extends Model
{
    //
      public static function pull_department_data()
      {
      	 $query_department = department::all();
      	 return $query_department;
      }

    public static function getDepartment_name($deptcode)
    {
    	$query_department = department::where(['deptcode' => $deptcode])->get();
    	if($query_department == null)
    	{
    		$query_department = ' ';
    	}
    	return $query_department;  
    }
    
}
