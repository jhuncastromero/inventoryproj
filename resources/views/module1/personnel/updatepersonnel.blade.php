@extends('layouts.master-page-layout')

@section('title-section')
	Personnel : Update Personnel Profile
@endsection

@section('content-section')
<div class="container">

		<div class="row">
			<div id="div_header"><i class="material-icons">update</i>&nbsp;Update Personnel Profile</div>
		</div>

		
			<div class="col s8">

				 <table class="responsive-table" id="table_list" style="width:90%;">

				 	<thead>  <!-- Column headings-->
 				 		<tr>
				 			<th> <i class="small material-icons">photo_camera</i> </th>
				 			<th>Name</th>
				 			<th>ID No.</th>
				 			<th colspan="2" style="text-align: center">Actions</th>
				 		</tr>
				 	</thead>

				 	<tbody>	<!-- Table body containing records of personnel-->
				 		
				 		@foreach($query_personnels as $query_personnel)
				 		
				 		<tr>
				 			
				 			<td>

								@if(!empty($query_personnel->photo_name))
									<img class="" src="{{ asset(Storage::url('personnel_photo/'.$query_personnel->emp_id.'/'.$query_personnel->photo_name))}}" width="50px" height="50px">
								@else
									<i class="small material-icons">person</i>
								@endif
				 			</td>
				 			
				 			<td>
				 				{{$query_personnel->last_name }}, {{ $query_personnel->first_name }} {{ $query_personnel->middle_initial }}.
				 			</td>

				 			<td style="padding-left: 0; padding-right: 0;
				 			 width:20%;">{{ $query_personnel->emp_id }}</td>
				 			
				 			<td style="padding-left: 0; padding-right: 0; text-align: center; width:10%;">
				 					<a class="btn btn-medium btn-flat"  href="{{ route('personnel.updatedetails',['id'=> $query_personnel->id]) }}" alt="edit profile"><i class="material-icons">create</i></a>
				 			</td>
				 			<td style="padding-left: 0; padding-right: 0; text-align: center; width:10%;">
				 				
				 					<a class="btn btn-medium btn-flat" id="{{ $query_personnel->id }}" name="previewDelete" onclick="document.getElementById('hidden_r_index').value=this.id; showModal()"><i class="material-icons">delete</i></a>
				 					
				 			</td>
				 			
				 		</tr>
                       
				 		@endforeach
				 	
					 		

				 	</tbody>

				 </table>

			</div>

		  	 <div class="row">
						
					<ul class="pagination">
		   		         <li class="waves-effect"><a href="{{$query_personnels->previousPageUrl()}}"><i class="material-icons">chevron_left</i></a></li>

		   		         	@if(($query_personnels->total()%5) > 0)
		   		         		@for($i=1; $i<=($query_personnels->total()/5)+1; $i++)
		   		         			 <li class="waves-effect circle"><a href="{{$query_personnels->url($i)}}">{{$i}}</a></li>
		   		         		@endfor
		   		         	@else
		   		         		@for($i=1; $i<=($query_personnels->total()/5); $i++)
		   		         			 <li class="waves-effect"><a href="{{$query_personnels->url($i)}}">{{$i}}</a></li>
		   		         		@endfor
		   		         	@endif

		   		         <li class="waves-effect"><a href="{{$query_personnels->nextPageUrl()}}"><i class="material-icons">chevron_right</i></a></li>
		   		    </ul>     
		
   		    </div>
	        <div class="row">  <!--Search Header-->
				<div id=""><i class="material-icons">search</i>&nbsp;<b>Quick Search Profile to Update </b></div>
			</div>

			<!--SEARCH PART-->
			<div class="row">

				
				<div class="col s5 m5">
								
					  <div class="row">

						  	<div class="input-field col s6">
			        	         <input type="text" id="search_empid" name="search_empid">  
			            	     <label for="search_empid"> ID No. </label>
		              		</div>
	                   </div>
					   
					   <div class="row">                   
		              		<div class="input-field col s6">
			                	 <input type="text" id="search_lastname" name="search_lastname">  
			                	<label for="search_lastname">Last Name </label>
		              		</div>
		              	</div>
		              
		              	 <div class="row" style="padding-bottom: 50px;">
		              		  	<button class=" btn waves-effect waves-light" type="submit" id="searchButton" style="background-color: #c62828;">Find<i class="material-icons right">search</i>
			            		</button> 
						</div>

		         </div>

		         <div class="col s6 m6">
		         	
			         		<div id="searchPersonnelDetail">

			         		</div>
			         	
		         </div>
		     	
	          
			</div>				

		</div>

		<div>
			       @if(session()->has('deletevalue'))
	      				<div><input type="hidden" id="hidden_deletevalue" name="hidden_deletevalue" value="{{ session()->get('deletevalue') }}"></div>
	      			@else
	      				<div><input type="hidden" id="hidden_deletevalue" name="hidden_deletevalue" value="{{ $deletevalue }}"></div>
	      			@endif
	    </div>
		<!-- FORM Below is for MODAL Window-->
		 <form action=" {{ route('personnel.destroy',0) }}" method="POST" id="delete-form">
	          {{ csrf_field() }}
              {{ method_field('DELETE') }}
	
			 <!-- Modal Structure -->
			  <div id="messageprompt" class="modal">
		    		<div class="modal-content">
		      			<h4> <i class="medium material-icons" style="color:red;">info</i>&nbsp;</h4>
		      				<b>Are you sure you want to delete this profile?</b>
		      				<div id="personnelDetails"></div>
		    		</div>
		    		<div class="modal-footer">
		      			
		      			<a href="#!" class="modal-close waves-effect waves-green btn-flat" id="yesDelete" onclick="document.getElementById('delete-form').submit();">Yes</a>
		      			<a href="#!" class="modal-close waves-effect waves-green btn-flat">No</a>
		    		</div>
		  		</div>		

			<div class="row">
				<input type="text" id="hidden_r_index" name = "hidden_r_index">
				<input type="text" id="quick_search" name = "quick_search">
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
             
          	 M.toast({html:"Profile was Deleted Successfully!"});
          	 $('#hidden_deletevalue').val('');
          }

		});

		        
         //javascript

		 function showModal()
		 {
		 	$('#messageprompt').modal('open');
		 }

		 //AJAX & JQUERY

		//for delete button already visible		
		 $("[name='previewDelete'").click(function(){

		 	var hidden_r_index = $('#hidden_r_index').val();
		 	var dataString = "hidden_r_index=" + hidden_r_index;
		 	
		 	$.ajax({
      			type: "POST",
      			url: "/personnel/previewdelete",
      			data : dataString,
      			success: function(data){
      				
      				$('#personnelDetails').html(data);
      				$('#hidden_r_index').val('');		
      			}

     		})
     		
		 	
		 });

		
		 //invoking ajax code when delete button under quick search profile was clicked
		 function ajax_display_details(){

		 	var quick_search = $('#quick_search').val();
		 	var dataString = "quick_search=" + quick_search;
		 	
		 	$.ajax({
      			type: "POST",
      			url: "/personnel/updatesearchdetails",
      			data : dataString,
      			success: function(data){
      				
      				$('#personnelDetails').html(data);
      			}

     		})

		 }

		 //for ajax quick search
		  $("#searchButton").click(function(){

		 	var sEmpid = $('#search_empid').val();
		 	var sLastName = $('#search_lastname').val();
		 	var dataString = "search_empid=" + sEmpid + "&search_lastname=" + sLastName;
		 	
		 	$.ajax({
      			type: "POST",
      			url: "/personnel/updatesearchpreview",
      			data : dataString,
      			success: function(data){
      				
      				$('#searchPersonnelDetail').html(data);
      				
      			}

     		})
     		
		 	
		 });

		

	</script>

@endsection