<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DocPrinterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\UserOnboardingController;
use App\Models\LoanRequest;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use amirsanni\phpewswrapper\PhpEwsWrapper;


Route::get('/abc', function () {
    $getRequest = LoanRequest::find(1);
    //return $getRequest->LoanCustomer->first_name;

    if (!is_null($getRequest) && $getRequest->otp_status != 'CONFIRMED') {
        $getOTP = 'H57EEF9B';
        $getRequest->otp_code = $getOTP;
        $getRequest->otp_sent = 'YES';
        $getRequest->otp_status = 'PENDING';
        if($getRequest->save()){
            $phoneNumber = preg_replace('/^0/', '233', $getRequest->LoanCustomer->mobile);
            //$this->sms($phoneNumber,"Your OTP is $getOTP");
            $to = $phoneNumber;
            $otp = "Your GSL loan verification code is: $getOTP";
            $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://mysms.nsano.com/api/v1/sms/single',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "sender": "Best GSL",
                "recipient": "'.$to.'",
                "message": "'.$otp.'"
            }',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'X-SMS-Apikey: f4ea26402bee8ab006a74c0be6a44d35',
                'cache-control: no-cache',
                'Accept: application/json'
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);
        return 1;
            return response()->json([
                'responseCode' => '000',
                'message' => 'updated'
            ], 200);

        }
    }else{
        return response()->json([
            'responseCode' => '404',
            'message' => 'Account number not found'
        ], 200);
    }
    //return $getRequest = LoanRequest::where('stage_info','stage_4')->whereBetween('approval_date', ['2024-02-01', '2024-02-29'])->get(['id','customer','account_number','payment_type','bank_name','pay_account_number','pay_account_name','bank_branch','sort_code']);
    //return $getRequest->loan_customer->first_name;

    // $loan_amount = 10000;
    // $rate  =12;
    // $tenor = 24;
    // $exLoanPayment = 2000;
    // $netSalary = 11000;


    // $rate  = ($rate /100)/$rate ;
    // $repayment_amount = $loan_amount*(($rate* pow((1+$rate ),$tenor))/(pow((1+$rate ),$tenor)-1));
    // $monthlyInstallment = round($repayment_amount,2);

    // $DSR = (($monthlyInstallment + $exLoanPayment) / $netSalary) * 100;

    // return round($DSR,2);
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Clear App cache:
Route::get('/larafresh', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('route:cache');
    Artisan::call('clear-compiled');
    Artisan::call('config:clear');
    Artisan::call('config:cache');

    return 'Project Refreshed Successfully!!!';
});

Route::get('route-collection', function () {
    $routeCollection = Route::getRoutes();

    echo "<table style='width:100%'>";
    echo '<tr>';
    echo "<td width='10%'><h4>HTTP Method</h4></td>";
    echo "<td width='10%'><h4>Route</h4></td>";
    echo "<td width='10%'><h4>Name</h4></td>";
    echo "<td width='70%'><h4>Corresponding Action</h4></td>";
    echo '</tr>';
    foreach ($routeCollection as $value) {
        echo '<tr>';
        echo '<td>'.$value->methods()[0].'</td>';
        echo '<td>'.$value->uri().'</td>';
        echo '<td>'.$value->getName().'</td>';
        echo '<td>'.$value->getActionName().'</td>';
        echo '</tr>';
    }
    echo '</table>';
});

Route::post('/account_verification', [CustomerController::class, 'accountVerification']);
Route::post('/otp_confirmation', [CustomerController::class, 'otpConfirmation']);
Route::post('/card_upload', [CustomerController::class, 'cardUpload']);

//Clear route cache:
Route::get('/route-cache', function () {
    $exitCode = Artisan::call('route:cache');

    return 'Routes cache cleared';
});
//Clear config cache:
Route::get('/config-cache', function () {
    $exitCode = Artisan::call('config:cache');

    return 'Config cache cleared';
});
// Clear application cache:
Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');

    return 'Application cache cleared';
});
// Clear view cache:
Route::get('/view-clear', function () {
    $exitCode = Artisan::call('view:clear');

    return 'View cache cleared';
});

Auth::routes();

Route::get('/test-email', function () {

    $ews = new PhpEwsWrapper('leaveportal@bestpointgh.com', 'Ab123456.', 'outlook.bestpointgh.com','2013');

    $ews->mail->sender_name = "LeavePortal";
    $ews->mail->subject = 'Test';
    $ews->mail->body = $message;
    $ews->mail->send_as_email = 'LeavePortal@bestpointgh.com';//erer/to send as another user, not the logged in user. Optional
    $ews->mail->recipient = 'cashun@bestpointgh.com'; //['abc@xyz.com', 'abc@example.com']
    $ews->mail->recipient_name = 'Christian Ashun';
    $ews->mail->send();

    return 1;

});

Route::get('/logout', [LoginController::class, 'logout'])->name('logout-link');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/', [HomeController::class, 'index'])->name('index');


Route::post('/pre-disbursement/{updateType}', [LoanController::class, 'prePayment'])->name('pre-disbursement');
Route::get('/pre-disbursement/{updateType}', [LoanController::class, 'index'])->name('disbursement');
Route::get('/export-pre-disbursement/{payFrom}/{payTo}', [ReportController::class, 'exportPayments'])->name('export-pre-disbursement');
Route::get('/list', [LoanController::class, 'index'])->name('list');
Route::get('/list/{stage}', [LoanController::class, 'index'])->name('list.stage');
Route::get('/pending-approval/{id}', [LoanController::class, 'pendingApproval'])->name('pending-approval');
Route::post('/hr-update/{id}', [LoanController::class, 'hrUpdate'])->name('hr-update');
Route::post('/credit-risk-update/{id}', [LoanController::class, 'creditRiskUpdate'])->name('credit-risk-update');
Route::get('/create', [LoanController::class, 'create'])->name('create');
Route::get('/create/{id}', [LoanController::class, 'create'])->name('create.id');
Route::get('/details/{id}', [LoanController::class, 'show'])->name('details');
Route::post('/store', [LoanController::class, 'store'])->name('store');
Route::post('/request-approval/{id}', [LoanController::class, 'storeApprovals'])->name('request-approval');
Route::get('/decline/{id}', [LoanController::class, 'decline'])->name('decline');


// User Onboarding
Route::get('/user/onboard', [UserOnboardingController::class, 'create'])->name('user.onboard.create');
Route::post('/user/onboard', [UserOnboardingController::class, 'store'])->name('user.onboard.store');

Route::get('/profile', [ProfileController::class, 'userProfile'])->name('profile');
Route::post('/update-profile', [ProfileController::class, 'updateProfile'])->name('update-profile');
Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');


Route::get('/print-cet/{id}', [DocPrinterController::class, 'printDisCet'])->name('print-cet');
Route::get('/print-letter/{id}', [DocPrinterController::class, 'printOfferLetter'])->name('print-letter');


Route::post('/otp-confirmation', [LoanController::class, 'otpConfirmation'])->name('otp-confirmation');
Route::get('/resend_otp/{id}', [LoanController::class, 'resendCode']);
Route::post('change-branch/{id}', [LoanController::class, 'changeBranch'])->name('change-branch');
Route::post('details/change-request-branch/{id}', [LoanController::class, 'changeRequestBranch'])->name('request-branch');