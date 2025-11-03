<!DOCTYPE html>
<head>
    <!-- Meta data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta name="csrf-token" class="csrf_token" content="{{ csrf_token() }}">
    <meta content="Dashtic - Bootstrap Webapp Responsive Dashboard Simple Admin Panel Premium HTML5 Template" name="description">
    <meta content="Spruko Technologies Private Limited" name="author">
    <meta name="keywords" content="Admin, Admin Template, Dashboard, Responsive, Admin Dashboard, Bootstrap, Bootstrap 4, Clean, Backend, Jquery, Modern, Web App, Admin Panel, Ui, Premium Admin Templates, Flat, Admin Theme, Ui Kit, Bootstrap Admin, Responsive Admin, Application, Template, Admin Themes, Dashboard Template"/>
    <!-- Title -->
    <title>Ghana Card Update</title>
    <!--Favicon -->
    <link rel="icon" href="theme/assets/images/brand/favicon.ico" type="image/x-icon"/>
    <!-- Bootstrap css -->
    <link href="theme/assets/plugins/bootstrap/css/bootstrap.css" rel="stylesheet" />
    <!-- Style css -->
    <link href="theme/assets/css/style.css" rel="stylesheet" />
    <!-- Dark css -->
    <link href="theme/assets/css/dark.css" rel="stylesheet" />
    <!-- Skins css -->
    <link href="theme/assets/css/skins.css" rel="stylesheet" />
    <!-- Animate css -->
    <link href="theme/assets/css/animated.css" rel="stylesheet" />
    <!---Icons css-->
    <link href="theme/assets/plugins/web-fonts/icons.css" rel="stylesheet" />
    <link href="theme/assets/plugins/web-fonts/font-awesome/font-awesome.min.css" rel="stylesheet">
    <link href="theme/assets/plugins/web-fonts/plugin.css" rel="stylesheet" />
    <!-- INTERNAL CSS START -->
    <!-- Select2 css -->
    <link href="theme/assets/plugins/select2/select2.min.css" rel="stylesheet" />
    <!-- File Uploads css -->
    <link href="theme/assets/plugins/fancyuploder/fancy_fileupload.css" rel="stylesheet" />
    <!-- Time picker css -->
    <link href="theme/assets/plugins/time-picker/jquery.timepicker.css" rel="stylesheet" />
    <!-- Date Picker css -->
    <link href="theme/assets/plugins/date-picker/date-picker.css" rel="stylesheet" />
    <!-- File Uploads css-->
    <link href="theme/assets/plugins/fileupload/css/fileupload.css" rel="stylesheet" type="text/css" />
    <!--Mutipleselect css-->
    <link rel="stylesheet" href="theme/assets/plugins/multipleselect/multiple-select.css">
    <!--Sumoselect css-->
    <link rel="stylesheet" href="theme/assets/plugins/sumoselect/sumoselect.css">
    <!--intlTelInput css-->
    <link rel="stylesheet" href="theme/assets/plugins/intl-tel-input-master/intlTelInput.css">
    <!--Jquerytransfer css-->
    <link rel="stylesheet" href="theme/assets/plugins/jQuerytransfer/jquery.transfer.css">
    <link rel="stylesheet" href="theme/assets/plugins/jQuerytransfer/icon_font/icon_font.css">
    <!--multi css-->
    <link rel="stylesheet" href="theme/assets/plugins/multi/multi.min.css">
    <!-- INTERNAL CSS END -->
    <link rel="stylesheet" type="text/css" href="theme/assets/css/toastr.css">{!! Toastr::message() !!}
</head>
<body class="h-100vh page-style1 light-mode default-sidebar">
    <div class="page">
        <div class="page-single">
            <div class="p-5">
                <div class="row">
                    <div class="col mx-auto">
                        <div class="row justify-content-center">
                            <div class="col-lg-9 col-xl-8">
                                <div class="card-group mb-0">
                                    <div class="card p-4 page-content">
                                        <div class="card-body page-single-content">
                                            <div class="w-100">
                                                <div class="btn-list d-sm-flex">
                                                    <img src="theme/assets/images/logo.jpg" class="img-fluid" alt="Responsive image">
                                                </div>
                                                <hr class="divider my-6">
                                                <div id="stage_1">
                                                    <form id="stage1Form" autocomplete="off">
                                                        <div class="form-group">
                                                            <label class="form-label">Account Number</label>
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-addon"><svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 16c-2.69 0-5.77 1.28-6 2h12c-.2-.71-3.3-2-6-2z" opacity=".3"/><circle cx="12" cy="8" opacity=".3" r="2"/><path d="M12 14c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4zm-6 4c.22-.72 3.31-2 6-2 2.7 0 5.8 1.29 6 2H6zm6-6c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0-6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z"/></svg></span>
                                                                <input type="text" id="account_number" name="account_number" class="form-control" placeholder="Account Number" onkeypress="return isSeatsNumber(event)" maxlength="13" minlength="13" required>
                                                                <span style="color: red;" id="account_number_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Phone Number (ex. 0241234567)</label>
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-addon"><svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M19 17.47c-.88-.07-1.75-.22-2.6-.45l-1.19 1.19c1.2.41 2.48.67 3.8.75v-1.49zM5.03 5c.09 1.32.35 2.59.75 3.8l1.2-1.2c-.23-.84-.38-1.71-.44-2.6H5.03z" opacity=".3"></path><path d="M9.07 7.57C8.7 6.45 8.5 5.25 8.5 4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.49c0-.55-.45-1-1-1-1.24 0-2.45-.2-3.57-.57-.1-.04-.21-.05-.31-.05-.26 0-.51.1-.71.29l-2.2 2.2c-2.83-1.45-5.15-3.76-6.59-6.59l2.2-2.2c.28-.28.36-.67.25-1.02zm7.33 9.45c.85.24 1.72.39 2.6.45v1.49c-1.32-.09-2.59-.35-3.8-.75l1.2-1.19zM5.79 8.8c-.41-1.21-.67-2.48-.76-3.8h1.5c.07.89.22 1.76.46 2.59L5.79 8.8z"></path></svg></span>
                                                                <input type="text" name="phone_number" class="form-control" placeholder="Phone Number" onkeypress="return isSeatsNumber(event)" maxlength="10" minlength="10" required>
                                                                <span style="color: red;" id="phone_number_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Date of Birth</label>
                                                            <div class="input-group mb-4">
                                                                <span class="input-group-addon"><svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M20 3h-1V1h-2v2H7V1H5v2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 2v3H4V5h16zM4 21V10h16v11H4z"></path><path d="M4 5.01h16V8H4z" opacity=".3"></path></svg></span>
                                                                <input class="form-control fc-datepicker" placeholder="YYYY-MM-DD" type="date" name="dob" required readonly>
                                                                <span style="color: red;" id="date_of_birth_error"></span>
                                                                
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <button type="submit" class="btn btn-lg btn-primary btn-block but_submit"><i class="fe fe-arrow-right"></i> Next</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div id="stage_2">
                                                    <form id="stage2Form" autocomplete="off">
                                                        <div class="alert alert-info">
                                                            <strong>Account Verification</strong>
                                                            <hr class="message-inner-separator">
                                                            <p>A verification code (OTP) has been sent to the phone number used in opening the account. Kindly enter the code and click Next.</p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Enter the verification code here </label>
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-addon"><svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M18 13h5v7h-5z" opacity=".3"></path><path d="M23 11.01L18 11c-.55 0-1 .45-1 1v9c0 .55.45 1 1 1h5c.55 0 1-.45 1-1v-9c0-.55-.45-.99-1-.99zM23 20h-5v-7h5v7zM2 4h18v5h2V4c0-1.11-.9-2-2-2H2C.89 2 0 2.89 0 4v12c0 1.1.89 2 2 2h7v2H7v2h8v-2h-2v-2h2v-2H2V4zm9 2l-.97 3H7l2.47 1.76-.94 2.91 2.47-1.8 2.47 1.8-.94-2.91L15 9h-3.03z"></path></svg></span>
                                                                <input type="text" id="otp" name="otp" class="form-control" placeholder="Verification Code" maxlength="6" minlength="6" required onkeydown="upperCaseF(this)">
                                                                <span style="color: red;" id="otp_error"></span>
                                                                <input type="hidden" id="acctNum" name="acctNum">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <button type="submit" class="btn btn-lg btn-primary btn-block but_submit"><i class="fe fe-arrow-right"></i> Next</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div id="stage_3">
                                                    <form id="stage3Form" autocomplete="off" enctype="multipart/form-data">
                                                        <div class="form-group">
                                                            <label class="form-label">Enter Ghana Card Number: </label>
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-addon"><svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M18 13h5v7h-5z" opacity=".3"></path><path d="M23 11.01L18 11c-.55 0-1 .45-1 1v9c0 .55.45 1 1 1h5c.55 0 1-.45 1-1v-9c0-.55-.45-.99-1-.99zM23 20h-5v-7h5v7zM2 4h18v5h2V4c0-1.11-.9-2-2-2H2C.89 2 0 2.89 0 4v12c0 1.1.89 2 2 2h7v2H7v2h8v-2h-2v-2h2v-2H2V4zm9 2l-.97 3H7l2.47 1.76-.94 2.91 2.47-1.8 2.47 1.8-.94-2.91L15 9h-3.03z"></path></svg></span>
                                                                <input type="text" name="gc_number" class="form-control" placeholder="GHA-XXXXXXXXX-X" maxlength="15" minlength="15" required onkeydown="upperCaseF(this)">
                                                                <span style="color: red;" id="gc_number_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-6 col-sm-12">
                                                                <div class="form-group">
                                                                    <center><label class="form-label">(Upload Front Image)</label></center>
                                                                    <input type="file" name="file_f" id="file_f" class="dropify" data-default-file="theme/assets/images/Ghana_card_front.jpg" data-height="100" required/>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-sm-12">
                                                                <div class="form-group">
                                                                    <center><label class="form-label">(Upload Back Image)</label></center>
                                                                    <input type="file" name="file_b" id="file_b" class="dropify" data-default-file="theme/assets/images/Ghana_card_back.jpg" data-height="100" required/>
                                                                </div>
                                                            </div>
                                                            
                                                            <label class="form-label">Image (PNG, JPEG format only. Maximum file size 5MB) </label>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <input type="hidden" id="acctNum2" name="acctNum2">
                                                                <input type="hidden" id="otpCode" name="otpCode">
                                                                <button type="submit" class="btn btn-lg btn-primary btn-block but_submit"><i class="fe fe-upload-cloud"></i> Submit Card</button>
                                                            </div>
                                                        </div>
                                                        
                                                    </form>
                                                </div>
                                                <div id="stage_4">
                                                    <div class="alert alert-info"><center>
                                                        <strong>Thank You For The Update </strong>
                                                        <hr class="message-inner-separator">
                                                        <p>Your Ghana card informa has been submited successfully for processing. You will be notified upon completion of the update.  </p></center>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card text-white bg-primary py-5 d-md-down-none page-content mt-0">
                                        <div class="card-body text-center justify-content-center page-single-content">
                                            <img src="theme/assets/images/pattern/gcu_bpsl2.png" alt="img">
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="text-center pt-4">
                                    <div class="font-weight-normal fs-16">You Don't have an account <a class="btn-link font-weight-normal" href="#">Register Here</a></div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Jquery js-->
    <script src="theme/assets/js/vendors/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap4 js-->
    <script src="theme/assets/plugins/bootstrap/popper.min.js"></script>
    <script src="theme/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!--Othercharts js-->
    <script src="theme/assets/plugins/othercharts/jquery.sparkline.min.js"></script>
    <!-- Circle-progress js-->
    <script src="theme/assets/js/vendors/circle-progress.min.js"></script>
    <!-- Jquery-rating js-->
    <script src="theme/assets/plugins/rating/jquery.rating-stars.js"></script>
    <!-- INTERNAL JS START -->
    <!--Select2 js -->
    <script src="theme/assets/plugins/select2/select2.full.min.js"></script>
    <script src="theme/assets/js/select2.js"></script>
    <!-- Timepicker js -->
    <script src="theme/assets/plugins/time-picker/jquery.timepicker.js"></script>
    <script src="theme/assets/plugins/time-picker/toggles.min.js"></script>
    <!-- Datepicker js -->
    <script src="theme/assets/plugins/date-picker/date-picker.js"></script>
    <script src="theme/assets/plugins/date-picker/jquery-ui.js"></script>
    <script src="theme/assets/plugins/input-mask/jquery.maskedinput.js"></script>
    <!--File-Uploads Js-->
    <script src="theme/assets/plugins/fancyuploder/jquery.ui.widget.js"></script>
    <script src="theme/assets/plugins/fancyuploder/jquery.fileupload.js"></script>
    <script src="theme/assets/plugins/fancyuploder/jquery.iframe-transport.js"></script>
    <script src="theme/assets/plugins/fancyuploder/jquery.fancy-fileupload.js"></script>
    <script src="theme/assets/plugins/fancyuploder/fancy-uploader.js"></script>
    <!-- File uploads js -->
    <script src="theme/assets/plugins/fileupload/js/dropify.js"></script>
    <script src="theme/assets/js/filupload.js"></script>
    <!-- Multiple select js -->
    <script src="theme/assets/plugins/multipleselect/multiple-select.js"></script>
    <script src="theme/assets/plugins/multipleselect/multi-select.js"></script>
    <!--Sumoselect js-->
    <script src="theme/assets/plugins/sumoselect/jquery.sumoselect.js"></script>
    <!--intlTelInput js-->
    <script src="theme/assets/plugins/intl-tel-input-master/intlTelInput.js"></script>
    <script src="theme/assets/plugins/intl-tel-input-master/country-select.js"></script>
    <script src="theme/assets/plugins/intl-tel-input-master/utils.js"></script>
    <!--jquery transfer js-->
    <script src="theme/assets/plugins/jQuerytransfer/jquery.transfer.js"></script>
    <!--multi js-->
    <script src="theme/assets/plugins/multi/multi.min.js"></script>
    <!-- Form Advanced Element -->
    <script src="theme/assets/js/formelementadvnced.js"></script>
    <script src="theme/assets/js/form-elements.js"></script>
    <script src="theme/assets/js/file-upload.js"></script>
    <script src="theme/views_js/allownumbersOnly.js"></script>
    <script src="theme/views_js/gcu.js"></script>
    <!-- INTERNAL JS END -->
    <script src="theme/assets/js/toastr.js"></script>
</body>
<!-- Mirrored from codeigniter.spruko.com/Dashtic/DASHTIC-LTR/pages/login-3 by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 08 Oct 2021 20:00:58 GMT -->
</html>