@extends('layouts.master-page-layout')

@section('title-section')

	Personnel : Profile Details

@endsection

@section('navlinks')
      
      @include('module1.personnel.personnel-nav-links')  

@endsection
      
@section('sidenavlinks')

   @include('module1.personnel.personnel-sidenav-links')

@endsection

@section('content-section')
	
<div class="container">
		 <div class="row">
				<div id="div_header"><i class="material-icons">person</i>&nbsp;Personnel Profile Details</div>	
		 </div>

		@if(!empty($query_personnels[0]))


				<div class="row">
					<div>
						@if(!empty($query_personnels[0]->photo_name))
							<img class="" src="{{ asset(Storage::url('personnel_photo/'.$query_personnels[0]->emp_id.'/'.$query_personnels[0]->photo_name))}}" width="175px" height="175px">
						@else
							<i class="large material-icons">person</i>
						@endif
					</div>
				</div>

				 <div class="row">

					  <div class="input-field col s6">
			            <input type="text" readonly value="{{ $query_personnels[0]->emp_id }}" class="validate">
			            <label for="emp_id">Employee ID#</label>
			          </div>

			          <div class="input-field col s6" >
			            <input type="text" readonly value="{{ $query_personnels[0]->last_name }}" class="validate">
			            <label for="last_name">Last Name</label>
			          </div>

				 </div>       

			     <div class="row">

			          <div class="input-field col s6">
			            <input type="text" readonly value="{{ $query_personnels[0]->first_name }}" class="validate">
			            <label for="first_name">First Name</label>
			          </div>

			          <div class="input-field col s6">
			            <input type="text" readonly value="{{ $query_personnels[0]->middle_initial }}"class="validate">
			            <label for="middle_initial">Middle Initial</label>
			          </div>

			     </div>

		         <div class="row">

				          <div class="input-field col s6">
				             <input type="text" readonly value="{{ $query_personnels[0]->deptcode }} - {{ $deptname }}" class="validate">
				            <label for="department">Department</label>
				          </div>

				          <div class="input-field col s6">
				            <input type="text" readonly value="{{ $query_personnels[0]->job_position }}" class="validate">
				            <label for="job_position">Designation</label>
				          </div>
				 </div>

				 <div class="row">

				          <div class="input-field col s6">
				            <input type="text" readonly value="{{ $query_personnels[0]->email_add }}" class="validate">
				            <label for="email_add">Email</label>
				          </div>
				  
				  </div>

				  <div>
				        	<span style="padding-left:20px; text-align: center;"><a href="{{ route('personnel.view') }}"><i class="material-icons">list</i>&nbsp;Back to List</a></span>
				  </div>
		@else
			<div><i> Whoops! Something went wrong. Please try again.</i> </div>
		@endif
</div>
	
@endsection

