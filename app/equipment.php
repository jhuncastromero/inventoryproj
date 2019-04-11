<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class equipment extends Model
{
   use SoftDeletes;
   protected $dates =['deleted_at'];
   protected $fillable = ['tag_no','serial_no','category','type','origin','mac_address','description','photo_name','status','date_acquired','qrcode_name'];

   	public static function create_new($tag_no, $serial_no,$category,$type,$origin,$mac_addres,$description,$photo_name,$status,$date_acquired) {

		$query_result = equipment::get_duplicate_tag_serial($tag_no,$serial_no);

		if ($query_result[0] != 0 || $query_result[1] != 0) {
			
			if  ( $query_result[0] != 0 && $query_result[1] != 0) {
				
				return 1;
			}
			else if ( $query_result[1] != 0 ) {

				return 2;
			}
			else if  ( $query_result[0] != 0 ) {

				return 3;
			}
			
		} 

		$equipment = new equipment;
		$equipment->tag_no = $tag_no;
		$equipment->serial_no = $serial_no;
		$equipment->category = $category;
		$equipment->type = $type;
		$equipment->origin = $origin;
		$equipment->mac_address = $mac_address;
		$equipment->description = $description;
		$equipment->status = $status;
		$equipment->photo_name= $photo_name;
		$equipment->save();

	}
	public static function get_duplicate_tag_serial($tag_no,$serial_no)	{

		$count_tag_no = 0;
		$count_serial_no = 0;

		$count_tag_no = equipment::where('tag_no','=',$tag_no);
		$count_serial_no = equipment::where('serial_no','=',$serial_no);

		return[$count_tag_no, $count_serial_no];
	}


}
