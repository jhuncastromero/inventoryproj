@extends('layouts.master-page-layout')

@section('title-section')

	Equipment Database

@endsection

@section('navlinks')
      
      @include('module2.equipment.equipment-nav-links')  

@endsection
      
@section('sidenavlinks')

   @include('module2.equipment.equipment-sidenav-links')

@endsection

@section('content-section')
	<div class="container">

		<div class="row">  <!--header row-->
			<div id="div_header"><i class="material-icons">group</i>&nbsp;Equipment Database </div>
		</div>
        

        <div class="row">  <!--sub header-->
			<div id=""><i class="material-icons">search</i>&nbsp;Find Equipment </div>
		</div>
	
		<div class="row">  <!--find row-->
			<div class="col s10">
			<Form action="" method="POST">
					{{ csrf_field() }}
				  <div class="row">

				  <div class="input-field col s6">
		                 <input type="text" id="search_equipmentid" name="search_equipmentid">  
		                <label for="search_equipmentid">Search Equipment Serial No. </label>
	              </div>
                   
	              <div class="input-field col s6">
		                 <input type="text" id="search_equipmenttype" name="search_equiptmenttype">  
		                <label for="search_lastname">Search Equipment Type </label>
	              </div>
	          </div>
	              <div class="col s4" style="padding-bottom: 50px;">
	              	  	<button class=" btn waves-effect waves-light" type="submit" name="action" style="background-color: #c62828;">Find<i class="material-icons right">search</i>
		            	</button> 

				</div>
			</Form>
		</div>

		<div class="row">  <!--cards preview row--> 
					

		</div>
  
		<div class="row">
			
		</div>

  </div>

@endsection

@section('js-script-section')


@endsection

