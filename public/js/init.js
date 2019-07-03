
 $(document).ready(function(){
    $('.sidenav').sidenav();
    $('select').formSelect();
    $('.modal').modal();
    $('.carousel').carousel
    $('.tooltipped').tooltip();
    $("#browse_photo").css('visibility','hidden');
    $('.datepicker').datepicker();
    $('.dropdown-trigger').dropdown();
    $('.collapsible').collapsible();


    //initialize html object as hidden
    $('#div_re_assign_to').css('visibility','hidden');
    $('#filter_display').css('visibility','hidden');
    $('#display_email').css('visibility','hidden');
    $('#div_preloader').css('visibility','hidden');
    $('#div_email_sent_msg').css('visibility','hidden');
    
    //$('.dropdown-button').dropdown('open');

  });

