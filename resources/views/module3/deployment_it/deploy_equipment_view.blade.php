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
						
						<div class ="col s12" id="display_hardware_photo" name="display_hardware_photo"></div>
				    		
				    </div>
				    <div class="row">
				    	
				    	<div class ="col s12" id="display_hardware" name ="display_hardware"></div>

				    </div>
				     <div class="row">

			 		<div class="col s10" style="padding-top: 20px;">
			 		
			 		<div id="filter_display" name="filter_display_hardware" class="row" style = "padding-top:30px; font-size: 12px;">
			 				<div>Filter Hardware Equipment Deployment</div>
				 			<div class="input-field col s3" >
				 				<select id="month" name="month" style="font-size: 10px;">
				 					<option value ="">Choose Month</option>
				 					<option value ="01">Jan</option>
				 					<option value ="02">Feb</option>
				 					<option value ="03">Mar</option>
				 					<option value ="04">Apr</option>
				 					<option value ="05">May</option>
				 					<option value ="06">Jun</option>
				 					<option value ="07">Jul</option>
				 					<option value ="08">Aug</option>
				 					<option value ="09">Sep</option>
				 					<option value ="10">Oct</option>
				 					<option value ="11">Nov</option>
				 					<option value ="12">Dec</option>
				 				</select>
				 			
				 				
				 			</div>
				 			<div class="input-field col s4" >
				 				<input type="text" id="year" name="year" maxlength="4">
				 				<label for="year">Year</label>
				 			</div>
				 			<div class="col s3" style="padding-top: 30px;">
				 				<a class="btn btn-small" style="width:75px; height: 30px;font-size:11px;" id="filter_btn" onclick="deployment_by_equipment_month_year();clear_content();">Filter</a>
				 			</div>

			 		</div>
			 	</div>

			 </div>
				
					 <div class="row" id="div_no_value" name = "div_no_value" style="padding-top: -80px;"></div>

				    <div id="messageprompt" class="modal">
		    			<div class="modal-content">

		    				<div id="div_personnel" name="div_personnel"></div>
		      				
		    			</div>
		    			<div class="modal-footer">
		      			
		      				<a href="#!" class="modal-close waves-effect waves-green btn-flat" id="" onclick="">close</a>
		      				

		    			</div>
		  			</div>	

		  	 <div class="row">

			 	<input type="hidden" id="hidden_serial_no" name="hidden_serial_no">
			 	

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

					function clear_content() {
						$('#month').val('');
			    		$('#year').val('');

					}

					

					function find_serial() {

						var xSerial;
						xSerial = '';

						xSerial = $('#search_serialno').val();
						view_equipment_deployment_details(xSerial);
					}
					
					// AJAX

					function deployment_by_equipment_month_year() {

			    			var dataString;
			    			var xMonth;
			    			var xYear;
			    			xYear ='';
			    			xMonth = '';
			    			dataString ='';


			    			xMonth = $('#month').val();
			    			xYear = $('#year').val();
			    			xSerial = $('#hidden_serial_no').val();

			    			dataString = 'month=' + xMonth + '&serial_no=' + xSerial + '&year=' + xYear;

			    			$.ajax({

			    				type : "GET",
			    				url : "/deployment_it/viewequipmentdeploymentfilter",
			    				data : dataString,
			    				success : function(data) {

			    						$('#display_hardware').html(data);
			    						$('#month').val('');
			    						$('#year').val('');
			    						
			    				}
			    			})

			    			$('#month').val('');
			    			$('#year').val('');

			    	}
			    	
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
			    	
			    		$('#hidden_serial_no').val(xSerial);
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
			    		 $('#filter_display').css('visibility','visible')	  

			    	}

			    	function personnel_details(xEmpID) {
    		
			    		

			    		if(xEmpID === '') {
			    			
			    		}
			    		else {

			    			var dataString;
				    		dataString = '';
				    	
				    		dataString = 'emp_id=' + xEmpID;

			    			$.ajax({
					            type: "POST",
					            url: "/deployment_it/viewpersonneldetails",
					            data : dataString,
					            success: function(data){

					            	$('#div_personnel').html(data);
					            	$('#messageprompt').modal('open');

					            }
					         })
			    			
			    		}
			    	}
			    	
			    	

			    	

	</script>
	

@endsection
