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

		
		 	 <div class="row">
		 	  		<div id="div_header"><i class="material-icons">transfer_within_a_station</i>&nbsp;Re-Assign Deployment</div> 
		 	 </div>

		 	 <div class="row">

			 	 	<div class = "col s4">

		 	 				<div class="input-field col s8" >
					                 
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
					                 <label for="type_list">Equipment Type List. </label>
				              </div>
				              
				              <div id="display_serials" name="display_serials"></div>

			 	 	</div>

			 	 	<div class = "col s8">

			 	 		<div class ="row" style="padding:0;">

				 	 		<div class="col s10" style="padding: 0;">

				 	 		

				 	 				<div class="col s4">
				 	 					   
				 	 					 <div id="display_hardware_photo" name="display_hardware_photo"></div>

				 	 				</div>

				 	 				<div class="col s8">
				 	 					
				 	 					 <div id="display_hardware" name="display_hardware"></div>

				 	 				</div>


				 	 		

				 	 		</div> 
				 	 	</div>

				 	 	<div class="row" style="padding:0">

				 	 		<div class="col s5" style="">

				 	 			<div id="div_current_user" name="div_current_user"></div>

				 	 		</div> 
				 	 		<div class="col s7" style="">

				 	 			<div id="div_re_assign_to" name ="div_re_assign_to">
				 	 				<div style="color:#ffffff; background-color:#212121;"> <p style="padding-bottom:5px;padding-left:10px; font-size:13px;">Re-Assign To </p>
				 	 				</div>

				 	 				<div class="input-field col s4" >
					                 
						                 <select id="deptcode" name="deptcode">  
						                 	@if(!empty($department))
						                 		<option value ="">Choose Department </option>
						                 		@foreach($department as $list)
						                 			<option value ="{{ $list->deptcode }}">{{ $list->deptname }} </option>
						                 		@endforeach
						                 	@else
						                 		<option value ="">No Current Record </option>
						     				@endif
						                 </select>
						                 <label for="deptcode">Department List. </label>
				              		</div>
				              
				       

				 	 			</div>

				 	 		</div> 
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

					//AJAX and other functions

					$('#type_list').on('change',function() {
						
						var xType;
						xType = '';

						xType = $('#type_list').val();
						$('#display_hardware').html('');
						$('#display_hardware_photo').html('');
						$('#div_current_user').html('');
						$('#div_re_assign_to').css('visibility','hidden');

						list_serials(xType);	

					})

					function list_serials(xType) {

			    		var dataString;
			    		dataString = '';
			    	
			    		dataString = 'type=' + xType;

			    		$.ajax({
					            type: "POST",
					            url: "/deployment_it/viewequipmentserials",
					            data : dataString,
					            success: function(data){

					            	$('#display_serials').html(data);
					            	
					            }


					        })
			    		$('#type_list').val('');


			    	}

			    	function view_equipment_deployment_details(xSerial) {

			    		var dataString;
			    		dataString = '';
			    	
			    		dataString = 'serial_no=' + xSerial;
                        
			    		$.ajax({
					            type: "POST",
					            url: "/deployment_it/redeploymenthardwaredetails",
					            data : dataString,
					            success: function(data){

					            	if(data[1] === 0) { //check if ajax return value is empty or not
					            		$('#div_no_value').html(data[0]);
					               		$('#display_row_serial').css('visibility','hidden');
					            		$('#display_hardware').html('');
					            		$('#display_hardware_photo').html('');
					            		$('#div_current_user').html('');
					            		$('#div_re_assign_to').html('');
					            		$('#div_re_assign_to').css('visibility','hidden');

					            	}
					            	else {

					            		$('#div_no_value').html('');
						               	$('#display_hardware').html(data[0]);
						            	$('#display_hardware_photo').html(data[1]);
						            	$('#div_current_user').html(data[2]);
						            	$('#div_re_assign_to').css('visibility','visible');

					            	}	
          	
					            }

					        })

			    	}

									
					


			    	

	</script>

@endsection