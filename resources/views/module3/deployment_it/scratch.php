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
   
            $output.='<div style="font-weight:regular;margin-top:20px;width:700px; padding-left:10px;padding-bottom:5px;padding-top:5px;background-color:#757575; font-size:14px; color:#ffffff;">Hardware Equipment Deployment Report by Personnel</div>'; 
            $output .= '<table style = "padding-top:15px;">';
            $output .= '<tr>';
            $output .= '<td style="width:200px;">';
                if(!empty($query_result[0]->photo_name))
                {
                        //convert the image to base64 for it to be loaded to the pdf
                        $path = base_path().'/storage/app/public/personnel_photo/'.$query_result[0]->emp_id.'/'.$query_result[0]->photo_name;

                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $base64 = 'data:image/'.$type.';base64,'.base64_encode($data);
                                     $output.= "<img src=" .$base64. "width='150px' height='150px'>";
                                        
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
                                    $output.='<td style=" border-bottom: 1pt solid #e0e0e0;padding-top:5px; padding-bottom:5px; padding-right:5px; padding-left:-10px;">---</td>';
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
          