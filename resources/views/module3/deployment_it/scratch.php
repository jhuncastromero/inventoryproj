$display_data='<table class="responsive-table" style="width:90%; font-size:12px;">';
        $display_data.='<td><center><img src="'. asset(Storage::url('personnel_photo/'.$query[0]->emp_id.'/'.$query[0]->photo_name)).'" width=150px" height="150px"></center></td>';
        $display_data.='<tr><td>'.$query[0]->last_name.', '.$query[0]->first_name.' '.$query[0]->middle_initial.'</td></tr>';
        $display_data.='<tr><td>'.$query[0]->job_position.'</td></tr>';
        $display_data.='<td>'.$dept_info[0]->deptname.'</td></tr>';

        $display_data.='</table>';

          /*$deployment_it = new deployment_it;
        $query = $deployment_it::where('emp_id','=',$current_emp_id);
        $query_previous_user = $deployment_it::find($query[0]->id);
        $query_previous_user->date_recalled = $date_today;
        $query_previous_user->save();*/