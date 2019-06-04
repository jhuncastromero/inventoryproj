
<ul id="slide-out" class="sidenav sidenav-fixed">
  <li><div class="user-view" id="sidenav_motif_style">
      
        <div style="color:#ffffff;font-size:16px; text-align: center">
          <div><i class="medium material-icons">laptop_mac</i></div>IT Equipment Deployment
        </div>
      
    </div>
  </li>
  <li> <a href="{{ route('deployment_it.deployequipment') }}"><i class="material-icons">transform</i>Assign/Deploy</a></li>
  <li><a href="#"><i class="material-icons">update</i>Update Deployment</a></li>
  <li><a href="{{ route('deployment_it.viewdeployment')}}"><i class="material-icons">find_in_page</i>View Deployment</a></li>
  <li><a href="#" data-activates="dropdown2" class="dropdown-button"><i class="material-icons">print</i>Report</a></li>
  <ul class="collapsible collapsible-accordion">
    <li><a class="collapsible-header" href="#">sample drop</a>
      <div class="collapsible-body">
          <ul>
            <li><a href="#!"> first </a></li>
            <li><a href="#!"> second </a></li>
            <li><a href="#!"> third </a></li>
          </ul>
      </div>

    </li>
  </ul>
  <li><div class="divider"></div></li>

  <li> <a href="#"><i class="material-icons">exit_to_app</i>Log-out</a></li>
</ul>

<a href="#" data-target="slide-out" class="sidenav-trigger"><i class="medium material-icons">menu</i></a>





//------------------




<ul id="slide-out" class="sidenav sidenav-fixed">
  <li><div class="user-view" id="sidenav_motif_style">
      
        <div style="color:#ffffff;font-size:16px; text-align: center">
          <div><i class="medium material-icons">laptop_mac</i></div>IT Equipment Deployment
        </div>
      
    </div>
  </li>
  <li> <a href="{{ route('deployment_it.deployequipment') }}"><i class="material-icons">transform</i>Assign/Deploy</a></li>
  <li><a href="#"><i class="material-icons">update</i>Update Deployment</a></li>
  <li><a class="dropdown-trigger"  data-target="dropdown1" href="{{ route('deployment_it.viewdeployment')}}"><i class="material-icons">find_in_page</i>View Deployment<i class="material-icons right">arrow_drop_down</i></a></li>
  <li><a href="#" data-activates="dropdown2" class="dropdown-button"><i class="material-icons">print</i>Report</a></li>

  <ul id="dropdown1" class="dropdown-content">
      <li><a href="{{ route('deployment_it.viewpersonneldeployment')}}"><i class="material-icons">person</i>By Personnel</a></li>
      <li><a href="#!"><i class="material-icons">scanner</i>By Equipment</a></li>
  </ul>


  <ul class="collapsible" style="padding-left: 15px; font-size:14px; ">
    <li>
      <div class="collapsible-header"><a href="#!" style="color:#616161;"><i class="material-icons" style="color:#616161;">find_in_page</i>&nbsp;&nbsp;&nbsp;View Deployment</a></div>
      
        <div class="collapsible-body">
          <ul>
            <li><a href="{{ route('deployment_it.viewpersonneldeployment')}}"><i class="material-icons">person</i>By Personnel</a></li>
          <li><a href="#!"><i class="material-icons">scanner</i>By Equipment</a></li>
      </ul>

        </div>
      
    </li>
    
  </ul>
  
  <li><div class="divider"></div></li>

  <li> <a href="#"><i class="material-icons">exit_to_app</i>Log-out</a></li>
</ul>

<a href="#" data-target="slide-out" class="sidenav-trigger"><i class="medium material-icons">menu</i></a>
