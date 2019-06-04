@extends('layouts.master-page-layout')

@section('title-section')

	IT Equipment Database : Deploy Equipment

@endsection

@section('navlinks')
      
      @include('module3.deployment_it.equipment-nav-links')  

@endsection
      
@section('sidenavlinks')

   @include('module3.deployment_it.equipment-sidenav-links')

@endsection

@section('content-section')

 <div class="container">
			 
			 <div class="row" style="padding-bottom: 20px;">
					<div id="div_header"><i class="material-icons">transform</i>&nbsp;Assign/ Deploy Equipments</div>	
			 </div>

			 @if(!empty($query_result))

			 	<div class="row">
			 		<div id="div_sub_header" style="padding-bottom: 50px;"><i class="material-icons" style="color:#c62828;">desktop_mac</i>&nbsp;Hardware Equipment Details</div>	

			 		<div class="col s4">
						<div>
							@if(!empty($query_result[0]->photo_name))
								<img class="" src="{{ asset(Storage::url('hardware_photo/'.$query_result[0]->category.'/'.$query_result[0]->photo_name)) }}" width="200px" height="200px">
							@else
								<div><i class="large material-icons">photo</i></div>
								<div style="font-style: italic; font-size:14px; padding-left: 10px;">(no photo)</div>
							@endif
						</div>
					</div>
					<div class="col s6">

						<table class="responsive-table" style="width: 100%; font-size:12px;">
						
				    	<tr>
				    		<td style="font-weight: bold;">Serial No.</td>
				    		@if(!empty($query_result[0]->serial_no))
				    			<td> {{ $query_result[0]->serial_no }}</td>
				    		@else
				    			<td> ------ </td>
				    		@endif
				    	</tr>

				    	<tr>
				    		<td style="font-weight: bold;">Equipment Tag No.</td>
				    		@if(!empty($query_result[0]->tag_no))
				    			<td> {{ $query_result[0]->tag_no }}</td>
				    		@else
				    			<td> ------ </td>
				    		@endif
				    	</tr>
				    	<tr>
				    		<td style="font-weight: bold;">Brand/ Make</td>
				    		@if(!empty($query_result[0]->brand))
				    			<td> {{ $query_result[0]->brand}}</td>
				    		@else
				    			<td> ------ </td>
				    		@endif
				    	</tr>
			    	

				    	<tr>
				    		<td style="font-weight: bold;">Description</td>
				    		@if(!empty($query_result[0]->description))
				    			<td> {{ $query_result[0]->description }}</td>
				    		@else
				    			<td> ------ </td>
				    		@endif
				    	</tr>

				    	<tr>
				    		<td style="font-weight: bold;">Status</td>
				    		@if(!empty($query_result[0]->status))
				    			<td> {{ $query_result[0]->status }}</td>
				    		@else
				    			<td> ------ </td>
				    		@endif
				    	</tr>

				    
				 	</table> 

				 	

					</div>
				</div>
				<div class="row">
						<div id="div_sub_header" style="padding-bottom: 30px;"><i class="material-icons" style="color:#c62828; font-size:30px;">group</i>&nbsp;Assign/ Deploy To</div>
						
						<div class="row">  <!--find row-->
							<div class="col s10">
								<div class = "row">
									  <div class="input-field col s4">
							                 <input type="text" id="search_empid" name="search_empid">  
							                <label for="search_empid">Search Employee ID </label>
						              </div>
								 	  <div class="input-field col s4">
							                 <input type="text" id="search_lastname" name="search_lastname">  
							                <label for="search_lastname">Search Employee Lastname </label>
						              </div>
								      <div class="col s4" style="padding-top:25px;">
							           		<button class=" btn waves-effect waves-light" onclick="ajax_find_employee();showModal();" style="background-color: #c62828;">Find<i class="material-icons right">search</i>
							           		</button>
					            	  </div>
					            </div>

				            </div>

						</div>
			            
		         
						           
					        
			         

				</div>

				
			 @else
			 	<div><i> Whoops! Something went wrong. Please try again.</i> </div>
			 @endif
			
			
			 <FORM ACTION = "{{ route('deployment_it.store') }}" METHOD="POST">
			 	@csrf
			
  		      <!-- Modal Structure -->
			  <div id="messageprompt" class="modal">
		    		<div class="modal-content">
		      			<h4> <i class="medium material-icons" style="color:blue;">info</i>&nbsp;</h4>
		      				<div id="div_employee">value here</div>
		      				<div>Add Remarks:</div>
		      				<div><input type="text" id="remarks" name="remarks" value="{{ $remarks }}"></div>
		    		</div>
		    		<div class="modal-footer">
		      			
		      			<button class="waves-effect waves-light btn-small" type="submit" name="action" style="background-color: #c62828;">Yes </button>
		      			<a href="#!" class="modal-close waves-effect waves-green btn-flat">NO</a>


		    		</div>
		  		</div>	

		  		<div>
		                 <input type="hidden" name="hidden_serial_no" id="hidden_serial_no" value="{{$query_result[0]->serial_no}}">
		                 <input type="hidden" name="hidden_emp_id" id="hidden_emp_id">
		                 <input type="hidden" name="hidden_deptcode" id="hidden_deptcode">
		                 <input type="hidden" name="hidden_room_no" id="hidden_room_no">

		        </div>
		      </FORM>
				

		</div>

@endsection

@section('jquery-section')

	<script type="text/javascript">
					

					$.ajaxSetup({
			     		 headers: {
			        		'X-CSRF-TOKEN' : $('meta[name="csrf_token"').attr('content')
			      		}
			     	});

					// JQuery
					$(document).ready(function(){
						
					
				      
					});

					$('#search_empid').click(function(){
						$('#search_lastname').val('');
					});

					$('#search_lastname').click(function(){
						$('#search_empid').val('');
					});

					
					function showModal() {

				 		var xEmpID = $('#search_empid').val();
				 		var xEmpLastName= $('#search_lastname').val();

				 		if ((xEmpID =='') && (xEmpLastName =='')) {

				 		}
				 		else {

				 			$('#messageprompt').modal('open');
				 			$('#search_empid').val('');
					        $('#search_lastname').val('')
					        $('#remarks').val('');
				 		}
				 		

					 }
						
				    // AJAX
			    	function ajax_find_employee() {

					      var xEmpID;
					      var xEmpLastName;
					      var dataString;

					      xEmpID = $('#search_empid').val();
					      xEmpLastName = $('#search_lastname').val();

					      dataString='emp_id=' + xEmpID + '&emp_lastname=' + xEmpLastName;
					    
					      $.ajax({
					            type: "POST",
					            url: "/deployment_it/employeedetails",
					            data : dataString,
					            success: function(data){


					             $('#div_employee').html(data[0]);
					              $('#hidden_emp_id').val(data[1]);
					              $('#hidden_deptcode').val(data[2]);
					              $('#hidden_room_no').val(data[3]);
					              $('#search_empid').val('');
					              $('#search_lastname').val('')

					            }


					        })
					    
			 
			     
			    	}

			    	
			    	

	</script>

@endsection