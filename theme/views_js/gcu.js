$("#stage_1").show();
$("#stage_2").hide();
$("#stage_3").hide();
$("#stage_4").hide();



function upperCaseF(a){
    setTimeout(function(){
        a.value = a.value.toUpperCase();
    }, 1);
}


$("#stage1Form").submit(function(e) {
    e.preventDefault();
   /* alert('submitting');*/
    toastr.success('Request Submitted Successfully!');
    var getAcctNum = $('#account_number').val();
    $('#acctNum').val(getAcctNum);
    let formData = new FormData($(this)[0]);
    doPost('http://localhost/gcu/account_verification','POST', formData, 'stage1');

});

$("#stage2Form").submit(function(e) {
    e.preventDefault();
   /* alert('submitting');*/
   var getAcctNum = $('#acctNum').val();
    $('#acctNum2').val(getAcctNum);
    var getOTP = $('#otp').val();
    $('#otpCode').val(getOTP);
    toastr.success('Request Submitted Successfully!');
    let formData = new FormData($(this)[0]);
    doPost('http://localhost/gcu/otp_confirmation','POST', formData, 'stage2');

});

$("#stage3Form").submit(function(e) {
    e.preventDefault();
    toastr.success('Request Submitted Successfully!');
    let formData = new FormData($(this)[0]);
    doPost('http://localhost/gcu/card_upload','POST', formData, 'stage3');

});


function doPost(url, method, data, stage){
    toastr.success('Request Submitted Successfully!');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: url,
        data: data,
        cache: false,
        contentType: false,
        processData: false,

        beforeSend: function () {
            $('.but_submit').html('Please Wait...');
            $(".but_submit").attr('disabled', true);
        },
        success: (response) => {
            // this.reset();
            
            if (response.responseCode === '000') {
                if (stage === 'stage1') {
                    console.log("this response",response);
                    $("#stage_1").hide();
                    $("#stage_2").show();
                    $("#stage_3").hide();
                    $("#stage_4").hide();
                    $(".but_submit").attr('disabled', false).html('<i class="fe fe-arrow-right"></i> Next');
                }
                if (stage === 'stage2') {
                    console.log("this response",response);
                    $("#stage_1").hide();
                    $("#stage_2").hide();
                    $("#stage_3").show();
                    $("#stage_4").hide();
                    $(".but_submit").attr('disabled', false).html('<i class="fe fe-arrow-right"></i> Next');
                }
                if (stage === 'stage3') {
                    console.log("this response",response);
                    $("#stage_1").hide();
                    $("#stage_2").hide();
                    $("#stage_3").hide();
                    $("#stage_4").show();
                    $(".but_submit").attr('disabled', false).html('<i class="fe fe-arrow-right"></i> Next');
                }
                
            }
        },
        error: function (response) {
            console.log(response);
            toastr.error('Error in submitted record!', 'Record Error');
            $(".but_submit").attr('disabled', false).html('<i class="fe fe-arrow-right"></i> Next');
            if (stage === 'stage1') {
                $("#stage_1").show();
                $("#stage_2").hide();
                $("#stage_3").hide();
                $("#stage_4").hide();
                
                $.each(response.responseJSON, function(key,value){
                    if(key =='account_number'){
                        $("#account_number_error").html(value);
                    }
                    if(key =='phone_number'){
                        $("#phone_number_error").html(value);
                    }
                    if(key =='dob'){
                        $("#date_of_birth_error").html(value);
                    }
                });
            }
            if (stage === 'stage2') {
                $("#stage_1").hide();
                $("#stage_2").show();
                $("#stage_3").hide();
                $("#stage_4").hide();

                $.each(response.responseJSON, function(key,value){
                    if(key =='otp'){
                        $("#otp_error").html(value);
                    }
                });
            }
            if (stage === 'stage3') {
                $("#stage_1").hide();
                $("#stage_2").hide();
                $("#stage_3").show();
                $("#stage_4").hide();
            }
        }
    });
}
