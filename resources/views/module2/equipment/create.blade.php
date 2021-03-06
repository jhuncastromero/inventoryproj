@extends('layouts.master-page-layout')

@section('title-section')

  Equipment : Add New Equipment

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
    <div id="div_header"><i class="material-icons">keyboard_hide</i>&nbsp;Add New Equipment</div> 
  </div>
  <div class="row">
    
    <FORM ACTION=" {{ route('hardware_equipment.store') }}" METHOD="POST" enctype="multipart/form-data">
      {{ csrf_field() }}
            
            <div class="row">
               <div class="input-field col s6">
                   <input type="text" id="tag_no" name="tag_no" class="" maxlength="30" >
                   <label for="tag_no">Equipment Tag</label>
                </div>
                <div class="input-field col s6" >
                    <input type="text" id="serial_no" name="serial_no" class="" maxlength="50">
                    <label for="serial_no">Serial No.</label>
                </div>
            </div>

            <div class="row">
              
              <div class="input-field col s6">
                <select id="category" name="category" class="validate" required="true" >
                  <option value="" selected>Choose Category</option>
                  <option value="IT">IT</option>
                  <option value="Non-IT">Non-IT</option>
                </select>
              </div>

              <div class="input-field col s6">
                <input type="text" id="type" name="type" class="validate" required="" maxlength="50">
                <label for="type">Equipment Type</label>
              </div>

            </div>
            
            <div class="row">
              
               <div class="input-field col s6">
                  <input type="text" id="brand" name="brand" maxlength="30">
                  <label for="brand">Brand/ Make</label>
              </div>             

              <div class="input-field col s6">
                <textarea id="description" name="description" maxlength="100" class="materialize-textarea" required="" validate=""></textarea>
                <label for="description">Description</label>
              </div>

            </div>

     		   <div class="row">

              <div class="input-field col s6">
                <input type="text" id=mac_address name="mac_address" class="validate" maxlength="60">
                <label for="mac_address">MAC Address/ Code</label>
              </div>

              <div class="input-field col s6">
                <input type="text" id="origin" name="origin" class="validate" maxlength="5">
                <label for="origin">Origin</label>
              </div>

            </div>

            <div class="row">

              <div class="input-field col s6">
                  <input type="text" id="status" name="status" maxlength="20">
                  <label for="status">Status</label>
              </div>
            
              <div class="input-field col s6">
                  <input type="text" id="date_acquired" name="date_acquired" class="datepicker" required="">
                  <label for="date_acquired">Date Acquired</label>
              </div>  


            </div>

            <div class="row">
              <div class="col s6" style="padding-bottom: 20px;">          
                    <div class="file-field input-field">
                        <div class="btn-small">
                            <span>Photo</span>
                            <input type="file" name="photofile" id="photofile">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text">
                        </div>
                    </div>
                </div>
                <div class="col s4" style="padding-bottom: 20px;">  
                      <div id="div_qr" name="div_qr" style=""></div>
                </div>
             </div>
             <div class="row">   
               
               <div class="col s6" style="padding-bottom: 10px;">          
                

               </div>
            </div>

            <button class=" btn waves-effect waves-light" type="submit" name="action" style="background-color: #c62828;">Create<i class="material-icons right">send</i>
            </button> <span style="padding-left:20px; text-align: center;"><a href="">Cancel</a></span>
            
     
     </FORM>
         
    </div>

    <div class="row">

        <input type="hidden" name="hiddentext" id="hiddentext" value=" {{ $createvalue }} ">

    </div>

</div>

 <!--Modal Structure -->
  <div id="messageprompt" class="modal">
    <div class="modal-content">
      <h4> <i class="medium material-icons" style="color:red;">{{ $error_icon }}</i>&nbsp; {{ $error_title }}</h4>
      <p>{{ $error_message }}</p>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-green btn-flat">OK</a>
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
    
    $(document).ready(function() {

       var xValue;
       xValue = $('#hiddentext').val();

       if(xValue==1)
       {
          $('#messageprompt').modal('open');
          $('#hiddentext').val(''); 
       }
       else if(xValue==2)
       {
          M.toast({html:'New Profile was successfully added!'});
          $('#hiddentext').val(''); 
       }

      
    });

    $('#category').change(function() {

        if($('#category').val() =='IT'){
          $('#mac_address').removeAttr('disabled');
        }
        else if($('#category').val()=='Non-IT') {
            $('#mac_address').attr('disabled','disabled');
        }
        generate_qr_code();
    });  


      // AJAX
    function generate_qr_code() {

      var category = $('#category').val();
      var tag_no = $('#tag_no').val();
      var serial_no = $('#serial_no').val();
      var dataString;

      dataString = 'serial_no=' + serial_no + '&tag_no=' + tag_no + '&category=' + category;
    
      $.ajax({
            type: "POST",
            url: "/hardware_equipment/preview_qrcode",
            data : dataString,
            success: function(data){
              
              $('#div_qr').html(data);
            }

        })
 
     
    }

  </script>

@endsection
