@extends('layouts.master-page-layout')

@section('title-section')

	IT Equipment Database : View Deploy Equipment

@endsection

@section('navlinks')
      
      @include('module3.deployment_it.equipment-nav-links')  

@endsection
      
@section('sidenavlinks')

   @include('module3.deployment_it.equipment-sidenav-links')

@endsection

@section('content-section')

		<div class="container">
			 	 <div class="row">
			 	  		<div id="div_header"><i class="material-icons">scanner</i>&nbsp;View Equipment Deployment </div> 
			 	 </div>

			 	 
				    <div class="row"  id="div_drop_down">  <!--find row-->
				
						 <div class="col s10">
							
								  <div class="input-field col s5" >
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
					               <div class="input-field col s5">
						                 <input type="text" id="search_serialno" name="search_serialno">  
						                 <label for="search_serialno">Equipment Serial No.</label>
					              </div>
					              <div class="col s2" style="padding-top:25px;">
					              	  	<button class=" btn waves-effect waves-light" type="submit" name="action" style="background-color: #c62828;" onclick="find_serial();">Find<i class="material-icons right">search</i>
						            	</button> 
						   		  </div>
				                  
						   		  

				                 
						   </div>
				    </div>
				    <div class ="row" id="div_row_serial" name = "div_row_serial">

					       <div class="col s8"  id="display_serials" name ="display_serials"></div>

				     </div>
		

				    <div class="row">

				    	<div class ="col s3" id="display_hardware_photo" name="display_hardware_photo"></div>
				    	<div class ="col s9" id="display_hardware" name ="display_hardware"></div>
				    	
				    
			     

				    </div>


					
					 <div class="row" id="div_no_value" name = "div_no_value" style="padding-top: -80px;"></div>


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

					
					$('#type_list').on('change',function() {
						
						var xType;
						xType = '';

						xType = $('#type_list').val();
						$('#display_row_serial').css('visibility','visible');
						$('#display_hardware').html('');
						$('#display_hardware_photo').html('');
						$('#search_serialno').val('');
						$('#div_no_value').html('');

						list_serials(xType);	

					})

					$('#search_serialno').on('change',function() {

						$('#display_row_serial').html('');



					})

					

					function find_serial() {

						var xSerial;
						xSerial = '';

						xSerial = $('#search_serialno').val();
						view_equipment_deployment_details(xSerial);
					}
					
					// AJAX
			    	
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


			    	}

			    	function view_equipment_deployment_details(xSerial) {

			    		var dataString;
			    		dataString = '';
			    	
			    		dataString = 'serial_no=' + xSerial;

			    		$.ajax({
					            type: "POST",
					            url: "/deployment_it/viewequipmentdeploymentdetails",
					            data : dataString,
					            success: function(data){

					            	if(data[1] === 0) { //check if ajax return value is empty or not
					            		$('#div_no_value').html(data[0]);
					            		$('#display_serials').html('');
					            		$('#display_row_serial').css('visibility','hidden');
					            		$('#display_hardware').html('');
					            		$('#display_hardware_photo').html('');

					            	}
					            	else {

					            		$('#display_serials').html('');
					            		$('#div_no_value').html('');
						            	$('#display_row_serial').css('visibility','hidden');
						            	$('#display_hardware').html(data[0]);
						            	$('#display_hardware_photo').html(data[1]);


					            	}	

					            	
					            	
					            }


					        })


			    	}
			    	

			    	

	</script>
	

@endsection
