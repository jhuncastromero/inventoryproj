<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Filesystem;
use Illuminate\Support\Facades\File;
use App\personnel;
use App\department;
use App\reportsetting;
use App\deployment_it;
use PDF;
use Carbon\Carbon;


class DynamicPDFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $get_personnel_lists = '';     
         $get_personnel_lists = $this->get_personnel_list();

         return view('module1.personnel.pdf_report',compact('get_personnel_lists'));

    }
    public function get_personnel_list() // for getting display in report page with paginate 5
    {
     return personnel::report_all_data();
    }
    public function print_all() // for loading all data in report PDF
    {
       return personnel::print_all_data();
    }

    public function pdf()
    {
       /* other option of code below
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($this->convert_personnel_data_to_html()); // get all data and load into html format
        $pdf->setPaper('A4','landscape'); // paper orientation
        return $pdf->stream();*/

        $pdf = \App::make('dompdf.wrapper');
        $pdf = PDF::setOptions(['images' => true, 'defaultFont' => 'Arial',]); 
        $pdf->loadHTML($this->convert_personnel_data_to_html()); // get all data and load into html format
        $pdf->setPaper('A4','landscape'); // paper orientation
        

        return $pdf->stream();

    }
    public function convert_personnel_data_to_html()
    {
         $get_personnel_lists = $this->print_all();
         $rpt_setting = reportsetting::return_report_setting('personnel');
         if ($rpt_setting->isEmpty())
         {
            $rpt_header ='';
            $rpt_sub_header ='';
         }
         else
         {
            $rpt_header =$rpt_setting[0]->report_header;
            $rpt_sub_header =$rpt_setting[0]->report_sub_header;;
         }
         $output = '

                <style>
                
                    table {

                        width : 100%;
                        border-collapse: collapse;
                       

                    }

                    th {
                        padding:8px;
                          font-size: 14px;
                    }

                    td {
                         font-size: 14px;
                    }
                    tr : nth-child(even) {
                        background-color: #f2f2f2;
                    }

                </style>

                   <div style="font-size:14px;">'.$rpt_header.'
                   <div style="font-size:18px;">'.$rpt_sub_header.'</div>
                   <br>
                    <table>

                    <thead style="background-color:#454444; color:white;">  <!-- Column headings-->
                        <tr>
                            <th> <center>Photo</center> </th>
                            <th> Name </th>
                            <th> ID No.  </th>
                            <th> Department </th>
                            <th> Designation </th>
                            <th> Email </th>
                            
                        </tr>
                    </thead>

                    <tbody> <!-- Table body containing records of personnel-->'; 

                    foreach($get_personnel_lists as $list)
                    {
                       $output .='
                        <tr>
                            <td>';

                                if(!empty($list->photo_name))
                                {
                                    //convert the image to base64 for it to be loaded to the pdf
                                    $path = base_path().'/storage/app/public/personnel_photo/'.$list->emp_id.'/'.$list->photo_name;

                                     $type = pathinfo($path, PATHINFO_EXTENSION);
                                     $data = file_get_contents($path);
                                     $base64 = 'data:image/'.$type.';base64,'.base64_encode($data);
                                     $output.= "<center><img src=" .$base64. "width='40px' height='40px'></center>";
                                }
                                else
                                {
                                    $output.='<center><i>(no photo)</i></center>';
                                }
                            $output.='</td>

                            <td style="padding-right:10px;">'. $list->last_name .', ' . $list->first_name. ' '.$list->middle_initial. '.   </td>
                            <td height="25px" width="15%">'. $list->emp_id.'</td>
                            <td width="20%">'. $list->deptname. '</td>
                            <td>'. $list->job_position. '</td>
                            <td width="15%">'. $list->email_add. '</td>';
                            
                     }
                     $output.'</tr></tbody></table>';
            return $output;

    }


    //------------------------------------>REPORT FOR DEPLOYMENT_IT..........................................>

    //Deployment by Personnel
    
    public function deployment_personnel_print_report(Request $request) { 
         $month = '';
         $year = '';
         $emp_id = '';
         $lastname = '';

        if($request->hidden_month =='' && $request->hidden_year ==''){
           
            
             $emp_id = $request->hidden_emp_id;
             $lastname = $request->hidden_last_name;
             return $this->deployment_personnel_print_report_all($emp_id, $lastname);
          
        }
        else {

          
             $emp_id = $request->hidden_emp_id;
             $lastname = $request->hidden_last_name;
             $month = $request->hidden_month;
             $year = $request->hidden_year;
             return $this->deployment_personnel_print_report_month_year($month, $year, $emp_id, $lastname);

        }

    }
    public function deployment_personnel_print_report_all($emp_id, $lastname) {
        
        $output = '';
        $personal = '';
        $hardware ='';
        $hardware_info = '';
        $emp_id='';
        $department = '';

    
        $rpt_setting = reportsetting::return_report_setting('deployment_it');
         if ($rpt_setting->isEmpty())
         {
            $rpt_header ='';
            $rpt_sub_header ='';
         }
         else
         {
            $rpt_header =$rpt_setting[0]->report_header;
            $rpt_sub_header =$rpt_setting[0]->report_sub_header;;
         }


        $deployment_it = new deployment_it;
        $query_result = $deployment_it::ajax_view_deployment_by_personnel($emp_id, $lastname);
        $output.="<style>
                
                    table {

                        width : 100%;
                        border-collapse: collapse;

                   }

                    th {
                        padding:3px;
                        font-size: 13px;
                        font-weight:regular;
                        color:#ffffff;
                        background-color:#757575;
                    }

                    td {
                         font-size: 12px;
                         font-family: arial;
                    }
                    
                </style>";
         $output.=' <div style="font-size:14px;">'.$rpt_header.'</div>';
         $output.=' <div style="font-size:15px;">'.$rpt_sub_header.'</div><br>';
         $output.='<div style="padding-top:-10px;"><hr></div>';

        if(empty($query_result)){
        
            $output .= "<p style='font-style:italic;font-size:13px;font-weight:bold;'>Personnel ID/ Lastname not found. Please check your entry.</p>";

             
        }
        else {
             
            $emp_id = $query_result[0]->emp_id;
   
            $output.='<div style="font-weight:regular;margin-top:20px;width:695px; padding-left:10px;padding-bottom:5px;padding-top:5px;background-color:#757575; font-size:14px; color:#ffffff;">Hardware Equipment Deployment Report by Personnel</div>'; 
            $output .= '<table style = "padding-top:15px;">';
            $output .= '<tr>';
            $output .= '<td style="width:125px;">';
                if(!empty($query_result[0]->photo_name))
                {
                        //convert the image to base64 for it to be loaded to the pdf
                        $path = base_path().'/storage/app/public/personnel_photo/'.$query_result[0]->emp_id.'/'.$query_result[0]->photo_name;

                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $base64 = 'data:image/'.$type.';base64,'.base64_encode($data);
                                     $output.= "<img src=" .$base64. "width='90px' height='90px'>";
                                        
                }
                else
                {
                     $output.='<i class="medium material-icons">person</i>';
                 
                }

                
            $output .= '</td>';
            $output .= '<td>';
            $department = deployment_it::get_deptname($query_result[0]->deptcode);
            $output .= '<div style="font-size:20px; font-weight:bold;">'.$query_result[0]->first_name.' '.$query_result[0]->middle_initial.'. '.$query_result[0]->last_name.'</div>';
            $output .= '<div style="font-size:14px; font-style:italic;">'.$query_result[0]->emp_id.'</div>';
            $output .= '<div style="font-size:14px; font-style:italic;">'.$query_result[0]->job_position.'</div>';
            $output.= '<div style="font-size:12px; font-style:italic;">'.$department[0]->deptname.' Department </div>';
            $output .= '</td>';
            $output .= '</tr>';
            $output .= '</table>';

                       
             // Assigned/ Deployed Hardware Equipment
             $deployment_it = new deployment_it;
             $query_hardware = $deployment_it::get_assigned_hardware($emp_id);

            if($query_hardware->isEmpty()) {
                 $output.= "<p style='font-style:italic;font-size:13px;font-weight:bold; padding-top:30px;'>No Hardware Equipment deployed/assigned to this personnel.</p>";
            }
            else {
             
               
                $output.='<div style="font-weight:regular;padding-bottom:20px;padding-top:20px; width:700px; font-size:16px;"><center>Assigned/ Deployed Hardware Equipment List</center></div>';
            
                $output.='<table class="" style="width:100%; ">';
                $output.='<thead><tr>';
                $output.='<th><center>Photo</center> </th>';
                $output.=' <th style="padding-left:20px;">Serial No.</th>';
                $output.=' <th style="padding-left:-20px">Tag No</th>';
                $output.=' <th style="padding-left:-1px">Brand/ Make</th>';
                $output.=' <th style="padding-left:20px">Date Assigned</th>';
                $output.=' <th style="padding-left:-10px">Date Recalled</th>';
                $output.='</thead></tr>';

               foreach($query_hardware as $list) {
                              $hardware_info = $deployment_it::get_hardware_info($list->serial_no);
                              $output.='<tr>';
                              if($hardware_info[0]->photo_name == '') {

                                    $output.='<td style="padding-top:5px; padding-bottom:5px; padding-right:5px; padding-left:0px;  border-bottom: 1pt solid #e0e0e0;""><i>---no photo---</i></td>';

                                 }
                                 else {

                                    //convert the image to base64 for it to be loaded to the pdf
                                    $output.= '<td style="padding-top:5px; padding-bottom:5px; padding-right:5px; padding-left:0px; border-bottom: 1pt solid #e0e0e0;">';

                                    $path = base_path().'/storage/app/public/hardware_photo/IT/'.$hardware_info[0]->photo_name;

                                    $type = pathinfo($path, PATHINFO_EXTENSION);
                                    $data = file_get_contents($path);
                                    $base64 = 'data:image/'.$type.';base64,'.base64_encode($data);
                                                 $output.= "<center><img src=" .$base64. "width='20px' height='20px'></center>";
                                    $output.= '</td>';           
                                 }
                                
                               $output.='<td style="padding-top:10px; padding-bottom:10px; padding-right:5px; padding-left:20px; border-bottom: 1pt solid;  border-bottom: 1pt solid #e0e0e0;">'.$list->serial_no.'</td>';

                                 $output.='<td style="padding-top:5px; padding-bottom:5px; padding-right:40px; padding-left:-20px; border-bottom: 1pt solid #e0e0e0;">'.$hardware_info[0]->tag_no.'</td>';

                                $output.='<td style=" border-bottom: 1pt solid #e0e0e0;">'.$hardware_info[0]->brand.'</td>';
                                 $output.='<td style="padding-top:5px; padding-bottom:5px; padding-right:5px; padding-left:20px; border-bottom: 1pt solid #e0e0e0;">'.Carbon::parse($list->date_deployed)->format('m/d/Y').'</td>';
                                 if($list->date_recalled =='') {
                                    $output.='<td style=" border-bottom: 1pt solid #e0e0e0;padding-top:5px; padding-bottom:5px; padding-right:5px; padding-left:-10px;">(in use) </td>';
                                 }
                                else {
                                        $output.='<td style="border-bottom: 1pt solid #e0e0e0;padding-top:5px; padding-bottom:5px; padding-right:5px; padding-left:-10px;">'.Carbon::parse($list->date_recalled)->format('m/d/Y').'</td>';
                                 }
                                $output.= '</tr>';
                 }

               $output.='</table>';
               $output.='<div style="padding-top:100px;font-size:10px;">Report Generation Date:&nbsp;'.Carbon::parse(now('utc'))->format('m/d/Y').'</div>';

            }
            
          }
      
        $pdf = \App::make('dompdf.wrapper');
        $pdf = PDF::setOptions(['images' => true, 'defaultFont' => 'Arial',]); 
        $pdf->loadHTML($output); // get all data and load into html format
        $pdf->setPaper('A4','portrait'); // paper orientation
        return $pdf->stream();
   
    }
     public function deployment_personnel_print_report_month_year($month, $year, $emp_id, $lastname) {
        $output = '';
        $personal = '';
        $hardware ='';
        $hardware_info = '';
        $emp_id='';
        $department = '';

    
        $rpt_setting = reportsetting::return_report_setting('deployment_it');
         if ($rpt_setting->isEmpty())
         {
            $rpt_header ='';
            $rpt_sub_header ='';
         }
         else
         {
            $rpt_header =$rpt_setting[0]->report_header;
            $rpt_sub_header =$rpt_setting[0]->report_sub_header;;
         }


        $deployment_it = new deployment_it;
        $query_result = $deployment_it::ajax_view_deployment_by_personnel($emp_id, $lastname);
        $output.="<style>
                
                    table {

                        width : 100%;
                        border-collapse: collapse;

                   }

                    th {
                        padding:3px;
                        font-size: 13px;
                        font-weight:regular;
                        color:#ffffff;
                        background-color:#757575;
                    }

                    td {
                         font-size: 12px;
                         font-family: arial;
                    }
                    
                </style>";
         $output.=' <div style="font-size:14px;">'.$rpt_header.'</div>';
         $output.=' <div style="font-size:16px;">'.$rpt_sub_header.'</div><br>';
         $output.='<div style="padding-top:-10px;"><hr></div>';

        if(empty($query_result)){
        
            $output .= "<p style='font-style:italic;font-size:13px;font-weight:bold;'>Personnel ID/ Lastname not found. Please check your entry.</p>";

             
        }
        else {
             
            $emp_id = $query_result[0]->emp_id;
            $output.='<div style="font-weight:regular;margin-top:20px;width:695px; padding-left:10px;padding-bottom:5px;padding-top:5px;background-color:#757575; font-size:14px; color:#ffffff;">Hardware Equipment Deployment Report by Personnel</div>'; 
            $output .= '<table style = "padding-top:15px;">';
            $output .= '<tr>';
            $output .= '<td style="width:125px;">';
                if(!empty($query_result[0]->photo_name))
                {
                        //convert the image to base64 for it to be loaded to the pdf
                        $path = base_path().'/storage/app/public/personnel_photo/'.$query_result[0]->emp_id.'/'.$query_result[0]->photo_name;

                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $base64 = 'data:image/'.$type.';base64,'.base64_encode($data);
                                     $output.= "<img src=" .$base64. "width='90px' height='90px'>";
                                        
                }
                else
                {
                     $output.='<i class="medium material-icons">person</i>';
                 
                }

                
            $output .= '</td>';
            $output .= '<td>';
            $department = deployment_it::get_deptname($query_result[0]->deptcode);
            $output .= '<div style="font-size:20px; font-weight:bold;">'.$query_result[0]->first_name.' '.$query_result[0]->middle_initial.'. '.$query_result[0]->last_name.'</div>';
            $output .= '<div style="font-size:14px; font-style:italic;">'.$query_result[0]->emp_id.'</div>';
            $output .= '<div style="font-size:14px; font-style:italic;">'.$query_result[0]->job_position.'</div>';
            $output.= '<div style="font-size:12px; font-style:italic;">'.$department[0]->deptname.' Department </div>';
            $output .= '</td>';
            $output .= '</tr>';
            $output .= '</table>';

                       
             // Assigned/ Deployed Hardware Equipment
             $deployment_it = new deployment_it;
             $query_hardware = $deployment_it::get_assigned_personnel_by_month_year($emp_id, $month, $year);

            if($query_hardware->isEmpty()) {
                 $output.= "<p style='font-style:italic;font-size:13px;font-weight:bold; padding-top:30px;'>No Hardware Equipment Record Found. Please check Month/ Year entry.</p>";
            }
            else {
             
               
                $output.='<div style="font-weight:regular;padding-bottom:20px;padding-top:30px; width:700px; font-size:16px;"><center>Assigned/ Deployed Hardware Equipment List</center></div>';
            
                $output.='<table class="" style="width:100%; ">';
                $output.='<thead><tr>';
                $output.='<th><center>Photo</center> </th>';
                $output.=' <th style="padding-left:20px;">Serial No.</th>';
                $output.=' <th style="padding-left:-20px">Tag No</th>';
                $output.=' <th style="padding-left:-1px">Brand/ Make</th>';
                $output.=' <th style="padding-left:20px">Date Assigned</th>';
                $output.=' <th style="padding-left:-10px">Date Recalled</th>';
                $output.='</thead></tr>';

               foreach($query_hardware as $list) {
                              $hardware_info = $deployment_it::get_hardware_info($list->serial_no);
                              $output.='<tr>';
                              if($hardware_info[0]->photo_name == '') {

                                    $output.='<td style="padding-top:5px; padding-bottom:5px; padding-right:5px; padding-left:0px;  border-bottom: 1pt solid #e0e0e0;""><i>---no photo---</i></td>';

                                 }
                                 else {

                                    //convert the image to base64 for it to be loaded to the pdf
                                    $output.= '<td style="padding-top:5px; padding-bottom:5px; padding-right:5px; padding-left:0px; border-bottom: 1pt solid #e0e0e0;">';

                                    $path = base_path().'/storage/app/public/hardware_photo/IT/'.$hardware_info[0]->photo_name;

                                    $type = pathinfo($path, PATHINFO_EXTENSION);
                                    $data = file_get_contents($path);
                                    $base64 = 'data:image/'.$type.';base64,'.base64_encode($data);
                                                 $output.= "<center><img src=" .$base64. "width='20px' height='20px'></center>";
                                    $output.= '</td>';           
                                 }
                                
                               $output.='<td style="padding-top:10px; padding-bottom:10px; padding-right:5px; padding-left:20px; border-bottom: 1pt solid;  border-bottom: 1pt solid #e0e0e0;">'.$list->serial_no.'</td>';

                                 $output.='<td style="padding-top:5px; padding-bottom:5px; padding-right:40px; padding-left:-20px; border-bottom: 1pt solid #e0e0e0;">'.$hardware_info[0]->tag_no.'</td>';

                                $output.='<td style=" border-bottom: 1pt solid #e0e0e0;">'.$hardware_info[0]->brand.'</td>';
                                 $output.='<td style="padding-top:5px; padding-bottom:5px; padding-right:5px; padding-left:20px; border-bottom: 1pt solid #e0e0e0;">'.Carbon::parse($list->date_deployed)->format('m/d/Y').'</td>';
                                 if($list->date_recalled =='') {
                                    $output.='<td style=" border-bottom: 1pt solid #e0e0e0;padding-top:5px; padding-bottom:5px; padding-right:5px; padding-left:-10px;">(in use)</td>';
                                 }
                                else {
                                        $output.='<td style="border-bottom: 1pt solid #e0e0e0;padding-top:5px; padding-bottom:5px; padding-right:5px; padding-left:-10px;">'.Carbon::parse($list->date_recalled)->format('m/d/Y').'</td>';
                                 }
                                $output.= '</tr>';
                 }

               $output.='</table>';
               $output.='<div style="padding-top:100px;font-size:10px;">Report Generation Date:&nbsp;'.Carbon::parse(now('utc'))->format('m/d/Y').'</div>';

            }

            
          }

      
        $pdf = \App::make('dompdf.wrapper');
        $pdf = PDF::setOptions(['images' => true, 'defaultFont' => 'Arial',]); 
        $pdf->loadHTML($output); // get all data and load into html format
        $pdf->setPaper('A4','portrait'); // paper orientation
        return $pdf->stream();
          

    }

    public function deployment_hardware_print_report(Request $request) {

        $serial_no = '';
        $month = '';
        $year = '';

        $serial_no = $request->hidden_serial_no;
        $month = $request->hidden_month;
        $year = $request->hidden_year;

        if($month =='' && $year=='') {

            return $this->deployment_hardware_print_report_all($serial_no);

        }
        else {

            return $this->deployment_hardware_print_report_month_year($serial_no, $month, $year);

        }
    }
    public function deployment_hardware_print_report_all($serial_no){

       
        $output='';
        $hardware = '';
        $hardware_photo = '';
        $deployment_it = new deployment_it;
        $personnel_info = '';
        $hadware_info = '';
        $query_hardware = $deployment_it::ajax_view_deployment_by_equipment($serial_no);
        



        $rpt_setting = reportsetting::return_report_setting('deployment_it');
         if ($rpt_setting->isEmpty())
         {
            $rpt_header ='';
            $rpt_sub_header ='';
         }
         else
         {
            $rpt_header =$rpt_setting[0]->report_header;
            $rpt_sub_header =$rpt_setting[0]->report_sub_header;;
         }

         $output.="<style>
                
                    table {

                        width : 100%;
                        border-collapse: collapse;

                   }

                    th {
                        padding:5px;
                        font-size: 14px;
                        font-weight:regular;
                        color:#ffffff;
                        background-color:#757575;
                    }

                    td {
                         font-size: 13px;
                         border-bottom: 1pt solid #e0e0e0;
                         padding:5px;
                        
                    }
                    
                </style>";
         $output.=' <div style="font-size:14px;">'.$rpt_header.'</div>';
         $output.=' <div style="font-size:15px;">'.$rpt_sub_header.'</div><br>';
         $output.='<div style="padding-top:-10px;"><hr></div>';



        if($query_hardware->isEmpty()) {
                 $output.= "<p style='font-style:italic;font-size:13px;font-weight:bold; padding-top:30px;'>No Record Found.</p>";
               
        }
        else {
            $output.='<div style="font-weight:regular;margin-top:20px;width:695px; padding-left:10px;padding-bottom:5px;padding-top:5px;background-color:#757575; font-size:14px; color:#ffffff;">Hardware Deployment Report by Equipment </div>'; 

            $output .= '<table style = "padding-top:15px;">';
            $output .= '<tr>';
            $output .= '<td style="width:130px;border-bottom:0">';

             $hardware_info = $deployment_it::get_hardware_info($serial_no);

             if($hardware_info[0]->photo_name == '') {
                
                $output.= '<i style="font-size:20px;">-- no photo -- </i>';
             }
             else {

                     //convert the image to base64 for it to be loaded to the pdf
                    $path = base_path().'/storage/app/public/hardware_photo/IT/'.$hardware_info[0]->photo_name;
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/'.$type.';base64,'.base64_encode($data);
                                 $output.= "<img src=" .$base64. "width='100px' height='100px'>";

             }
             $output.='</td>';
             $output.= '<td style="border-bottom:0">';
             $output.='<div style="font-weight:regular;font-size:28px; padding-top:-10px;">';
             $output.=$hardware_info[0]->brand. ' '.$hardware_info[0]->type;
             $output.='<div style="font-weight:regular;font-size:12px;font-style:italic;"> S/N:&nbsp;'.$hardware_info[0]->serial_no.'</div>';
             $output.='<div style="font-weight:regular;font-size:12px;font-style:italic;">Tag No:&nbsp;'.$hardware_info[0]->tag_no.'</div>';
             $output.='</td>';
             $output.='</tr>';
             $output.='</table>';


             $output.='<div style="font-weight:regular;padding-bottom:20px;padding-top:15px; width:700px; font-size:16px;"><center>Deployment/ Assignment Record List</center></div>';

             $output.='<table class="responsive-table" style="width:100%; font-size:12px;">';
             $output.='<thead><tr>';
             $output.='<th>Photo</th>';
             $output.=' <th>ID No</th>';
             $output.=' <th>Personnel Name</th>';
             $output.=' <th>Department</th>';
             $output.=' <th>Date Assigned</th>';
             $output.=' <th>Date Recalled</th>';
             $output.='</thead><tbody></tr>';

             foreach($query_hardware as $list) {
                        
                $personnel_info = $deployment_it::get_personnel_info($list->emp_id);
                $output.='<tr>';
                $output.='<td>';
                if(!empty($personnel_info[0]->photo_name))
                {
                     
                     //convert the image to base64 for it to be loaded to the pdf
                    $path = base_path().'/storage/app/public/personnel_photo/'.$personnel_info[0]->emp_id.'/'.$personnel_info[0]->photo_name;
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/'.$type.';base64,'.base64_encode($data);
                                 $output.= "<img src=" .$base64. "width='30px' height='30px'>";

                }
                else
                {
                     $output.='--no photo--';
                 
                }
                 $output.='</td>';
                
                 $output.='<td>'.$personnel_info[0]->emp_id.'</td>';
                
                 $output.='<td>'.$personnel_info[0]->last_name.', '.$personnel_info[0]->first_name.' '.$personnel_info[0]->middle_initial.'.' .'</td>';
                 $deptname = deployment_it::get_deptname($personnel_info[0]->deptcode);
                 $output.='<td>'.$deptname[0]->deptname.'</td>';
                 $output.='<td>'.Carbon::parse($list->date_deployed)->format('m/d/Y').'</td>';
                 if($list->date_recalled =='') {
                    $output.='<td>(in use)</td>';
                 }
                 else {
                    $output.='<td>'.Carbon::parse($list->date_recalled)->format('m/d/Y').'</td>';
                 }
                 $output.= '</tr></tbody>';
             }

             $output.='</tbody></table>';
             $output.='<div style="padding-top:100px;font-size:10px;">Report Generation Date:&nbsp;'.Carbon::parse(now('utc'))->format('m/d/Y').'</div>';

             
        }

        $pdf = \App::make('dompdf.wrapper');
        $pdf = PDF::setOptions(['images' => true, 'defaultFont' => 'Arial',]); 
        $pdf->loadHTML($output); // get all data and load into html format
        $pdf->setPaper('A4','portrait'); // paper orientation
        return $pdf->stream();

    }
     public function deployment_hardware_print_report_month_year($serial_no, $month, $year) {


        $output='';
        $hardware = '';
        $hardware_photo = '';
        $deployment_it = new deployment_it;
        $personnel_info = '';
        $hadware_info = '';
        $query_hardware = $deployment_it::get_assigned_hardware_by_month_year($serial_no, $month, $year);
        



        $rpt_setting = reportsetting::return_report_setting('deployment_it');
         if ($rpt_setting->isEmpty())
         {
            $rpt_header ='';
            $rpt_sub_header ='';
         }
         else
         {
            $rpt_header =$rpt_setting[0]->report_header;
            $rpt_sub_header =$rpt_setting[0]->report_sub_header;;
         }

         $output.="<style>
                
                    table {

                        width : 100%;
                        border-collapse: collapse;

                   }

                    th {
                        padding:5px;
                        font-size: 14px;
                        font-weight:regular;
                        color:#ffffff;
                        background-color:#757575;
                    }

                    td {
                         font-size: 13px;
                         border-bottom: 1pt solid #e0e0e0;
                         padding:5px;
                        
                    }
                    
                </style>";
         $output.=' <div style="font-size:14px;">'.$rpt_header.'</div>';
         $output.=' <div style="font-size:15px;">'.$rpt_sub_header.'</div><br>';
         $output.='<div style="padding-top:-10px;"><hr></div>';



        if($query_hardware->isEmpty()) {
                 $output.= "<p style='font-style:italic;font-size:13px;font-weight:bold; padding-top:30px;'>No Record Found.</p>";
               
        }
        else {
            $output.='<div style="font-weight:regular;margin-top:20px;width:695px; padding-left:10px;padding-bottom:5px;padding-top:5px;background-color:#757575; font-size:14px; color:#ffffff;">Hardware Deployment Report by Equipment </div>'; 

            $output .= '<table style = "padding-top:15px;">';
            $output .= '<tr>';
            $output .= '<td style="width:130px;border-bottom:0">';

             $hardware_info = $deployment_it::get_hardware_info($serial_no);

             if($hardware_info[0]->photo_name == '') {
                
                $output.= '<i style="font-size:20px;">-- no photo -- </i>';
             }
             else {

                     //convert the image to base64 for it to be loaded to the pdf
                    $path = base_path().'/storage/app/public/hardware_photo/IT/'.$hardware_info[0]->photo_name;
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/'.$type.';base64,'.base64_encode($data);
                                 $output.= "<img src=" .$base64. "width='100px' height='100px'>";

             }
             $output.='</td>';
             $output.= '<td style="border-bottom:0">';
             $output.='<div style="font-weight:regular;font-size:28px; padding-top:-10px;">';
             $output.=$hardware_info[0]->brand. ' '.$hardware_info[0]->type;
             $output.='<div style="font-weight:regular;font-size:12px;font-style:italic;"> S/N:&nbsp;'.$hardware_info[0]->serial_no.'</div>';
             $output.='<div style="font-weight:regular;font-size:12px;font-style:italic;">Tag No:&nbsp;'.$hardware_info[0]->tag_no.'</div>';
             $output.='</td>';
             $output.='</tr>';
             $output.='</table>';


             $output.='<div style="font-weight:regular;padding-bottom:20px;padding-top:15px; width:700px; font-size:16px;"><center>Deployment/ Assignment Record List</center></div>';

             $output.='<table class="responsive-table" style="width:100%; font-size:12px;">';
             $output.='<thead><tr>';
             $output.='<th>Photo</th>';
             $output.=' <th>ID No</th>';
             $output.=' <th>Personnel Name</th>';
             $output.=' <th>Department</th>';
             $output.=' <th>Date Assigned</th>';
             $output.=' <th>Date Recalled</th>';
             $output.='</thead><tbody></tr>';

             foreach($query_hardware as $list) {
                        
                $personnel_info = $deployment_it::get_personnel_info($list->emp_id);
                $output.='<tr>';
                $output.='<td>';
                if(!empty($personnel_info[0]->photo_name))
                {
                     
                     //convert the image to base64 for it to be loaded to the pdf
                    $path = base_path().'/storage/app/public/personnel_photo/'.$personnel_info[0]->emp_id.'/'.$personnel_info[0]->photo_name;
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/'.$type.';base64,'.base64_encode($data);
                                 $output.= "<img src=" .$base64. "width='30px' height='30px'>";

                }
                else
                {
                     $output.='--no photo--';
                 
                }
                 $output.='</td>';
                
                 $output.='<td>'.$personnel_info[0]->emp_id.'</td>';
                
                 $output.='<td>'.$personnel_info[0]->last_name.', '.$personnel_info[0]->first_name.' '.$personnel_info[0]->middle_initial.'.' .'</td>';
                 $deptname = deployment_it::get_deptname($personnel_info[0]->deptcode);
                 $output.='<td>'.$deptname[0]->deptname.'</td>';
                 $output.='<td>'.Carbon::parse($list->date_deployed)->format('m/d/Y').'</td>';
                 if($list->date_recalled =='') {
                    $output.='<td>(in use)</td>';
                 }
                 else {
                    $output.='<td>'.Carbon::parse($list->date_recalled)->format('m/d/Y').'</td>';
                 }
                 $output.= '</tr></tbody>';
             }

             $output.='</tbody></table>';
             $output.='<div style="padding-top:100px;font-size:10px;">Report Generation Date:&nbsp;'.Carbon::parse(now('utc'))->format('m/d/Y').'</div>';

             
        }

        $pdf = \App::make('dompdf.wrapper');
        $pdf = PDF::setOptions(['images' => true, 'defaultFont' => 'Arial',]); 
        $pdf->loadHTML($output); // get all data and load into html format
        $pdf->setPaper('A4','portrait'); // paper orientation
        return $pdf->stream();

     }


     //PRINT ALL DEPLOYMENT--------------------------------PRINT ALL DEPLOYMENT--------------------------------->

      public function deployment_personnel_report_all(){

        $output = '';
        $personal = '';
        $hardware ='';
        $hardware_info = '';
        $emp_id='';
        $department = '';

        $deployment_it = new deployment_it;
        $query_result = $deployment_it::get_all_personnel();

        if(empty($query_result)){
        
            $output .= "<p style='font-style:italic;font-size:13px;font-weight:bold;'>No Existing Record in Personnel Database</p>";

             
        }
        else {

            $rpt_setting = reportsetting::return_report_setting('deployment_it');
             if ($rpt_setting->isEmpty())
             {
                $rpt_header ='';
                $rpt_sub_header ='';
             }
             else
             {
                $rpt_header =$rpt_setting[0]->report_header;
                $rpt_sub_header =$rpt_setting[0]->report_sub_header;;
             }

            foreach($query_result as $list) {
                
                                
                        $output.="<style>

                            .page-break {

                                page-break-after:always;
                            }
                        
                            table {

                                width : 100%;
                                border-collapse: collapse;

                           }

                            th {
                                padding:3px;
                                font-size: 13px;
                                font-weight:regular;
                                color:#ffffff;
                                background-color:#757575;
                            }

                            td {
                                 font-size: 12px;
                                 font-family: arial;
                            }
                            
                        </style>";
                        $output.=' <div style="font-size:14px;">'.$rpt_header.'</div>';
                        $output.=' <div style="font-size:15px;">'.$rpt_sub_header.'</div><br>';
                        $output.='<div style="padding-top:-10px;"><hr></div>';

                        $emp_id = $list->emp_id;
               
                        $output.='<div style="font-weight:regular;margin-top:20px;width:695px; padding-left:10px;padding-bottom:5px;padding-top:5px;background-color:#757575; font-size:14px; color:#ffffff;">Hardware Equipment Deployment Report by Personnel</div>'; 
                        $output .= '<table style = "padding-top:15px;">';
                        $output .= '<tr>';
                        $output .= '<td style="width:125px;">';
                        if(!empty($list->photo_name))
                        {
                                //convert the image to base64 for it to be loaded to the pdf
                                $path = base_path().'/storage/app/public/personnel_photo/'.$list->emp_id.'/'.$list->photo_name;

                                $type = pathinfo($path, PATHINFO_EXTENSION);
                                $data = file_get_contents($path);
                                $base64 = 'data:image/'.$type.';base64,'.base64_encode($data);
                                             $output.= "<img src=" .$base64. "width='90px' height='90px'>";
                                                
                        }
                        else
                        {
                             $output.='<i class="medium material-icons">person</i>';
                         
                        }

                        
                    $output .= '</td>';
                    $output .= '<td>';
                    $department = deployment_it::get_deptname($list->deptcode);
                    $output .= '<div style="font-size:20px; font-weight:bold;">'.$list->first_name.' '.$list->middle_initial.'. '.$list->last_name.'</div>';
                    $output .= '<div style="font-size:14px; font-style:italic;">'.$list->emp_id.'</div>';
                    $output .= '<div style="font-size:14px; font-style:italic;">'.$list->job_position.'</div>';
                    $output.= '<div style="font-size:12px; font-style:italic;">'.$department[0]->deptname.' Department </div>';
                    $output .= '</td>';
                    $output .= '</tr>';
                    $output .= '</table>';

                               
                     // Assigned/ Deployed Hardware Equipment
                     $deployment_it = new deployment_it;
                     $query_hardware = $deployment_it::get_assigned_hardware($emp_id);

                    if($query_hardware->isEmpty()) {
                         $output.= "<p style='font-style:italic;font-size:13px;font-weight:bold; padding-top:30px;'>No Hardware Equipment deployed/assigned to this personnel.</p>";
                    }
                    else {
                     
                       
                        $output.='<div style="font-weight:regular;padding-bottom:20px;padding-top:20px; width:700px; font-size:16px;"><center>Assigned/ Deployed Hardware Equipment List</center></div>';
                    
                        $output.='<table class="" style="width:100%; ">';
                        $output.='<thead><tr>';
                        $output.='<th><center>Photo</center> </th>';
                        $output.=' <th style="padding-left:20px;">Serial No.</th>';
                        $output.=' <th style="padding-left:-20px">Tag No</th>';
                        $output.=' <th style="padding-left:-1px">Brand/ Make</th>';
                        $output.=' <th style="padding-left:20px">Date Assigned</th>';
                        $output.=' <th style="padding-left:-10px">Date Recalled</th>';
                        $output.='</thead></tr>';

                       foreach($query_hardware as $h_list) {
                                      $hardware_info = $deployment_it::get_hardware_info($h_list->serial_no);
                                      $output.='<tr>';
                                      if($hardware_info[0]->photo_name == '') {

                                            $output.='<td style="padding-top:5px; padding-bottom:5px; padding-right:5px; padding-left:0px;  border-bottom: 1pt solid #e0e0e0;""><i>---no photo---</i></td>';

                                         }
                                         else {

                                            //convert the image to base64 for it to be loaded to the pdf
                                            $output.= '<td style="padding-top:5px; padding-bottom:5px; padding-right:5px; padding-left:0px; border-bottom: 1pt solid #e0e0e0;">';

                                            $path = base_path().'/storage/app/public/hardware_photo/IT/'.$hardware_info[0]->photo_name;

                                            $type = pathinfo($path, PATHINFO_EXTENSION);
                                            $data = file_get_contents($path);
                                            $base64 = 'data:image/'.$type.';base64,'.base64_encode($data);
                                                         $output.= "<center><img src=" .$base64. "width='20px' height='20px'></center>";
                                            $output.= '</td>';           
                                         }
                                        
                                       $output.='<td style="padding-top:10px; padding-bottom:10px; padding-right:5px; padding-left:20px; border-bottom: 1pt solid;  border-bottom: 1pt solid #e0e0e0;">'.$h_list->serial_no.'</td>';

                                         $output.='<td style="padding-top:5px; padding-bottom:5px; padding-right:40px; padding-left:-20px; border-bottom: 1pt solid #e0e0e0;">'.$hardware_info[0]->tag_no.'</td>';

                                        $output.='<td style=" border-bottom: 1pt solid #e0e0e0;">'.$hardware_info[0]->brand.'</td>';
                                         $output.='<td style="padding-top:5px; padding-bottom:5px; padding-right:5px; padding-left:20px; border-bottom: 1pt solid #e0e0e0;">'.Carbon::parse($h_list->date_deployed)->format('m/d/Y').'</td>';
                                         if($h_list->date_recalled =='') {
                                            $output.='<td style=" border-bottom: 1pt solid #e0e0e0;padding-top:5px; padding-bottom:5px; padding-right:5px; padding-left:-10px;">(in use) </td>';
                                         }
                                        else {
                                                $output.='<td style="border-bottom: 1pt solid #e0e0e0;padding-top:5px; padding-bottom:5px; padding-right:5px; padding-left:-10px;">'.Carbon::parse($h_list->date_recalled)->format('m/d/Y').'</td>';
                                         }
                                        $output.= '</tr>';
                         }

                       $output.='</table>';
                       $output.='<div style="padding-top:100px;font-size:10px;">Report Generation Date:&nbsp;'.Carbon::parse(now('utc'))->format('m/d/Y').'</div>';
                       $output.='<div class="page-break"></div>';

                    }
                    
                 
               
            }
             $pdf = \App::make('dompdf.wrapper');
             $pdf = PDF::setOptions(['images' => true, 'defaultFont' => 'Arial',]); 
             $pdf->loadHTML($output); // get all data and load into html format
             $pdf->setPaper('A4','portrait'); // paper orientation
             return $pdf->stream();

        }


  
    }

    public function deployment_hardware_report_all(){

        $output='';
        $hardware_photo = '';
        $deployment_it = new deployment_it;
        $personnel_info = '';
        $hardware_info = '';
        $all_hardware='';
         
        $all_hardware = $deployment_it::get_all_hardware();  
        $rpt_setting = reportsetting::return_report_setting('deployment_it');

         if ($rpt_setting->isEmpty())
         {
            $rpt_header ='';
            $rpt_sub_header ='';
         }
         else
         {
            $rpt_header =$rpt_setting[0]->report_header;
            $rpt_sub_header =$rpt_setting[0]->report_sub_header;;
         }

     
        if($all_hardware->isEmpty()) {
                 $output.= "<p style='font-style:italic;font-size:13px;font-weight:bold; padding-top:30px;'>No Record Found.</p>";
               
        }

        else {
           
            foreach($all_hardware as $list) {

                     $query_hardware = $deployment_it::ajax_view_deployment_by_equipment($list->serial_no);
                     $output.="<style>

                                .page-break {

                                    page-break-after:always;
                                }
                            
                                table {

                                    width : 100%;
                                    border-collapse: collapse;

                               }

                                th {
                                    padding:5px;
                                    font-size: 14px;
                                    font-weight:regular;
                                    color:#ffffff;
                                    background-color:#757575;
                                }

                                td {
                                     font-size: 13px;
                                     border-bottom: 1pt solid #e0e0e0;
                                     padding:5px;
                                    
                                }
                                
                            </style>";
                     $output.='<div style="font-size:14px;">'.$rpt_header.'</div>';
                     $output.='<div style="font-size:15px;">'.$rpt_sub_header.'</div><br>';
                     $output.='<div style="padding-top:-10px;"><hr></div>';



                    if($query_hardware->isEmpty()) {
                             $output.= "<p style='font-style:italic;font-size:13px;font-weight:bold; padding-top:30px;'>No Record Found.</p>";
                           
                    }
                    else {
                        $output.='<div style="font-weight:regular;margin-top:20px;width:695px; padding-left:10px;padding-bottom:5px;padding-top:5px;background-color:#757575; font-size:14px; color:#ffffff;">Hardware Deployment Report by Equipment </div>'; 

                        $output .= '<table style = "padding-top:15px;">';
                        $output .= '<tr>';
                        $output .= '<td style="width:130px;border-bottom:0">';

                         $hardware_info = $deployment_it::get_hardware_info($list->serial_no);

                         if($hardware_info[0]->photo_name == '') {
                            
                            $output.= '<i style="font-size:20px;">-- no photo -- </i>';
                         }
                         else {

                                 //convert the image to base64 for it to be loaded to the pdf
                                $path = base_path().'/storage/app/public/hardware_photo/IT/'.$hardware_info[0]->photo_name;
                                $type = pathinfo($path, PATHINFO_EXTENSION);
                                $data = file_get_contents($path);
                                $base64 = 'data:image/'.$type.';base64,'.base64_encode($data);
                                             $output.= "<img src=" .$base64. "width='100px' height='100px'>";

                         }
                         $output.='</td>';
                         $output.= '<td style="border-bottom:0">';
                         $output.='<div style="font-weight:regular;font-size:28px; padding-top:-10px;">';
                         $output.=$hardware_info[0]->brand. ' '.$hardware_info[0]->type;
                         $output.='<div style="font-weight:regular;font-size:12px;font-style:italic;"> S/N:&nbsp;'.$hardware_info[0]->serial_no.'</div>';
                         $output.='<div style="font-weight:regular;font-size:12px;font-style:italic;">Tag No:&nbsp;'.$hardware_info[0]->tag_no.'</div>';
                         $output.='</td>';
                         $output.='</tr>';
                         $output.='</table>';


                         $output.='<div style="font-weight:regular;padding-bottom:20px;padding-top:15px; width:700px; font-size:16px;"><center>Deployment/ Assignment Record List</center></div>';

                         $output.='<table class="responsive-table" style="width:100%; font-size:12px;">';
                         $output.='<thead><tr>';
                         $output.='<th>Photo</th>';
                         $output.=' <th>ID No</th>';
                         $output.=' <th>Personnel Name</th>';
                         $output.=' <th>Department</th>';
                         $output.=' <th>Date Assigned</th>';
                         $output.=' <th>Date Recalled</th>';
                         $output.='</thead><tbody></tr>';

                        
                         foreach($query_hardware as $h_list) {
                                    
                            $personnel_info = $deployment_it::get_personnel_info($h_list->emp_id);
                            $output.='<tr>';
                            $output.='<td>';
                            if(!empty($personnel_info[0]->photo_name))
                            {
                                 
                                 //convert the image to base64 for it to be loaded to the pdf
                                $path = base_path().'/storage/app/public/personnel_photo/'.$personnel_info[0]->emp_id.'/'.$personnel_info[0]->photo_name;
                                $type = pathinfo($path, PATHINFO_EXTENSION);
                                $data = file_get_contents($path);
                                $base64 = 'data:image/'.$type.';base64,'.base64_encode($data);
                                             $output.= "<img src=" .$base64. "width='30px' height='30px'>";

                            }
                            else
                            {
                                 $output.='--no photo--';
                             
                            }
                             $output.='</td>';
                            
                             $output.='<td>'.$personnel_info[0]->emp_id.'</td>';
                            
                             $output.='<td>'.$personnel_info[0]->last_name.', '.$personnel_info[0]->first_name.' '.$personnel_info[0]->middle_initial.'.' .'</td>';
                             $deptname = deployment_it::get_deptname($personnel_info[0]->deptcode);
                             $output.='<td>'.$deptname[0]->deptname.'</td>';
                             $output.='<td>'.Carbon::parse($h_list->date_deployed)->format('m/d/Y').'</td>';
                             if($list->date_recalled =='') {
                                $output.='<td>(in use)</td>';
                             }
                             else {
                                $output.='<td>'.Carbon::parse($h_list->date_recalled)->format('m/d/Y').'</td>';
                             }
                             $output.= '</tr></tbody>';
                         }

                         $output.='</tbody></table>';
                         $output.='<div style="padding-top:100px;font-size:10px;">Report Generation Date:&nbsp;'.Carbon::parse(now('utc'))->format('m/d/Y').'</div>';
                         $output.='<div class="page-break"></div>';

                         
                    }
            }

            $pdf = \App::make('dompdf.wrapper');
            $pdf = PDF::setOptions(['images' => true, 'defaultFont' => 'Arial',]); 
            $pdf->loadHTML($output); // get all data and load into html format
            $pdf->setPaper('A4','portrait'); // paper orientation
            return $pdf->stream();
            
        }
        
    }



}//end of entire class



    
