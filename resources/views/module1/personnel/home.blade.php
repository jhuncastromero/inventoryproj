@extends('layouts.master-page-layout')

@section('title-section')

	Personnel Profile

@endsection


@section('navlinks')
      
      @include('module1.personnel.personnel-nav-links')  

@endsection
      
@section('sidenavlinks')

   @include('module1.personnel.personnel-sidenav-links')

@endsection

@section('content-section')
	<div class="container">

		<div class="row">  <!--header row-->
			<div id="div_header"><i class="material-icons">group</i>&nbsp;Personnel Profile </div>
		</div>
        

        <div class="row">  <!--sub header-->
			<div id=""><i class="material-icons">search</i>&nbsp;Find a Profile </div>
		</div>
	
		<div class="row">  <!--find row-->
			<div class="col s10">
			<Form action=" {{ route('personnel.searchpersonnel') }}" method="POST">
					{{ csrf_field() }}
				  <div class="row">

				  <div class="input-field col s6">
		                 <input type="text" id="search_empid" name="search_empid">  
		                <label for="search_empid">Search Employee ID </label>
	              </div>
                   
	              <div class="input-field col s6">
		                 <input type="text" id="search_lastname" name="search_lastname">  
		                <label for="search_lastname">Search by Last Name </label>
	              </div>
	          </div>
	              <div class="col s4" style="padding-bottom: 50px;">
	              	  	<button class=" btn waves-effect waves-light" type="submit" name="action" style="background-color: #c62828;">Find<i class="material-icons right">search</i>
		            	</button> 

				</div>
			</Form>
		</div>

		<div class="row">  <!--cards preview row--> 
		
			@if(empty($query_personnels)) 

			@else

				<table class="striped responsive-table" style="width:80%; font-size:14px">

				 	<thead>  <!-- Column headings-->
 				 		<tr>
				 			<th> <i class="small material-icons">photo_camera</i> </th>
				 			<th> Name </th>
				 			<th> Designation  </th>
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
				 			<td><a href="{{ route('personnel.show',$query_personnel->id) }}">{{ $query_personnel->last_name }}, {{ $query_personnel->first_name }} {{ $query_personnel->middle_initial }}.</a> </td>
				 			<td>{{ $query_personnel->job_position }}</a></td>
				 			
				 			
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

@section('js-script-section')


@endsection
