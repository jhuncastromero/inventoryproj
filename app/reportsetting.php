<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class reportsetting extends Model
{
    //
    public static function return_report_setting($module_name)
    {
    	return reportsetting::whereNull('deleted_at')
    	->where('report_module','=',$module_name)
    	->get();
    }
}
