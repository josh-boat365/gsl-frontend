// //allownumbersOnly
       function isSeatsNumber(evt)
       {
         var charCode = (evt.which) ? evt.which : event.keyCode
          if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
            return true;
       }
//       //-->

//allownumbersOnly with a point
      function isNumberKey(evt, element){

        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57) && !(charCode == 46 || charCode == 8 || charCode == 45))
            return false;
        else {
            var len = $(element).val().length;
            // Validation Point
            var index = $(element).val().indexOf('.');
            if ((index > 0 && charCode == 46) || len == 0 && charCode == 46) {
                return false;
            }
            if (index > 0) {
                var CharAfterdot = (len + 1) - index;
                if (CharAfterdot > 3) {
                    return false;
                }
            }
            // Validating Negative sign
            index = $(element).val().indexOf('-');
            if ((index > 0 && charCode == 45) || (len > 0 && charCode == 45)) {
                return false;
            }
        }
        return true;
    }

    function isContactKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode
    return !(charCode > 31 && (charCode < 48 || charCode > 57));
    
}

$(".allownumericwithdecimal").on("keypress keyup blur",function (event) {
        //this.value = this.value.replace(/[^0-9\.]/g,'');
    $(this).val($(this).val().replace(/[^0-9\.]/g,''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
});

 $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {    
   $(this).val($(this).val().replace(/[^\d].+/, ""));
    if ((event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
});
