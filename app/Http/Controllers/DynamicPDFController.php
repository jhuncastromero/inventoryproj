<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\personnel;
use PDF;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Storage;


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
    public function get_personnel_list()
    {
        $personnel = new personnel;
        return $personnel::report_all_data();
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
         $get_personnel_lists = $this->get_personnel_list();
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

                   <div style="font-size:14px;"> SSA Consulting Group International Services</div>
                   <div style="font-size:18px;">Personnel Profile Summary Report</div>
                   <br>
                    <table>

                    <thead style="background-color:#454444; color:white;">  <!-- Column headings-->
                        <tr>
                            <th> ID No.  </th>
                            <th> Name </th>
                            <th> Department </th>
                            <th> Designation </th>
                            <th> Email </th>
                            <th> <center>Photo</center> </th>
                        </tr>
                    </thead>

                    <tbody> <!-- Table body containing records of personnel-->'; 

                    foreach($get_personnel_lists as $list)
                    {
                       $output .='
                        <tr>
                            <td height="25px" width="15%">'. $list->emp_id.'</td>
                            <td style="padding-right:10px;">'. $list->last_name .', ' . $list->first_name. ' '.$list->middle_initial. '.   </td>
                            <td width="20%">'. $list->department. '</td>
                            <td>'. $list->job_position. '</td>
                            <td width="15%">'. $list->email_add. '</td>
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
                                                      
                     }
                     $output.='</td></tr></tbody></table>';
            return $output;

    }
}
    