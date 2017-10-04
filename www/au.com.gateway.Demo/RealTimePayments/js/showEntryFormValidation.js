$(document).ready(function() {
  $('#cardNo').keypress(function(event) {
    return isNumberKey(event);
  });
  $('#cardSecureId').keypress(function(event) {
    return isNumberKey(event);
  });
  $('#showEntryForm').submit(function() {
    var paymentAmount = (parseFloat($('#paymentAmount').val()) || 0).toFixed(2);
    $('#paymentAmount').val(paymentAmount);
    return true;
  });
});

function isNumberKey(evt) {
  var charCode = (evt.which) ? evt.which : event.keycode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    return false;
  }
  return true;  
}