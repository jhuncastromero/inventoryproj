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
				
				  <!--nothing to display-->
				@else

					@foreach($query_personnels as $query_personnel)
						<div class="col s10 m5">
							<div class="card horizontal card-medium">
								<div class="card-image">
									
										@if(!empty($query_personnel->photo_name))
											<img class="" src="{{ asset(Storage::url('personnel_photo/'.$query_personnel->emp_id.'/'.$query_personnel->photo_name))}}" width="75px" height="75px">
										@else
											<i class="medium material-icons">person</i>
										@endif
								</div>
							    <div>
									<div class="card-stacked">
										<div class="card-content">
											<p>{{ $query_personnel->job_position }}</p>
										</div>
									</div>
									<div class="card-action">
										<a href="{{ route('personnel.showdetails',['id' => $query_personnel->id]) }}">{{ $query_personnel->first_name}} {{ $query_personnel->middle_initial}}. {{ $query_personnel->last_name }} </a>
									</div>
								</div>
							</div>
						</div>
					@endforeach
				@endif
			

		</div>
  
		<div class="row">
			
		</div>

  </div>

@endsection

@section('js-script-section')


@endsection

