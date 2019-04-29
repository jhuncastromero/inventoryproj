@extends('layouts.master-page-layout')

@section('title-section')

  Equipment : Update Database
@endsection

@section('navlinks')
      
      @include('module2.equipment.equipment-nav-links')  

@endsection
      
@section('sidenavlinks')

   @include('module2.equipment.equipment-sidenav-links')

@endsection

@section('content-section')

<div class="container">

		<div class="row">
			<div id="div_header"><i class="material-icons">update</i>&nbsp;Update Equipment Information</div>
		</div>

		<div class="row">
			
			<div class="col s8">

				 <table class="highlight responsive-table" style="width:120%; font-size:14px;" >

				 	<thead>  <!-- Column headings-->
 				 		<tr>
				 			<th> <i class="small material-icons">photo</i> </th>
				 			<th> Tag No. </th>
				 			<th> Serial No.  </th>
				 			<th> Type  </th>
				 			<th> Category</th>
				 			<th colspan=2> <center>Actions</center></th>
				 			<th></th>
				 		</tr>
				 	</thead>

				 	<tbody>	<!-- Table body containing records of personnel-->
				 		
				 		@if(!empty($query_results))

						 		@foreach($query_results as $list)
						 		<tr>
						 			<td>

										@if(!empty($list->photo_name))
											<img class="" src="{{ asset(Storage::url('hardware_photo/'.$list->category.'/'.$list->photo_name)) }}" width="50px" height="50px">

											
										@else
											<i style="font-size:12px;">( no photo )</i>
										@endif
						 			</td>
						 			<td>{{ $list->tag_no }}</td>
						 			<td>{{ $list->serial_no }}</td>
						 			<td>{{ $list->type }}</td>
						 			<td><center>{{ $list->category }}</center></td>
						 			<td><a class="btn btn-medium btn-flat" href="{{ route('hardware_equipment.updatedetails',['id' => $list->id]) }}"><i style="font-size:18px;"class="small material-icons">create</i></a></td>
						 			<td><a class="btn btn-medium btn-flat" onclick="document.getElementById('hidden_r_index').value=this.id; showModal();" id="{{$list->id}}" name="previewDelete"><i style="font-size:18px;"class="small material-icons">delete</i></a></td>
						 			
						 		</tr>
		                       
						 		@endforeach
						@endif
				 		

				 	</tbody>

				 </table>	
			</div>
			

		</div>
		<div class="row" >
			
			<!---->
			@if($pagination_number != 0 || $action !='filter')
				<div id="div_pagination">
					<ul class="pagination">
		   		         <li class="waves-effect"><a href="{{$query_results->previousPageUrl()}}"><i class="material-icons">chevron_left</i></a></li>

		   		         	@if(($query_results->total()% $pagination_number) > 0)
		   		         		@for($i=1; $i<=($query_results->total()/ $pagination_number)+1; $i++)
		   		         			 <li class="waves-effect circle"><a href="{{$query_results->url($i)}}">{{$i}}</a></li>
		   		         		@endfor
		   		         	@else
		   		         		@for($i=1; $i<=($query_results->total()/ $pagination_number); $i++)
		   		         			 <li class="waves-effect"><a href="{{$query_results->url($i)}}">{{$i}}</a></li>
		   		         		@endfor
		   		         	@endif

		   		         <li class="waves-effect"><a href="{{$query_results->nextPageUrl()}}"><i class="material-icons">chevron_right</i></a></li>
		   		    </ul> 
		   		  </div>
		    @endif    

		</div>
		<div>
			       @if(session()->has('deletevalue'))
	      				<div><input type="hidden" id="hidden_deletevalue" name="hidden_deletevalue" value="{{ session()->get('deletevalue') }}"></div>
	      			@else
	      				<div><input type="hidden" id="hidden_deletevalue" name="hidden_deletevalue" value="{{ $deletevalue }}"></div>
	      			@endif
	    </div>

	    <Form action="{{ route('hardware_equipment.updatefilterview') }}" method="GET">
			<div class="row">
				   <div style="padding-top: 20px;">
				   	  <i class="material-icons">filter</i> &nbsp;<b>Filter List</b>
				   </div>
			  	  	<div class="input-field col s4">
	                   <input type="text" id="type" name="type">  
	                   <label for="type">By Equipment Type </label>
	              	</div>

	              	<div class="input-field col s4"> 	
		               	<select id="category" name="category">
		              		<option value ="" disabled selected> Choose Category </option>
		              		<option value ="IT">IT</option>
		              		<option value ="Non-IT">Non-IT</option>
	              		</select>
	                    <label>By Category</label>
	              	</div>
			</div>
			<div class="col s4" style="padding-bottom: 50px;">
			            <button class=" btn waves-effect waves-light btn-small " type="submit" name="action" style="background-color: #c62828;" id="filter">Filter<i class="material-icons right">search</i>
				        </button> 
				        <a href="{{ route('hardware_equipment.updatelist') }}" class="modal-close waves-effect waves-green btn-small"><i class="material-icons">refresh</i></a>
			</div>
		</Form>
		<!-- FORM Below is for MODAL Window-->
		 <form action="{{ route('hardware_equipment.destroy',0) }}" method="POST" id="delete-form">
	          {{ csrf_field() }}
              {{ method_field('DELETE') }}
	
			 <!-- Modal Structure -->
			  <div id="messageprompt" class="modal">
		    		<div class="modal-content">
		      			<h4> <i class="medium material-icons" style="color:red;">info</i>&nbsp;</h4>
		      				<b>Are you sure you want to delete this hardware equipment?</b>
		      				<div id="div_hardware"></div>
		    		</div>
		    		<div class="modal-footer">
		      			
		      			<a href="#!" class="modal-close waves-effect waves-green btn-flat" id="yesDelete" onclick="document.getElementById('delete-form').submit();">Yes</a>
		      			<a href="#!" class="modal-close waves-effect waves-green btn-flat">NO</a>
		    		</div>
		  		</div>		

			<div class="row">
				<input type="hidden" id="hidden_r_index" name = "hidden_r_index">
				<input type="hidden" id="quick_search" name = "quick_search">
			</div>			
		  </form>
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

		          var xValue;
		          xValue = 0;
		          xValue = $('#hidden_deletevalue').val();

		           if(xValue==3)
		          {
		             
		          	 M.toast({html:"Hardware Equipment was Deleted Successfully!"});
		          	 $('#hidden_deletevalue').val('');
		          }

				});

			     function showModal()
				 {
				 	$('#messageprompt').modal('open');
				 }

				  //for delete button already visible		
				 $("[name='previewDelete'").click(function(){

				 	var hidden_r_index = $('#hidden_r_index').val();
				 	var dataString = "hidden_r_index=" + hidden_r_index;
				 	
				 	$.ajax({
		      			type: "POST",
		      			url: "/hardware_equipment/preview_hardware",
		      			data : dataString,
		      			success: function(data){
		      				
		      				$('#div_hardware').html(data);
		      			}

		     		})
		     		
				 	
				 });
				
				
		     	
		</script>

@endsection
	