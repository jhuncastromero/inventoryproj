
<ul id="slide-out" class="sidenav sidenav-fixed">
	<li><div class="user-view" id="sidenav_motif_style">
			
			  <div style="color:#ffffff;font-size:16px; text-align: center">
			  	<div><i class="medium material-icons">laptop_mac</i></div>IT Equipment Deployment
			  </div>
			
		</div>
	</li>
	<li> <a href="{{ route('deployment_it.deployequipment') }}"><i class="material-icons">transform</i>Assign/Deploy</a></li>
	<ul class="collapsible " style="padding-left: 15px; font-size:14px; ">
	    <li>
	      <div class="collapsible-header" style="color:#616161"><i class="material-icons" style="color:#616161;">update</i>&nbsp;&nbsp;&nbsp;Update Deployment</div>
	      
		      <div class="collapsible-body">
		      	<ul style="padding-left: 10px;">
		      		<li><a href="{{ route('deployment_it.reassignequipment') }}"><i class="material-icons">transfer_within_a_station</i>Re-Assign Equipment</a></li>
		    		<li><a href="{{route('deployment_it.recallequipment')}}"><i class="material-icons">rotate_left</i>Recall Equipment</a></li>
				</ul>

		      </div>
	  		
	    </li>
  	</ul>

	<ul class="collapsible " style="padding-left: 15px; font-size:14px; ">
	    <li>
	      <div class="collapsible-header" style="color:#616161"><i class="material-icons" style="color:#616161;">find_in_page</i>&nbsp;&nbsp;&nbsp;View Deployment</div>
	      
		      <div class="collapsible-body">
		      	<ul style="padding-left: 10px;">
		      		<li><a href="{{ route('deployment_it.viewpersonneldeployment')}}"><i class="material-icons">person</i>By Personnel</a></li>
		    		<li><a href="{{ route('deployment_it.viewequipmentdeployment')}}"><i class="material-icons">scanner</i>By Equipment</a></li>
				</ul>

		      </div>
	  		
	    </li>
  	</ul>
  
	
	<ul class="collapsible " style="padding-left: 15px; font-size:14px; ">
	    <li>
	      <div class="collapsible-header" style="color:#616161"><i class="material-icons" style="color:#616161;">print</i>&nbsp;&nbsp;&nbsp;Report</div>
	      
		      <div class="collapsible-body">
		      	<ul style="padding-left: 10px;">
		    
		      		<FORM ACTION="{{ route('deployment_it.reportallitdeploymentpersonnel') }}" METHOD="GET" target="_blank" id="rptForm1">
		      				<li><a href="" onclick="rptForm1.submit();"><i class="material-icons">chevron_right</i>All By Personnel </a></li>
		      	   </FORM>


		      		<FORM ACTION="{{ route('deployment_it.reportallitdeploymenthardware') }}" METHOD="GET" target="_blank" id="rptForm2">
		    				<li><a href="#!"  onclick="rptForm2.submit();"><i class="material-icons">chevron_right</i>All By Equipment</a></li>
		    		</FORM>
		    
				</ul>

		      </div>
	  		
	    </li>
  	</ul>

	<li><div class="divider"></div></li>

	<li> <a href="#"><i class="material-icons">exit_to_app</i>Log-out</a></li>
</ul>

<a href="#" data-target="slide-out" class="sidenav-trigger"><i class="medium material-icons">menu</i></a>

