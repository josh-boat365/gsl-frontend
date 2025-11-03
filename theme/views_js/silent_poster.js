$(document).on('click', '.comment_btn', function(e) {
    action_type = $(this).attr('action_type');

    if (action_type === 'DECLINE') {
        $("#decline-main").remove();
        $("#decline-selector").append(
            '<div id="decline-main" class="form-group">' +
            '<select name="decline_type" class="form-control custom-select select2" required>' +
            '<option selected value="">Select Decline Type</option>' +
            '<option value="AMENDMENT">Reject For Amendment</option>' +
            '<option value="DECLINE">Decline Request</option>' +
            '</select>' +
            '</div>');

        $('.comment_title').text('Decline Request (Add a comment if any)');
    }
    if (action_type === 'APPROVE') {
        $("#decline-main").remove();
        $("#decline-selector").append(
            '<div id="decline-main" class="form-group">' +
            '<select name="approval_status" class="form-control custom-select select2" required>' +
            '<option selected value="APPROVED">APPROVE REQUEST</option>' +
            '<option value="DECLINED">DECLINE REQUEST</option>' +
            '</select>' +
            '</div>');
        $('.comment_title').text('Approve Request (Add a comment if any)');
    }

    $('#commentModal').modal('show');
});




$("#hrUpdateForm").submit(function(e) {
    e.preventDefault();
    /* alert('submitting');*/
    //toastr.success('Request Submitted Successfully!');
    let formData = new FormData($(this)[0]);
    alert(formData);
    console.log(formData);
    doPost('../hr-update','POST', formData);

});

$("#creditRiskUpdateForm").submit(function(e) {
    e.preventDefault();
    /* alert('submitting');*/
    //toastr.success('Request Submitted Successfully!');
    let formData = new FormData($(this)[0]);
    alert(formData);
    console.log(formData);
    doPost('../credit-risk-update','POST', formData);

});

$("#otherAttachmentForm").submit(function(e) {
    e.preventDefault();
    /* alert('submitting');*/
    //toastr.success('Request Submitted Successfully!');
    let formData = new FormData($(this)[0]);
    alert(formData);
    console.log(formData);
    doPost('../other-attachment','POST', formData);

});



function doPost(url, method, data){
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
            $('#but_submit').html('Please Wait...');
            $("#but_submit").attr('disabled', false);
        },
        success: (response) => {
            this.reset();
            // console.log("this response",response);
            // if (response.type === 'transfer_request') {
            //     $("#but_submit").attr('disabled', false).html('Add');
            //     /*toastr.success('Request Submitted Successfully!');*/
            // }
            // if (response.type === 'transfer_request_update') {
            //     $("#but_submit").attr('disabled', false).html('Update');
            //     /*toastr.success('Request Updated Successfully!');*/
            // }
        },
        error: function (response) {
            console.log(response);
            // toastr.error('Something Happened.');
            $('#but_submit').html('Please Try Again.');
        }
    });
}
//declarationStatus();
//$('#submit').prop("disabled", true);
function declarationStatus(){
    if ($("#declaration").is(':checked')) {
        $('#btnSubmit').prop("disabled", false);
    }
    else{
        $('#btnSubmit').prop("disabled", true);
    }
}
$("#declaration").click(function() {
    declarationStatus();
});

$('#payment_type').change(function(){
    paymentTypeInput()
});

$(".dropify-infos-message").hide();
$(".file-icon").hide();
$(".dropify-error").hide();
paymentTypeInput()

function paymentTypeInput(){
    if($('#payment_type').children("option:selected").val() == "MOBILE MONEY"){
        $(".MM").show();
        $(".BK").hide();
    }
    else{
        $(".BK").show();
        $(".MM").hide();
    }
}
