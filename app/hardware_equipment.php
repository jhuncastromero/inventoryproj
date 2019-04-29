<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

use App\paginationsetting;

class hardware_equipment extends Model
{
   use SoftDeletes;
   protected $dates =['deleted_at'];
   protected $fillable = ['tag_no','serial_no','category','type','origin','mac_address','description','photo_name','status','date_acquired','qrcode_name','brand'];

   	public static function save_data($tag_no, $serial_no,$category,$type,$origin,$mac_address,$description,$photo_name,$status,$date_acquired, $qrcode_name, $brand) {

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
		$hardware_equipment->photo_name = $photo_name;
		$hardware_equipment->qrcode_name = $qrcode_name;
		$hardware_equipment->date_acquired = Carbon::parse($date_acquired)->format('Y-m-d');
		$hardware_equipment->save();

	}
	public static function update_data($id, $tag_no, $serial_no,$category,$type,$origin,$mac_address,$description,$photo_name,$status,$date_acquired, $qrcode_name, $brand) {

		$update = new hardware_equipment;
        $hardware_equipment = $update::find($id);

		$hardware_equipment->tag_no = $tag_no;
		$hardware_equipment->serial_no = $serial_no;
		$hardware_equipment->category = $category;
		$hardware_equipment->type = $type;
		$hardware_equipment->origin = $origin;
		$hardware_equipment->mac_address = $mac_address;
		$hardware_equipment->description = $description;
		$hardware_equipment->brand = $brand;
		$hardware_equipment->status = $status;
		$hardware_equipment->photo_name = $photo_name;
		$hardware_equipment->qrcode_name = $qrcode_name;
		$hardware_equipment->date_acquired = Carbon::parse($date_acquired)->format('Y-m-d');
		$hardware_equipment->save();

	}
	public static function delete_data($id) {

		$hardware_equipment = new hardware_equipment;
		$query_results = $hardware_equipment::find($id);
		$query_results->delete();
		
	}
   	
   	public static function show_details($id) {

 		$query_result = '';
 		$hardware_equipment = new hardware_equipment;
 		$query_result = $hardware_equipment::where('id','=',$id)
 			->whereNull('deleted_at')
 			->get();
 		return $query_result;
   	}


   	public static function get_update_error_warning($tag_no, $serial_no, $mac_address,$id){

   		$count_tag_no = 0;
   		$count_serial_no = 0;
   		$count_mac_address = 0;

   		$hardware_equipment = new hardware_equipment;
   		$query_results = $hardware_equipment::where('id','=',$id)
   			->whereNull('deleted_at')
   			->get();

   		if($query_results[0]->tag_no != $tag_no){
   			$count_tag_no = $hardware_equipment::where('tag_no','=',$tag_no)->count();
   		}

   		if($query_results[0]->serial_no != $serial_no){
   			$count_serial_no = $hardware_equipment::where('serial_no','=',$serial_no)->count();
   		}

   		/*if($query_results[0]->mac_address != $mac_address || ){
   			$count_mac_address = $hardware_equipment::where('mac_address','=',$mac_address)->count();
   		}

   		return [$count_tag_no, $count_serial_no, $count_mac_address];*/
   		return [$count_tag_no, $count_serial_no];
   	}
   	
   	public static function get_error_warning($tag_no, $serial_no, $mac_address) {

		$hardware_equipment = new hardware_equipment;
		$query_result = $hardware_equipment::get_duplicate_tag_serial_mac($tag_no, $serial_no, $mac_address);

		if ($query_result[0] != 0 || $query_result[1] != 0 ) {
			
			if  ( $query_result[0] != 0 && $query_result[1] != 0 && $query_result[2] != 0) {
				
				return 1; // user entered duplicated tagno and serial no.
			}
			else if ( $query_result[0] != 0 ) {

				return 2; //user entered duplicated tag no.
			}
			else if  ( $query_result[1] != 0 ) {

				return 3; //user entered duplicated serial no.
			}
			/*else if ( $query_result[2] != 0 ) {

				return 4; //user entered duplicated mac address
			}*/
			
		} 

		
	}
	public static function get_duplicate_tag_serial_mac($tag_no, $serial_no, $mac_address)	{

		$count_tag_no = 0;
		$count_serial_no = 0;
		$count_mac_address = 0;

		$count_tag_no = hardware_equipment::where('tag_no','=',$tag_no)->count();
		$count_serial_no = hardware_equipment::where('serial_no','=',$serial_no)->count();
		$count_mac_address = hardware_equipment::where('mac_address','=',$mac_address)->count();

		return[$count_tag_no, $count_serial_no];

	}

	public static function list_view_equipment() {

		$query_result = '';
		$query_result = hardware_equipment::whereNull('deleted_at')->get();
		return $query_result;
	}
	public static function list_view_equipment_pagination() {

		$query_results = '';
		$paginationsetting = new paginationsetting;
		$paginate = $paginationsetting::whereNull('deleted_at')
			->where('pagination_module','=','hardware_equipments')
			->get();

		if (!empty($paginate)) {
			$query_results = hardware_equipment::whereNull('deleted_at')->paginate($paginate[0]->pagination_number);
		}
		else {
			$query_results = hardware_equipment::whereNull('deleted_at')->get();	
		}

		
		
		return $query_results;
	}
	public static function filter_view_equipment_pagination($type, $category) {

		$query_results = '';
		
		
			if($type != ''){
				$query_results = hardware_equipment::whereNull('deleted_at')
				->where('type','like', $type.'%')
				->get();
			}
			else if($category != '') {
				
					$query_results = hardware_equipment::whereNull('deleted_at')
				    ->where('category','=', $category)
				    ->get();
				
			}
		
	   return $query_results;
	}
	public static function update_filter_equipment_pagination($type, $category) {

		$query_results = '';
		
			if($type != ''){
				$query_results = hardware_equipment::whereNull('deleted_at')
				->where('type','like', $type.'%')
				->get();
			}
			else if($category != '') {
					$query_results = hardware_equipment::whereNull('deleted_at')
				    ->where('category','=', $category)
				    ->get();
				
			}
			
	
		return $query_results;
	}

	public static function flexible_search($criteria, $search_value) {

		$query_results = '';
		$hardware_equipment = new hardware_equipment;
		$query_results = $hardware_equipment::where( $criteria, '=', $search_value)
			->whereNull('deleted_at')
			->get();
		return $query_results;
	}
	public static function flexible_search_non_sensitive($criteria, $search_value) {

		$query_results = '';
		$hardware_equipment = new hardware_equipment;
		$query_results = $hardware_equipment::where( $criteria, 'like', $search_value.'%')
			->whereNull('deleted_at')
			->get();
      //where('last_name','like',$last_name.'%')->whereNull('deleted_at')->get();   

		return $query_results;
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
