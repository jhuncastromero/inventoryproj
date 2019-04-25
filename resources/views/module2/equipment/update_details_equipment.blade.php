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
    <div id="div_header"><i class="material-icons">update</i>&nbsp;Update Equipment Information</div> 
  </div>
  <div class="row">
    
    <FORM ACTION="{{ route('hardware_equipment.update',$query_results[0]->id)}}" METHOD="POST" enctype="multipart/form-data">
      {{ csrf_field() }}
      {{ method_field('PUT') }}            
            

      		<div class="row" >
      			<div class="col s3">
		      			@if(!empty($query_results[0]->photo_name))
							<img class="" src="{{ asset(Storage::url('hardware_photo/'.$query_results[0]->category.'/'.$query_results[0]->photo_name))}}" width="200px" height="200px">
						@else
							<i class="large material-icons">photo</i>
						@endif
				</div>
				<div class="col s9">
	
					<div class="row">
			 
						 	<div class="col s10">
							    <div>			 
								 	<p>
								 		<label>
								 				<input name="no_photo"  id="no_photo" type="radio"/>
								 				<span>Remove Photo</span>
								 		</label>
								 	</p>
							 	</div>

							 	<div>
								 	<p>
								 		<label>
								 				<input name="new_photo" id="new_photo" type="radio"/>
								 				<span>Upload New Photo</span>
								 		</label>
								 	</p>
								 		<div class="col s6" style="padding-bottom: 10px;" id="browse_photo" name="browse_photo">          
						            		<div class="file-field input-field">
							            		<div class="btn-small">
							            			<span>Photo</span>
							            			<input type="file" name="photofile" id="photofile">
							            		</div>
							            		<div class="file-path-wrapper" id="filepath" name="filepath">
							            			<input class="file-path validate" type="text" id="filePath">
							            		</div>
						            		</div>
						           		</div>
								</div>
							</div>	
					</div>
			</div>
      		
            <div class="row" >
               <div class="input-field col s6" >
                   <input type="text" id="tag_no" name="tag_no" class="" maxlength="30" value="{{ $query_results[0]->tag_no  }}" >
                   <label for="tag_no">Equipment Tag</label >
                </div>
                <div class="input-field col s6" >
                    <input type="text" id="serial_no" name="serial_no" class="" maxlength="50" value="{{ $query_results[0]->serial_no  }}">
                    <label for="serial_no">Serial No.</label>
                </div>
            </div>

            <div class="row">
              
              <div class="input-field col s6">
                <select id="category" name="category" class="validate" required="true" >
                  <option value="{{ $query_results[0]->category  }}" selected>{{ $query_results[0]->category }}</option>
                  <option value="IT">IT</option>
                  <option value="Non-IT">Non-IT</option>
                </select>
                <label for="category">Category</label>

              </div>

              <div class="input-field col s6">
                <input type="text" id="type" name="type" class="validate" required="" maxlength="50" value="{{ $query_results[0]->type  }}">
                <label for="type">Equipment Type</label>
              </div>

            </div>
            
            <div class="row">
              
               <div class="input-field col s6">
                  <input type="text" id="brand" name="brand" maxlength="30" value="{{ $query_results[0]->brand  }}" >
                  <label for="brand">Brand/ Make</label>
              </div>             

              <div class="input-field col s6">
                <textarea id="description" name="description" maxlength="100" class="materialize-textarea" required="" validate="" >{{ $query_results[0]->description }}</textarea>
                <label for="description">Description</label>
              </div>

            </div>

     		   <div class="row">

              <div class="input-field col s6">
                <input type="text" id=mac_address name="mac_address" class="validate" maxlength="60" value="{{ $query_results[0]->mac_address  }}"> 
                <label for="mac_address">MAC Address/ Code</label>
              </div>

              <div class="input-field col s6">
                <input type="text" id="origin" name="origin" class="validate" maxlength="5" value="{{ $query_results[0]->origin }}">
                <label for="origin">Origin</label>
              </div>

            </div>

            <div class="row">

                <div class="input-field col s6">
                    <input type="text" id="status" name="status" maxlength="20" value="{{ $query_results[0]->status}}">
                    <label for="status">Status</label>
                </div>
              
                <div class="input-field col s6">
                    <input type="text" id="date_acquired" name="date_acquired" class="datepicker" required="" value="{{ $query_results[0]->date_acquired  }}">
                    <label for="date_acquired">Date Acquired</label>
                </div>  


            </div>

            <div class = "row" style="padding-bottom: 40px;">
                  <div class ="col s4">
                        <div id="qr_div">
                            @if(!empty($query_results[0]->QRCode_name))
                                 <img class="" src="{{ asset(Storage::url('qrcode/'.$query_results[0]->QRCode_name)) }}" width="100px" height="100">
                             @else
                                  <p><i> ( QR Code Not Generated ) </i></p>
                            @endif
                        </div>
                        <div>
                            <a class="" href="#" id="{{ $query_results[0]->id}}" name="update_qr">Update QR Code</a>

                        </div>
                    </div>
              

            </div>

           
            <button class=" btn waves-effect waves-light" type="submit" name="action" style="background-color: #c62828;">Update<i class="material-icons right">update</i>
            </button> <span style="padding-left:20px; text-align: center;"><a href="">Cancel</a></span>
            
     

         
    </div>

    <div class="row">

     	   <div><input type="hidden" id="hiddentext" name="hiddentext" value="{{ $photo_status }}"></div>
         <div><input type="hidden" id="qr" name="qr" value ="{{ $query_results[0]->id}}"
	      @if(session()->has('updatevalue'))
		  	<div><input type="hidden" id="hidden_updatevalue" name="hidden_updatevalue" value="{{ 	session()->get('updatevalue') }}"></div>
		  @else
		  	<div><input type="hidden" id="hidden_updatevalue" name="hidden_updatevalue" value="{{ $updatevalue }}"></div>
		  @endif

    </div>
 </FORM>

</div>
 <!-- Modal Structure -->
  <div id="messageprompt" class="modal">
    <div class="modal-content">
      <h4> <i class="medium material-icons" style="color:red;">{{ $error_icon }}</i>&nbsp;{{ $error_title }}</h4>
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

    	 $("#no_photo").click(function(){
			
        	$("#new_photo").prop("checked", false);
  				$("#browse_photo").prop("enabled",false);
  				$("#browse_photo").css('visibility','hidden');
  				$("#hiddentext").val("no_photo");
			 
       });		

			 $("#new_photo").click(function(){
  				$("#no_photo").prop("checked", false);
  				$("#browse_photo").prop("enabled",true);
  				$("#browse_photo").css('visibility','visible');
  				$("#hiddentext").val("new_photo");
			 });

		   var xValue;
	      xValue = 0;
        xValue = $('#hidden_updatevalue').val();

        if(xValue=='1')
        {
           $('#messageprompt').modal('open');
           $('#hidden_updatevalue').val(''); 
           $('#hiddentext').val('');
        }
        else if(xValue=='2')
        {
           
        	 $('#hidden_updatevalue').val('');
           $('#hiddentext').val('');
           M.toast({html:"Hardware Equipment update(s) was successfully made!"});

        }
   
      
    });

    $('#category').change(function() {
        
       var xID = $("#qr").val();
        var dataString = "qr=" + $("#qr").val() + "&category=" + $("#category").val() + "&tag_no=" + $("#tag_no").val() + "&serial_no=" + $("#serial_no").val();

        if($('#category').val() =='IT'){
          $('#mac_address').removeAttr('disabled');
        }
        else if($('#category').val()=='Non-IT') {
            $('#mac_address').val('');
            $('#mac_address').attr('disabled','disabled');

        }
        

        $.ajax({
            type: "POST",
            url: "/hardware_equipment/updateqrcode",
            data : dataString,
            success: function(data){
              $("#qr_div").html('');
              $("#qr_div").html(data);
            }

        })
    });  


    $("[name='update_qr']").click(function(){

        var xID = $("#qr").val();
        var dataString = "qr=" + $("#qr").val() + "&category=" + $("#category").val() + "&tag_no=" + $("#tag_no").val() + "&serial_no=" + $("#serial_no").val();
      
      $.ajax({
            type: "POST",
            url: "/hardware_equipment/updateqrcode",
            data : dataString,
            success: function(data){
              $("#qr_div").html('');
              $("#qr_div").html(data);
            }

        })
       
        
    });
           
      


    
 
     
   

  </script>

@endsection
