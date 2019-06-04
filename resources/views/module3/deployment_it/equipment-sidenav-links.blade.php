
<ul id="slide-out" class="sidenav sidenav-fixed">
	<li><div class="user-view" id="sidenav_motif_style">
			
			  <div style="color:#ffffff;font-size:16px; text-align: center">
			  	<div><i class="medium material-icons">laptop_mac</i></div>IT Equipment Deployment
			  </div>
			
		</div>
	</li>
	<li> <a href="{{ route('deployment_it.deployequipment') }}"><i class="material-icons">transform</i>Assign/Deploy</a></li>
	<li><a href="#"><i class="material-icons">update</i>Update Deployment</a></li>
	<ul class="collapsible " style="padding-left: 15px; font-size:14px; ">
	    <li>
	      <div class="collapsible-header" style="color:#616161"><i class="material-icons" style="color:#616161;">find_in_page</i>&nbsp;&nbsp;&nbsp;View Deployment</div>
	      
		      <div class="collapsible-body">
		      	<ul>
		      		<li><a href="{{ route('deployment_it.viewpersonneldeployment')}}"><i class="material-icons">person</i>By Personnel</a></li>
		    		<li><a href="{{ route('deployment_it.viewequipmentdeployment')}}"><i class="material-icons">scanner</i>By Equipment</a></li>
				</ul>

		      </div>
	  		
	    </li>
  	</ul>
  
	<li><a href="#" data-activates="dropdown2" class="dropdown-button"><i class="material-icons">print</i>Report</a></li>

	<li><div class="divider"></div></li>

	<li> <a href="#"><i class="material-icons">exit_to_app</i>Log-out</a></li>
</ul>

<a href="#" data-target="slide-out" class="sidenav-trigger"><i class="medium material-icons">menu</i></a>
