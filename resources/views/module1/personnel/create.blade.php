@extends('layouts.master-page-layout')

@section('title-section')
  Personnel : Create New Personnel Profile
@endsection

@section('content-section')
  
<div class="container">
  <div class="row">
    <div id="div_header"><i class="material-icons">person_add</i>&nbsp;Create New Profile</div> 
  </div>
  <div class="row">
    
    <FORM ACTION="{{ route('personnel.store') }}" METHOD="POST" enctype="multipart/form-data">
      {{ csrf_field() }}
          <div class="row">
          <div class="input-field col s6">
                <input type="text" id="emp_id" name="emp_id" class="validate" required="true" >
                <label for="emp_id">Employee ID#</label>
              </div>
              <div class="input-field col s6" >
                <input type="text" id="last_name" name="last_name" class="validate" required="true">
                <label for="last_name">Last Name</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s6">
                <input type="text" id="first_name" name="first_name" class="validate" required="true">
                <label for="first_name">First Name</label>
              </div>
              <div class="input-field col s6">
                <input type="text" id="middle_initial" name="middle_initial" class="validate" required="" maxlength="3">
                <label for="middle_initial">Middle Initial</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s6">
                <select id="department" name="department" class="validate" required="true">
                  <option value="" disabled selected>Choose Department</option>
                  <option value="Dept1">Option 1 </option>
                  <option value="Dept2">Option 2 </option>
                  <option value="Dept3">Option 3 </option>
                </select>
                <label for="department">Choose Department</label>
              </div>
              <div class="input-field col s6">
                <input type="text" id="job_position" name="job_position" class="validate" required="true">
                <label for="job_position">Designation</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s6">
                <input type="email" id="email_add" name="email_add" class="validate" required="true">
                <label for="email_add">Email</label>
              </div>
            </div>
     		
            <div class="row">
  				<div class="col s6" style="padding-bottom: 20px;">          
	            	<div class="file-field input-field">
	            		<div class="btn-small">
	            			<span>Photo</span>
	            			<input type="file" name="photofile" id="photofile">
	            		</div>
	            		<div class="file-path-wrapper">
	            			<input class="file-path validate" type="text">
	            		</div>
	            	</div>
	            </div>
            </div>

            <button class=" btn waves-effect waves-light" type="submit" name="action" style="background-color: #c62828;">Create<i class="material-icons right">send</i>
            </button> <span style="padding-left:20px; text-align: center;"><a href="{{ route('personnel.create') }}">Cancel</a></span>
    </FORM>
       
  </div>

  <div class="row">

      <input type="hidden" name="hiddentext" id="hiddentext" value="{{ $createvalue }} ">

  </div>

</div>

<!-- Modal Structure -->
  <div id="messageprompt" class="modal">
    <div class="modal-content">
      <h4> <i class="medium material-icons" style="color:red;">{{ $error_icon }}</i>&nbsp;{{ $error_title }}</h4>
      <p>{{ $error_message }}</p>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-green btn-flat">OK</a>
    </div>
  </div>
  
@endsection

@section('jquery-section')

  <script type="text/javascript">
    
    $(document).ready(function(){

       var xValue;
       xValue = $('#hiddentext').val();

       if(xValue==1)
       {
          $('#messageprompt').modal('open');
          $('#hiddentext').val(''); 
       }
       else if(xValue==2)
       {
          M.toast({html:'New Profile was successfully added!'});
          $('#hiddentext').val(''); 
       }

    });



  </script>

@endsection
