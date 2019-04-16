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
			<div id="div_header"><i class="material-icons">view_list</i>&nbsp;List View Equipment Database</div>
		</div>

		<div class="row">
			
			<div class="col s8">

				 <table class="responsive-table" style="width:90%;">

				 	<thead>  <!-- Column headings-->
 				 		<tr>
				 			<th> <i class="small material-icons">picture_in_picture</i> </th>
				 			<th> Tag No. </th>
				 			<th> Serial No.  </th>
				 			<th> Type  </th>
				 			<th> Category  </th>
				 			<th> Details  </th>
				 		</tr>
				 	</thead>

				 	<tbody>	<!-- Table body containing records of personnel-->
				 		@foreach($query_results as $list)
				 		<tr>
				 			<td>

								@if(!empty($list->photo_name))
									<img class="" src="{{ asset(Storage::url('hardware_photo/'.$list->category.'/'.$list->photo_name)) }}" width="50px" height="50px">

									
								@else
									<i class="small material-icons">photo</i>
								@endif
				 			</td>
				 			<td>{{ $list->tag_no }}</td>
				 			<td>{{ $list->serial_no }}</td>
				 			<td>{{ $list->type }}</td>
				 			<td>{{ $list->category }}</td>
				 			<td><a href="#!"><i class="small material-icons">chevron_right</i></a></td>
				 			
				 			
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
	