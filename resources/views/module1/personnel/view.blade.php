@extends('layouts.master-page-layout')

@section('title-section')
	Personnel : View Personnel Profile
@endsection

@section('content-section')
	
	<div class="container">

		<div class="row">
			<div id="div_header"><i class="material-icons">view_list</i>&nbsp;List View of Personnels</div>
		</div>

		<div class="row">
			
			<div class="col s8">

				 <table class="responsive-table" style="width:90%;">

				 	<thead>  <!-- Column headings-->
 				 		<tr>
				 			<th> <i class="small material-icons">photo_camera</i> </th>
				 			<th> Name </th>
				 			<th> ID No.  </th>
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
				 			<td>{{ $query_personnel->last_name }}, {{ $query_personnel->first_name }} {{ $query_personnel->middle_initial }}.  </td>
				 			<td><a href="{{ route('personnel.show',$query_personnel->id) }}">{{ $query_personnel->emp_id }}</a></td>
				 			
				 			
				 		</tr>
                       
				 		@endforeach
				 		

				 	</tbody>

				 </table>

			</div>
			

		</div>
		<div class="row">
			
			<!---->
			@if($pagination_number != 0)
				<div>
					<ul class="pagination">
		   		         <li class="waves-effect"><a href="{{$query_personnels->previousPageUrl()}}"><i class="material-icons">chevron_left</i></a></li>

		   		         	@if(($query_personnels->total()% $pagination_number) > 0)
		   		         		@for($i=1; $i<=($query_personnels->total()/ $pagination_number)+1; $i++)
		   		         			 <li class="waves-effect circle"><a href="{{$query_personnels->url($i)}}">{{$i}}</a></li>
		   		         		@endfor
		   		         	@else
		   		         		@for($i=1; $i<=($query_personnels->total()/ $pagination_number); $i++)
		   		         			 <li class="waves-effect"><a href="{{$query_personnels->url($i)}}">{{$i}}</a></li>
		   		         		@endfor
		   		         	@endif

		   		         <li class="waves-effect"><a href="{{$query_personnels->nextPageUrl()}}"><i class="material-icons">chevron_right</i></a></li>
		   		    </ul> 
		   		  </div>
		    @else
		    	<div style="font-size:14px; font-style:italic;">
		    		<p> (A Pagination Control should appear here. However, Pagination was not properly set. Please see PAGINATION on Settings Module)</p>
		    	</div>
   		    @endif    

		</div>
		
	        <div class="row">  <!--Search Header-->
				<div id=""><i class="material-icons">search</i>&nbsp;<b>Quick Search Profile</b></div>
			</div>

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

		         <div class="col s4 m4">
		         	
			         		<div id="searchPersonnelDetail">

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

			    //for ajax quick search
				$("#searchButton").click(function(){
				 	var sEmpid = $('#search_empid').val();
				 	var sLastName = $('#search_lastname').val();
				 	var dataString = "search_empid=" + sEmpid + "&search_lastname=" + sLastName;
						 	
				 	$.ajax({
				      		type: "POST",
				      		url: "/personnel/viewlist",
				      		data : dataString,
				      		success: function(data){
				      				
				      				$('#searchPersonnelDetail').html(data);
				      				
				      		}

				     })
	     		
		 	
		 		});

		     	
		</script>

@endsection