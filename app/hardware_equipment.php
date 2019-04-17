<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class hardware_equipment extends Model
{
   use SoftDeletes;
   protected $dates =['deleted_at'];
   protected $fillable = ['tag_no','serial_no','category','type','origin','mac_address','description','photo_name','status','date_acquired','qrcode_name','brand'];

   	public static function create_new($tag_no, $serial_no,$category,$type,$origin,$mac_address,$description,$photo_name,$status,$date_acquired, $qrcode_name, $brand) {

		$hardware_equipment = new hardware_equipment;
		
		$hardware_equipment->tag_no = $tag_no;
		$hardware_equipment->serial_no = $serial_no;
		$hardware_equipment->category = $category;
		$hardware_equipment->type = $type;
		$hardware_equipment->origin = $origin;
		$hardware_equipment->mac_address = $mac_address;
		$hardware_equipment->description = $description;
		$hardware_equipment->brand = $brand;
		$hardware_equipment->status = $status;
		$hardware_equipment->qrcode_name = $qrcode_name;
		$hardware_equipment->date_acquired = Carbon::parse($date_acquired)->format('Y-m-d');
		$hardware_equipment->save();

	}
   	
   	public static function show_details($id) {

 		$query_result = '';
 		$hardware_equipment = new hardware_equipment;
 		$query_result = $hardware_equipment::where('id','=',$id)
 			->whereNull('deleted_at')
 			->get();
 		return $query_result;
   	}


   	public static function get_error_warning($tag_no, $serial_no) {

		$hardware_equipment = new hardware_equipment;
		$query_result = $hardware_equipment::get_duplicate_tag_serial($tag_no,$serial_no);

		if ($query_result[0] != 0 || $query_result[1] != 0) {
			
			if  ( $query_result[0] != 0 && $query_result[1] != 0) {
				
				return 1; // user entered duplicated tagno and serial no.
			}
			else if ( $query_result[0] != 0 ) {

				return 2; //user entered duplicated tag no.
			}
			else if  ( $query_result[1] != 0 ) {

				return 3; //user entered duplicated serial no.
			}
			
		} 

		
	}
	public static function get_duplicate_tag_serial($tag_no,$serial_no)	{

		$count_tag_no = 0;
		$count_serial_no = 0;

		$count_tag_no = hardware_equipment::where('tag_no','=',$tag_no)->count();
		$count_serial_no = hardware_equipment::where('serial_no','=',$serial_no)->count();

		return[$count_tag_no, $count_serial_no];
	}

	public static function list_view_equipment() {
		$query_result = '';
		$query_result = hardware_equipment::whereNull('deleted_at')->get();
		return $query_result;
	}
}
