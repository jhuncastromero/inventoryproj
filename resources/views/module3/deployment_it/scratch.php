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




       
        if($query_hardware->isEmpty()) {
                 $output.= "<p style='font-style:italic;font-size:13px;font-weight:bold; padding-top:30px;'>No Record Found.</p>";
               
        }

        else {

            
            foreach($all_hardware as $list) {

                     $query_hardware = $deployment_it::ajax_view_deployment_by_equipment($list->serial_no);
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
                         
                    }
                }

                $pdf = \App::make('dompdf.wrapper');
                $pdf = PDF::setOptions(['images' => true, 'defaultFont' => 'Arial',]); 
                $pdf->loadHTML($output); // get all data and load into html format
                $pdf->setPaper('A4','portrait'); // paper orientation
                return $pdf->stream();
            }
        }