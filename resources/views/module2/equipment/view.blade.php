@extends('layouts.master-page-layout')

@section('title-section')

  Equipment : View Equipment Database

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
			<div id="div_header"><i class="material-icons">view_list</i>&nbsp;Equipment List </div>
		</div>

					
		<div class="row">
			
			<div class="col s8">

				 <table class=" highlight responsive-table" style="width:120%; font-size:14px;" >

				 	<thead>  <!-- Column headings-->
 				 		<tr>
				 			<th> <i class="small material-icons">photo</i> </th>
				 			<th> Tag No. </th>
				 			<th> Serial No.  </th>
				 			<th> Type  </th>
				 			<th > Category  </th>
				 			
				 		</tr>
				 	</thead>

				 	<tbody>	<!-- Table body containing records of personnel-->
				 		
				 		@if(empty($query_results))

				 		@else

					 		@foreach($query_results as $list)
					 		<tr>
					 			<td>

									@if(!empty($list->photo_name))
										<img class="" src="{{ asset(Storage::url('hardware_photo/'.$list->category.'/'.$list->photo_name)) }}" width="50px" height="50px">

										
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
					 			<td>{{ $list->category }}</td>
					 			
					 			
					 		</tr>
	                       
					 		@endforeach
				 		@endif

				 	</tbody>

				 </table>	
			</div>
			

		</div>
		<div class="row">
			
			<!---->
			@if($pagination_number != 0 && $action !='filter')
				<div>
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

		<Form action="{{ route('hardware_equipment.filterview') }}" method="GET">
			
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
			            <button class=" btn waves-effect waves-light btn-small " type="submit" name="action" style="background-color: #c62828;">Filter<i class="material-icons right">search</i>
				        </button> 
				         <a href="{{ route('hardware_equipment.view') }}" class="modal-close waves-effect waves-green btn-small"><i class="material-icons">refresh</i></a>
			</div>
		</Form>
</div>


@endsection

@section('jquery-section')

		<script type="text/javascript">
				

				$.ajaxSetup({
		     		 headers: {
		        		'X-CSRF-TOKEN' : $('meta[name="csrf_token"').attr('content')
		      		}
		     	});

			    
		     	
		</script>

@endsection
	