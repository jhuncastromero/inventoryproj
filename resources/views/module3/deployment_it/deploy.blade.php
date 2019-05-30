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
		 	 <div class="row">
		 	  		<div id="div_header"><i class="material-icons">transform</i>&nbsp;Assign/Deploy Equipment</div> 
		 	 </div>
			 <div class="row">
	              <div class="input-field col s6">
	                <select id="type" name="type">
	                  <option value="" disabled selected>Choose Equipment</option>
	                  @foreach($resultSet as $list)
	                         <option value="{{ $list->type }}"> {{ $list->type }} </option>
	                  @endforeach
	                </select>
	                <label for="category">Choose Equipment Type</label>
	             </div>
             </div>
              
              <div class="row">
	         	  <FORM ACTION = "{{ route('deployment_it.deploydetails') }}"  METHOD="POST">
	         	  	@csrf
		              <div id="equipment_list"></div>
		              <div>
		              		<input type="hidden" name="hidden_serial_no" id="hidden_serial_no" value="{{$serial_no}}">
		              </div>
		          </FORM>
		          <input type="hidden" name="hidden_flag" id="hidden_flag" value="{{$flag}}">

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
						
				      	var xFlagValue 
						xFlagValue = $('#hidden_flag').val();

						   if(xFlagValue==1)
					       {
					          M.toast({html:'Hardware Equipment was Successfully Assigned to Personnel!'});
					          $('#hidden_flag').val(''); 
					       }
					       
					});
					
					$('#type').on('change',function(){

						generate_equipment_list();
					  	
					 });
					

					// AJAX
			    	function generate_equipment_list() {

					      var xType = $('#type').val();
					      var dataString;

					      dataString='type=' + xType;
					    
					      $.ajax({
					            type: "POST",
					            url: "/deployment_it/equipmentlist",
					            data : dataString,
					            success: function(data){
					            
					            	$('#equipment_list').html(data);

					            }


					        })
					    
			 
			     
			    	}
			    	function get_serial_no(xSerial) {

			    		$('#hidden_serial_no').val(xSerial);
			    		$('#type').val("");
			    		


			    	}

			    	

	</script>

@endsection