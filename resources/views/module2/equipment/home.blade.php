@extends('layouts.master-page-layout')

@section('title-section')

	Equipment Database

@endsection

@section('navlinks')
      
      @include('module2.equipment.equipment-nav-links')  

@endsection
      
@section('sidenavlinks')

   @include('module2.equipment.equipment-sidenav-links')

@endsection

@section('content-section')
	<div class="container">

		<div class="row">  <!--header row-->
			<div id="div_header"><i class="material-icons">laptop_mac</i>&nbsp;Equipment Database </div>
		</div>
        <div class="row">  <!--sub header-->
			<div id=""><i class="material-icons">search</i>&nbsp;Find Equipment </div>
		</div>
		<div class="row">  <!--find row-->
			<div class="col s10">
				<Form action="{{ route('hardware_equipment.searchequipment') }}" method="POST">
						{{ csrf_field() }}
					  <div class="row">
						  <div class="input-field col s6">
				                <input type="text" id="tag_no" name="tag_no">  
				                <label for="tag_no">By Tag No. </label>
			              </div>
	                      <div class="input-field col s6">
				                <input type="text" id="serial_no" name="serial_no">  
				                <label for="serial_no">By Serial No. </label>
			              </div>
			          </div>
		          	  <div class="row">
						  <div class="input-field col s6">
				                <input type="text" id="type" name="type">  
				                <label for="type">By Equipment Type </label>
			              </div>
						  <div class="input-field col s6">
				                <input type="text" id="category" name="category">  
				                <label for="category">All By Category </label>
			              </div>
			           </div>
		           	    <div class="col s4" style="padding-bottom: 50px;">
		              	  	<button class=" btn waves-effect waves-light" type="submit" name="action" style="background-color: #c62828;">Find<i class="material-icons right">search</i>
			            	</button> 
		           	   </div>
				</Form>
			</div>
		</div>	
		<div class="row">  <!--cards preview row--> 
				@if(empty($query_results))
				
				
				@else

					<div style="padding-bottom: 10px; font-size:12px;"> Search Result: </div>
					<table style="width:80%; font-size:12px;" class ="striped responsive-table">

				 	<thead>  <!-- Column headings-->
 				 		<tr>
				 			<th> <i class="small material-icons">photo</i> </th>
				 			<th> Tag No. </th>
				 			<th> Serial No.  </th>
				 			<th> Type  </th>
				 		</tr>
				 	</thead>

				 	<tbody>	<!-- Table body containing records of personnel-->
				 		@foreach($query_results as $list)
				 		<tr>
				 			<td>

								@if(!empty($list->photo_name))
									<img class="" src="{{ asset(Storage::url('hardware_photo/'.$list->category.'/'.$list->photo_name)) }}" width="40px" height="40px">

									
								@else
									<i style="font-size:12px;">( no photo )</i>
								@endif
				 			</td>
				 			@if(!empty($list->tag_no))
				 			   <td><a href="{{ route('hardware_equipment.show', $list->id) }}">{{ $list->tag_no }}</a></td>
				 			@else 
				 			   <td>------</td>
				 			@endif

				 			@if(!empty($list->serial_no))
				 			   <td><a href="{{ route('hardware_equipment.show', $list->id) }}">{{ $list->serial_no }}</a></td>
				 			@else 
				 			   <td>------</td>
				 			@endif
				 			<td>{{ $list->type }}</td>
				 			
				 			
				 		</tr>
                       
				 		@endforeach
				 		

				 	</tbody>

				 </table>	
				@endif
										

		</div>
		<div class="row">
			
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
				
				$("#tag_no").click(function(){
				  	$("#serial_no").val('');
				   	$("#type").val('');
				   	$("#category").val('');
				 });
				$("#serial_no").click(function(){
				  	$("#tag_no").val('');
				   	$("#type").val('');
				   	$("#category").val('');
				 });
				$("#type").click(function(){
				  	$("#serial_no").val('');
				   	$("#tag_no").val('');
				   	$("#category").val('');
				 });
				$("#category").click(function(){
				  	$("#serial_no").val('');
				   	$("#tag_no").val('');
				   	$("#type").val('');
				 });



		</script>


@endsection

