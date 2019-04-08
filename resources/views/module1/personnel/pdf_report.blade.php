@extends('layouts.master-page-layout')

@section('title-section')
	Personnel Profile : Generate Report
@endsection

@section('content-section')

	<div class="container">

		<div class="row">  <!--header row-->
			<div id="div_header"><i class="material-icons">print</i>&nbsp;Generate Personnel Report Summary </div>
		</div>
		<div class= "row">

			<a href="{{ route('personnel.pdf') }}" target="_blank" class="btn btn-danger"> Convert to PDF </a>

		</div>
		<div class = "row">
			<table class="striped">

				 	<thead>  <!-- Column headings-->
 				 		<tr>
				 			<th> Employee ID  </th>
				 			<th> Name </th>
				 			<th> Department </th>
				 			<th> Designation </th>
				 			<th> Email </th>
				 			<th> Photo </th>
				 		</tr>
				 	</thead>

				 	<tbody>	<!-- Table body containing records of personnel-->
				 		@foreach($get_personnel_lists as $list)
				 		<tr>
				 			<td>{{ $list->emp_id }}</a></td>
				 			<td>{{ $list->last_name }}, {{ $list->first_name }} {{ $list->middle_initial }}.  </td>
				 			<td>{{ $list->deptname }}</a></td>
				 			<td>{{ $list->job_position }}</a></td>
				 			<td>{{ $list->email_add }}</a></td>
				 			<td>

								@if(!empty($list->photo_name))
									<img class="" src="{{ asset(Storage::url('personnel_photo/'.$list->emp_id.'/'.$list->photo_name))}}" width="50px" height="50px">
								@else
									<i class="small material-icons">person</i>
								@endif
				 			</td>
				 		</tr>
                       
				 		@endforeach
				 		

				 	</tbody>

				 </table>
		</div>
		<div class="row">
						
					<ul class="pagination">
		   		         <li class="waves-effect"><a href="{{$get_personnel_lists->previousPageUrl()}}"><i class="material-icons">chevron_left</i></a></li>

		   		         	@if(($get_personnel_lists->total()%10) > 0)
		   		         		@for($i=1; $i<=($get_personnel_lists->total()/10)+1; $i++)
		   		         			 <li class="waves-effect circle"><a href="{{$get_personnel_lists->url($i)}}">{{$i}}</a></li>
		   		         		@endfor
		   		         	@else
		   		         		@for($i=1; $i<=($get_personnel_lists->total()/10); $i++)
		   		         			 <li class="waves-effect"><a href="{{$get_personnel_lists->url($i)}}">{{$i}}</a></li>
		   		         		@endfor
		   		         	@endif

		   		         <li class="waves-effect"><a href="{{$get_personnel_lists->nextPageUrl()}}"><i class="material-icons">chevron_right</i></a></li>
		   		    </ul>     
		
   		    </div>
	</div>
@endsection