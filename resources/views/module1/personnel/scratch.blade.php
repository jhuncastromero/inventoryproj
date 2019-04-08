$query_departments = $department::getDepartment_name($request->department); // code for identifying the department name where the employee belong. use for display purposes ex. DEP002 - CONSULTING AND TRAINING
        if($query_departments->isEmpty())
        {
            $deptname = '';
        }
        else
        {
            $deptname = $query_departments[0]->deptname;
        }

        $department = new department; // populate dropdown department code
            $pull_department = $department::pull_department_data();

            if ($pull_department == '')
            {
                $pull_department = '';            
            }