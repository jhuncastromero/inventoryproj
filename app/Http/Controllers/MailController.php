<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\DemoEmail;
use App\Mail\assetTrackingEmail;
use Illuminate\Support\Facades\Mail;


//added APP
use App\deployment_it;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Filesystem;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

class MailController extends Controller
{
    //



   public function email_assets_to_personnel_individual(Request $request) {

      
        $objAsset = new \stdClass();
        $objAsset->timer= '';
        $output = '';
        $personal = '';
        $hardware ='';
        $hardware_info = '';
        $emp_id='';
        $department = '';

        
        $deployment_it = new deployment_it;
        $query_result = $deployment_it::ajax_view_deployment_by_personnel($request->email_emp_id, $request->email_last_name);

        if(empty($query_result)){
        
            $output = "<p style='font-style:italic;font-size:13px;font-weight:bold;'>Unable to Send Details of Assigned Hardware/ Equipments.p>";
          
        }
        else {
             
             $emp_id = $query_result[0]->emp_id;
             $emp_email = $query_result[0]->email_add;
            //for column 1 - picture of personnel and brief info
            $output.='<br><p style="font-family:arial; font-size:12px;">Dear Mr. / Ms. ' .$query_result[0]->last_name.',';
            $output.='<br><br><br> As an update, below are the list of Company Hardware/Equipments you are currently using and in possession. Kindly check the details below: <br><br></p>';
            
                     //for column 2 - Assigned/ Deployed Hardware Equipment
            $deployment_it = new deployment_it;
            $query_hardware = $deployment_it::get_in_used_hardware($emp_id);

            if($query_hardware->isEmpty()) {
                 $output.= "<p style='font-style:italic;font-size:12px;font-weight:bold;font-family:arial; padding-top:30px;'>No Hardware Equipment deployed/assigned to this personnel.</p>";
            }
            else {
                  


                            
                 $output.='<table class="responsive-table" style="width:60%; padding:5px; font-size:11px; font-family:arial;">';
                 $output.='<thead style="background-color:#9e9e9e; color:#ffffff;"><tr>';
                 $output.=' <th align="left">Type</th>';
                 $output.=' <th align="left">Brand/ Make</th>';
                 $output.=' <th align="left">Serial No.</th>';
                 $output.=' <th align="left">Tag No.</th>';
                 $output.=' <th align="left">Date Assigned</th>';
                 $output.='</thead></tr>';

               foreach($query_hardware as $list) {
                              $hardware_info = $deployment_it::get_hardware_info($list->serial_no);
                              $output.='<tr>';
                              $output.='<td style="border-bottom:1px solid #bdbdbd;">'.$hardware_info[0]->type.'</td>';
                              $output.='<td style="border-bottom:1px solid #bdbdbd;">'.$hardware_info[0]->brand.'</td>';
                              $output.='<td style="border-bottom:1px solid #bdbdbd;">'.$list->serial_no.'</td>';
                               $output.='<td style="border-bottom:1px solid #bdbdbd;">'.$hardware_info[0]->tag_no.'</td>';
                              $output.='<td style="border-bottom:1px solid #bdbdbd;">'.Carbon::parse($list->date_deployed)->format('m/d/Y').'</td>';
                              $output.= '</tr>';
                 }

                 $output.='</table>';
                 $output.='<p style="font-family:arial;font-size:12px;"><br>Please use the assigned company hardware/equipment properly and report any software/ hardware issues to IT Technical Support Group for diagnosis and repair. <br><br><br>Thank you! <br><br><br><i>IT-Support Admin</i><br><i style="font-size:10px;">SSA Consulting Group - Manila</i> </p>';


               



            }
        }

        $objAsset->data = $output;

        Mail::to($emp_email)->send(new assetTrackingEmail($objAsset));
        return redirect()->back()->with('flag',1);
    }
}
