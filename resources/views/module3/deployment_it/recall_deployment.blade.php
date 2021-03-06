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
		 	  		<div id="div_header"><i class="material-icons">rotate_left</i>&nbsp;Recall Hardware Equipment</div> 
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
					

			</div>
			<div class = "row">
				<div class="col s10">
						<div id="div_hardware_info" style=""></div>
				</div>

			</div>

			<div class = "row">
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

					$('#type_list').on('change', function(){
						
						get_hardware_list($('#type_list').val());
						$('#div_hardware_list_bytype').css('visibility','visible');
					})

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
							url :"/deployment_it/recallequipmentdetail",
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

					function get_serial_no() {

						recall_change_status($('#hidden_serial_no').val());

					}
					function recall_change_status(xSerial) {

						var dataString;
						dataString = '';

						dataString = 'serial_no=' + xSerial;

						$.ajax({

								type: "POST",
								url : '/deployment_it/recallequipmentupdate',
								data : dataString,
								success: function(data) {

									$('#div_hardware_info').html('');
									$('#div_hardware_photo').html('');
									$('#hidden_serial_no').val('');
								}
						})
						M.toast({html:"Hardware Recall was successfully made!"});
					}

					

	</script>

@endsection