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
		 	  		<div id="div_header"><i class="material-icons">person</i>&nbsp;View Hardware/Equipment Deployment by Personnel</div> 
		 	 </div>

		 	  <div class="row">  <!--sub header-->
					<div id=""><i class="material-icons">search</i>&nbsp;Find a Profile </div>
			  </div>

			  <div class="row">  <!--find row-->
					
						<div class="col s10">
							  <div class="row">
								  <div class="input-field col s4">
						                 <input type="text" id="search_empid" name="search_empid">  
						                 <label for="search_empid">Search Employee ID </label>
					              </div>
				                  <div class="input-field col s4">
						                <input type="text" id="search_lastname" name="search_lastname">  
						                <label for="search_lastname">Search by Last Name </label>
					              </div>
					              <div class="col s4" style="padding-top:25px;">
					              	  	<button class=" btn waves-effect waves-light" type="submit" name="action" style="background-color: #c62828;" onclick="deployment_by_personnel();">Find<i class="material-icons right">search</i>
						            	</button> 
						   		  </div>
					          </div>
					 	
					     </div>
				     
			 </div>
			 <div class="row" id="div_found_value" name = "div_found_value">

			 	<div class="col s3">
			 		<div id="display_personnel" name ="display_personnel"></div>
			 	</div>

			 		<div class="col s9" style="padding-top: 20px;">
			 		<div id="display_hardware" name ="display_hardware"></div>
			 	</div>

			 </div>
			 <div class="row" id="div_no_value" name = "div_no_value" style="padding-top: -80px;">
			 	<div id="no_data" name="no_data"></div>
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

					$('#search_empid').click(function(){
						$('#search_lastname').val('');

					});

					$('#search_lastname').click(function(){
						$('#search_empid').val('');

					});
					
					
					// AJAX
			    	function deployment_by_personnel() {

					      var xEmpID;
					      var xLastName;
					      var dataString;

					      xEmpID = '';
					      xLastName = '';
					      dataString = '';

					      xEmpID = $('#search_empid').val();
					      xLastName = $('#search_lastname').val();

					      dataString='emp_id=' + xEmpID + "&lastname=" + xLastName;
					    
					      $.ajax({
					            type: "POST",
					            url: "/deployment_it/viewpersonneldeploymentdetails",
					            data : dataString,
					            success: function(data){

					            	if(data[1] === 0) { //check if ajax return value is empty or not
					            	
					            	  	$('#div_found_value').css('visibility','hidden');
					            	   	$('#div_no_value').css('visibility','visible');
					            	   	$('#no_data').html(data[0]);
					            	  

					            	}

					            	else {
 										
 										$('#div_found_value').css('visibility','visible');
					            	   	$('#div_no_value').css('visibility','hidden');
					            	   	$('#display_personnel').html(data[0]);
					            	   	$('#display_hardware').html(data[1]);
					            	}

					            	//$('#display_data').html(data);	           
					            	$('#search_empid').val('');
					                $('#search_lastname').val('');
					            }


					        })
					    
			 
			     
			    	}
			    	

			    	

	</script>
	

@endsection