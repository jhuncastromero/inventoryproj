<?php

namespace App\Http\Controllers;

use App\personnel;
use App\department;
use App\paginationsetting;
use App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Filesystem;

class personnelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function personnelUpdate()
    {
        $deletevalue = '';
        $promptvalue = '';
        $pagination_number = '';

        $personnel = new personnel;
        $pagination_number = $personnel::pagination_setting('personnel');
        $query_personnels = $personnel::list_update();
        return view('module1.personnel.updatepersonnel',compact('query_personnels','deletevalue','promptvalue','pagination_number'));

    }
    public function personnelUpdate_details($id)
    {
        $updatevalue = '';
        $photo_status='';
        $error_title = '';
        $error_message = '';
        $error_icon = '';
        $pull_department = '';
        $deptname = '';


        $department = new department; // populate dropdown department code
        $pull_department = $department::pull_department_data();

        if ($pull_department == '')
        {
            $pull_department = '';            
        }   

        $personnel = new personnel;
        $query_personnels = $personnel::getdetails_2($id);
        $query_departments = $department::getDepartment_name($query_personnels[0]->deptcode);

        if($query_departments->isEmpty())
        {
            $deptname = '';
        }
        else
        {
            $deptname = $query_departments[0]->deptname;
        }

        return view('module1.personnel.updatedetails',compact('query_personnels','updatevalue','error_title','error_message', 'error_icon','photo_status','pull_department','deptname'));
        
    }

    public function personnelView()
    {
        return view('module1.personnel.view');
    }
    
    public function index(Request $request)
    {
       $personnel = new personnel;
       $query_personnels = $personnel::personnel_query($request->search_empid,$request->search_lastname);
       return view('module1.personnel.home',compact('query_personnels')); 
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $createvalue ='';
        $error_title = '';
        $error_message = '';
        $error_icon = '';
        $department = '';
        $pull_department = '';

        $department = new department;
        $pull_department = $department::pull_department_data();

        if ($pull_department == '')
        {
            $pull_department = '';            
        }   
        return view('module1.personnel.create', compact('createvalue','error_title','error_message','error_icon','pull_department'));   

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $error ='';
        $createvalue = '';
        $error_title = '';
        $error_message = '';
        $error_icon = '';
        $pull_department = '';


        $department = new department; // populate dropdown department code
        $pull_department = $department::pull_department_data();

        if ($pull_department == '')
        {
            $pull_department = '';            
        }   


        $photofile = $request->file('photofile');
        if($photofile)
        {
              $photo_filename = $request->emp_id.'.jpg';
              $personnel_folder_name = $request->emp_id;
              
               $photofile->storeAs('public/personnel_photo/'.$personnel_folder_name, $photo_filename);
        }
        else
        {
            $photo_filename = "";
        }



        $personnel = new personnel;
        $error = $personnel::create_new($request->emp_id, $request->last_name, $request->first_name, $request->middle_initial, $request->deptcode, $request->job_position, $photo_filename ,$request->email_add );
        
        if($error==0)
        {
            $createvalue=2;
            return view('module1.personnel.create',compact('createvalue', 'error_title','error_message','error_icon','pull_department'));
        }
        else
        {
           
            if($error == 1) 
            {
                        $error_title = "Duplicated Entry";
                        $error_icon = "warning";
                        $error_message = "An Existing Entry was found. Please check the following: Employee ID, 
                                                   Personnel Name (Last Name, First Name, Middle Initial)
                                                   and Email Address";
            }
            else if($error == 2 )
            {
                 $error_title = "Existing ID No.";
                 $error_icon = "warning";
                 $error_message = "You have entered an existing ID NO. Please check your entry.";
            }
            else if($error == 3 )
            {
                 $error_title = "Existing Personnel Name.";
                 $error_icon = "warning";
                 $error_message = "You have entered an existing Personnel Name (Last Name, First Name, Middle Initial). Please check your entry.";
            }
            else if($error == 4 )
            {
                 $error_title = "Existing Email Address";
                 $error_icon = "warning";
                 $error_message = "You have entered an existing Email Address. Please check your entry.";
            }

            //return view('module1.message_page',compact('error_title','error_icon','error_message'));

            $createvalue=1;
            return view('module1.personnel.create',compact('createvalue', 'error_title','error_message','error_icon','pull_department'));
        }

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
       
        $deptname = '';
        $department = new department;
        $personnel = new personnel;

        $query_personnels = $personnel::getdetails($id);
        $query_departments = $department::getDepartment_name($query_personnels[0]->deptcode);
        if($query_departments->isEmpty())
        {
            $deptname = '';
        }
        else
        {
            $deptname = $query_departments[0]->deptname;
        }
        return view('module1.personnel.show',compact('query_personnels','deptname'));
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

       $personnel = new personnel;
       $data= $personnel::getdetails($id);
       $query_personnels = $personnel::getdetails($id);

       $photo_filename ='';
       $go_update = '';
       $error_title = '';
       $error_icon = '';
       $error_message = '';
       $photo_status='';
       $update_photo_dbase='';
       $deptname = '';
       $test_photofile=0;
       $pagination_number = '';
       
       $old_emp_id = $data[0]->emp_id;
       $old_last_name =$data[0]->last_name;
       $old_first_name = $data[0]->first_name;
       $old_middle_initial = $data[0]->middle_initial;
       $old_email_add = $data[0]->email_add;
       $old_photo_name = $data[0]->photo_name;
       $old_folder_name = $data[0]->emp_id;  

       
       $photo_filename = $request->emp_id.'.jpg'; //photo filename
       $personnel_folder_name = $request->emp_id;  //folder name

       //$pagination_number = $personnel::pagination_setting('personnel'); // for obtaining the pagination_number setting in PAGINATIONSETTINGS table



       $department = new department; // code for identifying the department name where the employee belong. use for display purposes ex. DEP002 - CONSULTING AND TRAINING
       $query_departments = $department::getDepartment_name($data[0]->deptcode); 
        if($query_departments->isEmpty())
        {
            $deptname = '';
        }
        else
        {
            $deptname = $query_departments[0]->deptname;
        }
        
        $go_update = $personnel::update_profile($id, $request->emp_id, $request->last_name, $request->first_name, $request->middle_initial, $request->deptcode, $request->job_position, $request->email_add, $old_emp_id, $old_last_name,$old_first_name, $old_middle_initial, $old_email_add );

       if( $go_update[0] == 0 && $go_update[1] == 0 && $go_update[2] == 0 )
       {
            
            if($request->hasFile('photofile')) //test if user has selected a photo file 
            {
                $test_photofile = 1;         
            }
            else
            {
                $test_photofile = 0;
            }


            if($request->hiddentext=='new_photo') //UPLOAD photo was selected
            {
                
                if($old_photo_name == '') //no photo
                {
                        if($test_photofile != 0) //user has selected a photofile
                        {
                                                
                              //create directory and save the photofile selected
                             $photofile = $request->file('photofile');
                             Storage::makeDirectory('personnel_photo');    
                             $photofile->storeAs('public/personnel_photo/'.$personnel_folder_name, $photo_filename);               

                             //update photo_name field in personnel table
                             $update_photo_dbase = personnel::update_photo_dbase($id,$photo_filename);
                        }
                        else   //user has not selected any photofile and opted to click the update button
                        {
                               
                            //update photo_name field in personnel table
                                $photo_filename = '';
                                $update_photo_dbase = personnel::update_photo_dbase($id,$photo_filename);
                        }
                }
                else //if there is an existing photo
                {

                    if(trim($old_emp_id) == trim($request->emp_id)) //user has changed the Employee ID
                    {
                          if($test_photofile != 0) //user has selected a photofile
                         {
                             $photofile = $request->file('photofile');
                             Storage::makeDirectory('personnel_photo');    
                             $photofile->storeAs('public/personnel_photo/'.$personnel_folder_name, $photo_filename);   

                             //update photo_name field in personnel table
                             $update_photo_dbase = personnel::update_photo_dbase($id,$photo_filename);
                         }
                         else
                        {
                            //update photo_name field in personnel table
                            $update_photo_dbase = personnel::update_photo_dbase($id,$old_photo_name);
                        }

                    }
                    else //user changed the Employee ID
                    {
                          //renaming the Folder/Directory
                      Storage::move('/public/personnel_photo/'.$old_folder_name,'/public/personnel_photo/'.$request->emp_id);

                        //renaming File
                      Storage::move('/public/personnel_photo/'.$request->emp_id.'/'.$old_photo_name,'/public/personnel_photo/'.$request->emp_id.'/'.$photo_filename);

                      //update photo_name field in personnel table
                      $update_photo_dbase = personnel::update_photo_dbase($id,$photo_filename);
                    }
                    
                }    
            }                
            else if($request->hiddentext =='no_photo') // REMOVE Photo was selected
            {
                $path = '/public/personnel_photo/'.$old_folder_name;
                if(Storage::exists($path)) //there is an existing directory or folder 
                {
                    $photo_filename = '';
                    Storage::deleteDirectory($path);
                    $update_photo_dbase = personnel::update_photo_dbase($id,$photo_filename);
                }
                else //did'nt found the directory name or folder name
                {
                    $photo_filename = '';
                    $update_photo_dbase = personnel::update_photo_dbase($id,$photo_filename);   
                }
            
            }
            else  //USER DID NOT SELECT ANY OPTION ( REMOVE OR UPLOAD NEW PHOTO)
            {     //code will be similar to Upload new photo (see above)  
                 if($old_photo_name == '') //no photo
                {
                        if($test_photofile != 0) //user has selected a photofile
                        {
                                                
                              //create directory and save the photofile selected
                             $photofile = $request->file('photofile');
                             Storage::makeDirectory('personnel_photo');    
                             $photofile->storeAs('public/personnel_photo/'.$personnel_folder_name, $photo_filename);               

                             //update photo_name field in personnel table
                             $update_photo_dbase = personnel::update_photo_dbase($id,$photo_filename);
                        }
                        else   //user has not selected any photofile and opted to click the update button
                        {
                               
                            //update photo_name field in personnel table
                                $photo_filename = '';
                                $update_photo_dbase = personnel::update_photo_dbase($id,$photo_filename);
                        }
                }
                else //if there is an existing photo
                {

                    if(trim($old_emp_id) == trim($request->emp_id)) //user has changed the Employee ID
                    {
                          if($test_photofile != 0) //user has selected a photofile
                         {
                             $photofile = $request->file('photofile');
                             Storage::makeDirectory('personnel_photo');    
                             $photofile->storeAs('public/personnel_photo/'.$personnel_folder_name, $photo_filename);   

                             //update photo_name field in personnel table
                             $update_photo_dbase = personnel::update_photo_dbase($id,$photo_filename);
                         }
                         else
                        {
                            //update photo_name field in personnel table
                            $update_photo_dbase = personnel::update_photo_dbase($id,$old_photo_name);
                        }

                    }
                    else //user changed the Employee ID
                    {
                          //renaming the Folder/Directory
                      Storage::move('/public/personnel_photo/'.$old_folder_name,'/public/personnel_photo/'.$request->emp_id);

                        //renaming File
                      Storage::move('/public/personnel_photo/'.$request->emp_id.'/'.$old_photo_name,'/public/personnel_photo/'.$request->emp_id.'/'.$photo_filename);

                      //update photo_name field in personnel table
                      $update_photo_dbase = personnel::update_photo_dbase($id,$photo_filename);
                    }
                    
                }    
            }

            return redirect()->back()->with('updatevalue',2);
       }
       else
       {

            if( $go_update[0] != 0 )
            {

                 $error_title = "Existing ID No.";
                 $error_icon = "warning";
                 $error_message = "You have entered an existing ID NO. Please check your entry.";
            }
            else if( $go_update[1] != 0)
            {
                 $error_title = "Existing Personnel Name.";
                 $error_icon = "warning";
                 $error_message = "You have entered an existing Personnel Name (Last Name, First Name, Middle Initial). Please check your entry.";
            }
            else if( $go_update[2] != 0 )
            {
                 $error_title = "Existing Email Address";
                 $error_icon = "warning";
                 $error_message = "You have entered an existing Email Address. Please check your entry.";
            }


            $department = new department; // populate dropdown department code
            $pull_department = $department::pull_department_data();

            if ($pull_department == '')
            {
                $pull_department = '';            
            }

            $updatevalue = 1;

            
            return view('module1.personnel.updatedetails',compact('query_personnels','updatevalue', 'error_title','error_message','error_icon','photo_status','pull_department','deptname'));

       }   

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        

       $deletevalue = 3;
       $id = '';
       
       $id = $request->hidden_r_index;
           
       $personnel = new personnel;
       $query_personnels = $personnel::list_update();
       $deletevalue = personnel::personnel_delete($id);
       
       return redirect()->back()->with('deletevalue',3);
       //return view('module1.personnel.updatepersonnel',compact('query_personnels','deletevalue');
    }

    public function listView()
    {
        $personnel = new personnel;
        $pagination_number = $personnel::pagination_setting('personnel');
        $query_personnels = $personnel::listview();
        return view('module1.personnel.view',compact('query_personnels','pagination_number'));

    }
    public function personnel_query(Request $request)
    {

           $personnel = new personnel;
           $query_personnels = $personnel::personnel_query($request->search_empid, $request->search_lastname);
           return view('module1.personnel.home',compact('query_personnels')); 

        
    }
    public function personnel_show_details($id)
    {
        $personnel = new personnel;
        $query_personnels = $personnel::getdetails_2($id);
        return view('module1.personnel.show2',compact('query_personnels'));
    }
    public function personnel_post_message()
    {
        // for displaying warning and error messages
    }   

    //AJAX

    public function ajax_search_view_list(Request $request)  //VIEW 
    {
        $output = '';
        $id = '';
        
        $emp_id = $request->search_empid;
        $last_name = $request->search_lastname;
        $personnel = new personnel;
        $query_personnels = $personnel::ajax_search_delete_query($emp_id, $last_name);

        if($query_personnels->count()>0)
        {
            $output.="<table>";
            foreach($query_personnels as $list)
            {
               

                $output.="<tr>";
                    $output.="<td style='color:red'><i class='material-icons'>keyboard_arrow_right</i></td>";
                    $output.="<td>";
                        $output.= $list->last_name.", ".$list->first_name." " .$list->middle_initial."."; 
                    $output.="</td>";

                    //update button
                    $output.="<td style='font-size:14px;'>";
                        $output.="<a class = 'waves-effect waves-light btn-small' href='".route('personnel.show',$list->id)."'><i class='material-icons'>find_in_page</i></a>";
                    $output.="</td>";

                    

                $output.="</tr>";

            }
            $output.="</table>";
            $output.="<div style='padding-top:10px; font-style:italic; font-size:12px;'>" ;
            $output.= $query_personnels->count()." matching record(s) found";
        }
        else
        {
            $output.="<i class='material-icons' style='color:red; font-size:50px;';>warning</i>&nbsp;&nbsp;<b style='font-sie:25px;'>(Profile Not Found)</b>";
        }
        
        return $output;
        
    }


    public function ajax_preview_delete(Request $request) //UPDATE
    {
        $output = '';
        $id = '';
        $id = $request->hidden_r_index;
        
        $personnel = new personnel;
        $query_personnels = $personnel::getdetails_2($id);

        $output = '<div class="row">';
        $output .= '<div class="col s3 m3 l3">';
            $output .= '<div style="padding-top:10px;">';

                if(!empty($query_personnels[0]->photo_name))
                {
                     $output.='<img src="'. asset(Storage::url('personnel_photo/'.$query_personnels[0]->emp_id.'/'.$query_personnels[0]->photo_name)).'" width="100px" height="100px">';
                }
                else
                {
                     $output.='<i class="medium material-icons">person</i>';
                 
                }
            $output .= '</div>';
        $output .= '</div>';

        $output .= '<div class="col s10 m10 l10">';
                    $output .= '<div style="font-size:24px;padding-top:18px;">'.$query_personnels[0]->first_name.' '.$query_personnels[0]->middle_initial.'. '.$query_personnels[0]->last_name.'</div>';
                    $output .= '<div style="font-size:16; font-style:italic;">'.$query_personnels[0]->job_position.'</div>';
                    $output .= '<div style="font-size:16; font-style:italic;">'.$query_personnels[0]->deptname.'</div>';
        $output .= '</div>';
        
        $output .= '</div>';
        return $output;
        
    }


    public function ajax_search_preview_delete_list(Request $request) //UPDATE
    {
        $output = '';
        $id = '';
        
        $emp_id = $request->search_empid;
        $last_name = $request->search_lastname;
        $personnel = new personnel;
        $query_personnels = $personnel::ajax_search_delete_query($emp_id, $last_name);

        if($query_personnels->count()>0)
        {
            $output.="<table>";
            foreach($query_personnels as $list)
            {
               

                $output.="<tr>";
                    $output.="<td style='color:red'><i class='material-icons'>keyboard_arrow_right</i></td>";
                    $output.="<td>";
                        $output.= $list->last_name.", ".$list->first_name." " .$list->middle_initial."."; 
                    $output.="</td>";

                    //update button
                    $output.="<td style='font-size:14px;'>";
                        $output.="<a class = 'waves-effect waves-light btn-small' href='".route('personnel.updatedetails',['id'=> $list->id])."'>update</a>";
                    $output.="</td>";

                    //delete button
                    $output.="<td style='font-size:14px;'>";
                        $output.="<a class = 'waves-effect waves-light btn-small red' id='".$list->id ."' name='search_details' onclick=";
                        $output.='"'."document.getElementById("."'".'quick_search'."'".').value=this.id;ajax_display_details();showModal();">';
                        $output.="delete</a>";

                    $output.="</td>";

                $output.="</tr>";

            }
            $output.="</table>";
            $output.="<div style='padding-top:10px; font-style:italic; font-size:12px;'>" ;
            $output.= $query_personnels->count()." matching record(s) found";
        }
        else
        {
            $output.="<i class='material-icons' style='color:red; font-size:50px;';>warning</i>&nbsp;&nbsp;<b style='font-sie:25px;'>(Profile Not Found)</b>";
        }
        
        return $output;
        
    }
    public function ajax_search_preview_delete_details(Request $request) //UPDATE
    {
        $output = '';
        $id = '';
        $id = $request->quick_search;
        
        $personnel = new personnel;
        $query_personnels = $personnel::getdetails_2($id);

        $output = '<div class="row">';
        $output .= '<div class="col s3 m3 l3">';
            $output .= '<div style="padding-top:10px;">';

                if(!empty($query_personnels[0]->photo_name))
                {
                     $output.='<img src="'. asset(Storage::url('personnel_photo/'.$query_personnels[0]->emp_id.'/'.$query_personnels[0]->photo_name)).'" width="100px" height="100px">';
                }
                else
                {
                     $output.='<i class="medium material-icons">person</i>';
                 
                }
            $output .= '</div>';
        $output .= '</div>';

        $output .= '<div class="col s10 m10 l10">';
                    $output .= '<div style="font-size:24px;padding-top:18px;">'.$query_personnels[0]->first_name.' '.$query_personnels[0]->middle_initial.'. '.$query_personnels[0]->last_name.'</div>';
                    $output .= '<div style="font-size:16; font-style:italic;">'.$query_personnels[0]->job_position.'</div>';
                    $output .= '<div style="font-size:16; font-style:italic;">'.$query_personnels[0]->deptname.'</div>';
        $output .= '</div>';
        
        $output .= '</div>';
        return $output;
        
    }

 
}