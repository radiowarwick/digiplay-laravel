$(document).ready(function(){
  $("#advancedsearchform").hide();
  $(document).on("click","#advancedsearchtoggle",function(){
    $("#advancedsearchform").toggle();
  });
});
