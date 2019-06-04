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

			 	 
				    <div class="row">  <!--find row-->
				
					<div class="col s10">
						  <div class="row">
							  <div class="input-field col s4">
					                 <select id="type_list" name="type_list">  
					                 	@if(!empty($load_categories))
					                 		<option value ="">Please Choose Equipment Type </option>
					                 		@foreach($load_categories as $list)
					                 			<option value ="{{ $list->type }}">{{ $list->type }} </option>
					                 		@endforeach
					                 	@else
					                 		<option value ="">No Equipment Type Record </option>
					     				@endif
					                 </select>
					                 <label for="type_list">Equipment Type List. </label>
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

					
					$('#type_list').on('change',function() {
						
						var xType;
						xType = '';

						xType = $('#type_list').val();

						list_serials(xType);

					})
					
					// AJAX
			    	function deployment_by_equipment() {

					   
			     
			    	}

			    	function list_serials(xType) {

			    		alert(xType);
			    	}
			    	

			    	

	</script>
	

@endsection
