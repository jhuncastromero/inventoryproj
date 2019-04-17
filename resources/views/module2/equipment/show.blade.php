@extends('layouts.master-page-layout')

@section('title-section')

  Equipment Details

@endsection

@section('navlinks')
      
      @include('module2.equipment.equipment-nav-links')  

@endsection
      
@section('sidenavlinks')

   @include('module2.equipment.equipment-sidenav-links')

@endsection

@section('content-section')
		
		<div class="container">
			 
			 <div class="row" style="padding-bottom: 20px;">
					<div id="div_header"><i class="material-icons">laptop_mac</i>&nbsp;Equipment Details</div>	
			 </div>

			 @if(!empty($query_result))

			 	<div class="row">
					<div>
						@if(!empty($query_result[0]->photo_name))
							<img class="" src="{{ asset(Storage::url('hardware_photo/'.$query_result[0]->category.'/'.$query_result[0]->photo_name)) }}" width="175px" height="175">
						@else
							<div><i class="large material-icons">photo</i></div>
							<div style="font-style: italic; font-size:14px; padding-left: 10px;">(no photo)</div>
						@endif
					</div>
				</div>
			 @else
			 	<div><i> Whoops! Something went wrong. Please try again.</i> </div>
			 @endif
			
			 <div class="row" style="padding-top:20px;">

				    <table class="responsive-table" style="width: 50%; font-size:14px;">
						
				    	<tr>
				    		<td style="font-weight: bold;">Serial No.</td>
				    		@if(!empty($query_result[0]->serial_no))
				    			<td> {{ $query_result[0]->serial_no }}</td>
				    		@else
				    			<td> ------ </td>
				    		@endif
				    	</tr>

				    	<tr>
				    		<td style="font-weight: bold;">Equipment Tag No.</td>
				    		@if(!empty($query_result[0]->tag_no))
				    			<td> {{ $query_result[0]->tag_no }}</td>
				    		@else
				    			<td> ------ </td>
				    		@endif
				    	</tr>

				    	<tr>
				    		<td style="font-weight: bold;">MAC Address/ Code</td>
				    		@if(!empty($query_result[0]->mac_address))
				    			<td> {{ $query_result[0]->mac_address }}</td>
				    		@else
				    			<td> ------ </td>
				    		@endif
				    	</tr>

				    	<tr>
				    		<td style="font-weight: bold;">Category</td>
				    		@if(!empty($query_result[0]->category))
				    			<td> {{ $query_result[0]->category }}</td>
				    		@else
				    			<td> ------ </td>
				    		@endif
				    	</tr>

				    	<tr>
				    		<td style="font-weight: bold;">Type</td>
				    		@if(!empty($query_result[0]->type))
				    			<td> {{ $query_result[0]->type }}</td>
				    		@else
				    			<td> ------ </td>
				    		@endif
				    	</tr>

				    	<tr>
				    		<td style="font-weight: bold;">Description</td>
				    		@if(!empty($query_result[0]->description))
				    			<td> {{ $query_result[0]->description }}</td>
				    		@else
				    			<td> ------ </td>
				    		@endif
				    	</tr>

				    	<tr>
				    		<td style="font-weight: bold;">Origin</td>
				    		@if(!empty($query_result[0]->origin))
				    			<td> {{ $query_result[0]->origin }}</td>
				    		@else
				    			<td> ------ </td>
				    		@endif
				    	</tr>

				    	<tr>
				    		<td style="font-weight: bold;">Status</td>
				    		@if(!empty($query_result[0]->status))
				    			<td> {{ $query_result[0]->status }}</td>
				    		@else
				    			<td> ------ </td>
				    		@endif
				    	</tr>

				    	<tr>
				    		<td style="font-weight: bold;">Date Acquired</td>
				    		@if(!empty($query_result[0]->date_acquired))
				    			<td> {{ $query_result[0]->date_acquired }}</td>
				    		@else
				    			<td> ------ </td>
				    		@endif
				    	</tr>

				    	<tr>
				    		<td style="font-weight: bold;">QR Code</td>
				    		@if(!empty($query_result[0]->QRCode_name))
				    			<td> <img class="" src="{{ asset(Storage::url('qrcode/'.$query_result[0]->QRCode_name)) }}" width="75px" height="75"></td>
				    		@else
				    			<td> ------ </td>
				    		@endif
				    	</tr>


				 	</table>

			 </div>

		</div>

@endsection