@extends('layouts.master-page-layout')

@section('title-section')

	IT Equipment Database : Deploy Equipment

@endsection

@section('style-section')

	<style>

		.btn-small {
			height: 24px;
			line-height: 24px;
			font-size:11px;
			
		}
		.modal {
			height: 80%;
			width: 80%;
		}

	</style>
		
@endsection

@section('navlinks')
      
      @include('module3.deployment_it.equipment-nav-links')  

@endsection
      
@section('sidenavlinks')

   @include('module3.deployment_it.equipment-sidenav-links')

@endsection

@section('content-section')

		<div class="container">
		 	 <div class="row" style = "">
		 	  		<div id="div_header"><i class="material-icons">transfer_within_a_station</i>&nbsp;Re-Assign Hardware Equipment</div> 
		 	 </div>

		 	 <div class="row">

	 	   			<div class="input-field col s6" >
			                 
			                 <select id="type_list" name="type_list">  
			                 	@if(!empty($load_categories))
			                 		<option value ="">Choose Equipment Type </option>
			                 		@foreach($load_categories as $list)
			                 			<option value ="{{ $list->type }}">{{ $list->type }} </option>
			                 		@endforeach
			                 	@else
			                 		<option value ="">No Equipment Type Record </option>
			     				@endif
			                 </select>
			                 <label for="type_list">Equipment Type List </label>
		             </div>
				              
				   	  		
			 	 
			</div>
			<div class = "row">

					<div id="div_hardware_list_bytype"></div>
					<div class="col s3" style="">
						<div id="div_hardware_photo"></div>
					</div>
					<div class="col s8 offset-s1">
						<div id="div_hardware_info" style=""></div>
					</div>

			</div>

			 <div id="messageprompt" class="modal">
    			<div class="modal-content">
    				
    				<div class="row" style="background-color: #c62828; padding: 5px; color:#ffffff; border-radius: 3px; font-size:15px;">
    					<div class="col s10"><i class="material-icons">transfer_within_a_station</i>&nbsp;Re-Assign Hardware Equipment</div> 
    				</div>
    				<div class="row">
    					<div class="col s6" style="">

    						<select id="dept_list" name="dept_list">  
			                 	@if(!empty($load_deptcode))
			                 		<option value ="">Choose a Department </option>
			                 		@foreach($load_deptcode as $list)
			                 			<option value ="{{ $list->deptcode }}">{{ $list->deptname }} </option>
			                 		@endforeach
			                 	@else
			                 		<option value ="">No Department Record Found </option>
			     				@endif
			                 </select>
			                 <label for="type_list">Department List </label>
			                 <div id="div_personnel_info" name="div_personnel_info"></div>

    					</div>
    					<div class="col s6" style="">
    						 <div id="div_personnel_detail" name="div_personnel_detail"></div>
    					</div>
    				</div>
      				
    			</div>
    			<div class="modal-footer">
      			
      				<input type="hidden" id ="hidden_emp_id" name="hidden_emp_id">
      				<input type="hidden" id ="hidden_serial_no" name="hidden_serial_no">

    			</div>
  			</div>		
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

					$('#type_list').on('change', function(){
						
						get_hardware_list($('#type_list').val());
						$('#div_hardware_list_bytype').css('visibility','visible');
					})

					$('#dept_list').on('change', function(){
						
						get_personnel_list($('#dept_list').val());
						
					})


					//AJAX and other functions

					function open_modal() {
						$('#messageprompt').modal('open');
						$('#hidden_emp_id').val('');
						$('#div_personnel_info').html('');
						$('#div_personnel_detail').html('')

					}

					function close_modal() {
						$('#messageprompt').modal('close');
						$('#hidden_emp_id').val('');
						$('#hidden_serial_no').val();
						$('#div_personnel_info').html('');
						$('#div_personnel_detail').html('')

					}

					function get_personnel_list(xDeptCode) {

						var dataString;
						dataString = '';

						dataString ='deptcode=' + xDeptCode;

						$.ajax({

							    type: "POST",
					            url: "/deployment_it/reassignlistpersonnel",
					            data : dataString,
					            success: function(data){

					            	$('#div_personnel_info').html(data);


					            }

						})
						$('#dept_list').val('')

					}
					function get_personnel_detail(xEmpID) {

						var dataString;
						dataString = '';
						dataString = 'emp_id=' + xEmpID;

						$.ajax({

								type : "POST",
								url : "/deployment_it/reassignpersonneldetail",
								data : dataString,
								success : function(data){

									$('#div_personnel_detail').html(data);

								}
						})
						$('#hidden_emp_id').val(xEmpID);

					}

					function get_hardware_list(xType) {

						var dataString;
						dataString = '';

						dataString='type=' + xType;

						$.ajax({

							    type: "POST",
					            url: "/deployment_it/reassignlistequipment",
					            data : dataString,
					            success: function(data){

					            	$('#div_hardware_list_bytype').html(data);

					            }

						})
						$('#type_list').val('');
						$('#div_hardware_photo').html('');
						$('#div_hardware_info').html('');

					}

					function get_hardware_detail(xSerial) {

						var dataString;
						dataString = '';

						dataString = 'serial_no=' + xSerial;

						$.ajax({

							type : "POST",
							url :"/deployment_it/reassignequipmentdetail",
							data : dataString,
							success: function(data) {
								$('#div_hardware_photo').html(data[0]);
								$('#div_hardware_info').html(data[1]);
								
							}

						})
						
						$('#div_hardware_list_bytype').html('');
						$('#div_hardware_list_bytype').css('visibility','hidden');
						$('#hidden_serial_no').val(xSerial);
					

					}

					function reassign_hardware_equiment() {

						var dataString;
						dataString = '';


						dataString = 'emp_id=' + $('#hidden_emp_id').val() + '&serial_no=' + $('#hidden_serial_no').val();

						$.ajax({

								type : "POST",
								url : "/deployment_it/reassignhardwareequipment",
								data : dataString,
								success : function(data) {

								 
						
								}
						})
						close_modal();
						M.toast({html:"Hardware Equipment Re-assignment was successfully made!"});
						get_hardware_detail($('#hidden_serial_no').val());
						

					}


									
					


			    	

	</script>

@endsection