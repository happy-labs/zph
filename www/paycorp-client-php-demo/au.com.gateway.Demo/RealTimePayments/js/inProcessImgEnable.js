$(document).ready(function(){
  $('#inProcessImg').hide();
  $("#showEntryForm").submit(function(){
      $("#inProcessImg").show();
      $(this).delay(500);
      return true;
  });
});