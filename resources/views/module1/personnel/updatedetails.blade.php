@extends('layouts.master-page-layout')

@section('title-section')
	Personnel : Update Profile Details
@endsection

@section('content-section')

<div class="container">

    	 <div class="row">
				<div id="div_header"><i class="material-icons">update</i>&nbsp;Update Personnel Profile Details</div>
		 </div>

	    <FORM ACTION="{{ route('personnel.update', $query_personnels[0]->id) }}" METHOD="POST" enctype="multipart/form-data">

		 {{ csrf_field() }}
		 {{ method_field('PUT') }}


		 <div class="row">
		 
		 	<div class="col s3">
		 		@if(!empty($query_personnels[0]->photo_name))
					<img class="" src="{{ asset(Storage::url('personnel_photo/'.$query_personnels[0]->emp_id.'/'.$query_personnels[0]->photo_name))}}" width="200px" height="200px">
				@else
					<i class="large material-icons">person</i>
				@endif
			</div>
		
			<div class="col s9">
	
				<div class="row">
		 
					 	<div class="col s10">
						    <div>			 
							 	<p>
							 		<label>
							 				<input name="no_photo"  id="no_photo" type="radio"/>
							 				<span>Remove Photo</span>
							 		</label>
							 	</p>
						 	</div>

						 	<div>
							 	<p>
							 		<label>
							 				<input name="new_photo" id="new_photo" type="radio"/>
							 				<span>Upload New Photo</span>
							 		</label>
							 	</p>
							 		<div class="col s6" style="padding-bottom: 10px;" id="browse_photo" name="browse_photo">          
					            		<div class="file-field input-field">
						            		<div class="btn-small">
						            			<span>Photo</span>
						            			<input type="file" name="photofile" id="photofile">
						            		</div>
						            		<div class="file-path-wrapper" id="filepath" name="filepath">
						            			<input class="file-path validate" type="text" id="filePath">
						            		</div>
					            		</div>
					           		</div>
							</div>
						</div>	
				</div>
			 </div>

		 </div>

		 <div class="row">
  				
	     </div>


		 <div class="row">

		 	 <div class="input-field col s6">
	            <input type="text" value="{{ $query_personnels[0]->emp_id }}" class="validate" required="true" name="emp_id" id="emp_id">
	            <label for="emp_id">Employee ID#</label>
	          </div>

	          <div class="input-field col s6" >
	            <input type="text" value="{{ $query_personnels[0]->last_name }}" class="validate" required="true" name="last_name" id="last_name">
	            <label for="last_name">Last Name</label>
	          </div>

	           <div class="input-field col s6" >
	            <input type="text" value="{{ $query_personnels[0]->first_name }}" class="validate" required="true" name="first_name" id="first_name">
	            <label for="first_name">First Name</label>
	          </div>

	          <div class="input-field col s6" >
	            <input type="text" value="{{ $query_personnels[0]->middle_initial }}" class="validate" required="true" name="middle_initial" id="middle_initial">
	            <label for="middle_initial">Middle Initial</label>
	          </div>
			
			  <div class="input-field col s6" >
	             <select id="department" name="department">
                  <option value="" disabled selected> {{ $query_personnels[0]->department }} - {{ $deptname }} </option>
                  @foreach($pull_department as $list)
                         <option value="{{ $list->deptcode }}"> {{ $list->deptcode }} - {{ $list->deptname }} </option>
                  @endforeach
                </select>
        
	          </div>

			  <div class="input-field col s6" >
	            <input type="text" value="{{ $query_personnels[0]->job_position }}" class="validate" required="true" name="job_position" id="job_position">
	            <label for="job_position">Designation</label>
	          </div>

	          <div class="input-field col s6" >
	            <input type="text" value="{{ $query_personnels[0]->email_add }}" class="validate" required="true" name="email_add" id="email_add">
	            <label for="email_add">Email</label>
	          </div>

			   <div class="row">
	      			
	      			<div><input type="hidden" id="hiddentext" name="hiddentext" value="{{ $photo_status }}"></div>
	      			@if(session()->has('updatevalue'))
	      				<div><input type="hidden" id="hidden_updatevalue" name="hidden_updatevalue" value="{{ session()->get('updatevalue') }}"></div>
	      			@else
	      				<div><input type="hidden" id="hidden_updatevalue" name="hidden_updatevalue" value="{{ $updatevalue }}"></div>
	      			@endif

	            </div>

		 </div>

		 <div class="row">

		 	 <button class=" btn waves-effect waves-light" type="submit" name="action" style="background-color: #c62828;">Update<i class="material-icons right">update</i>
            </button> <span style="padding-left:20px; text-align: center;"><a href="">Cancel</a></span>

		 </div>
		 
		 </form>

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

           	$("#no_photo").click(function(){
				$("#same_photo").prop("checked", false);
				$("#new_photo").prop("checked", false);
				$("#browse_photo").prop("enabled",false);
				$("#browse_photo").css('visibility','hidden');
				$("#hiddentext").val("no_photo");
			});		

			$("#new_photo").click(function(){
				$("#no_photo").prop("checked", false);
				$("#same_photo").prop("checked", false);
				$("#browse_photo").prop("enabled",true);
				$("#browse_photo").css('visibility','visible');
				$("#hiddentext").val("new_photo");
			});

  		  var xValue;
          xValue = 0;
          xValue = $('#hidden_updatevalue').val();

          if(xValue=='1')
          {
             $('#messageprompt').modal('open');
             $('#hidden_updatevalue').val(''); 
             $('#hiddentext').val('');
          }
          else if(xValue=='2')
          {
             
          	 $('#hidden_updatevalue').val('');
             $('#hiddentext').val('');
             M.toast({html:"Profile Update(s) was successfully made!"});

          }


		});

			  


	</script>

@endsection