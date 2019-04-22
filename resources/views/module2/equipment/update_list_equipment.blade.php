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
			<div id="div_header"><i class="material-icons">update</i>&nbsp;Update Equipment Database</div>
		</div>

		<div class="row">
			
			<div class="col s8">

				 <table class="responsive-table" style="width:120%; font-size:14px;" >

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
				 			<td><a class="btn btn-medium btn-flat" ><i style="font-size:18px;"class="small material-icons">delete</i></a></td>
				 			
				 		</tr>
                       
				 		@endforeach
				 		

				 	</tbody>

				 </table>	
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

			    
		     	
		</script>

@endsection
	