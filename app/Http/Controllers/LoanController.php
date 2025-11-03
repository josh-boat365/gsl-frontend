<?php

namespace App\Http\Controllers;

use App\Models\LoanGuarantor;
use App\Models\LoanCustomer;
use App\Models\LoanRequest;
use App\Models\User;
use Illuminate\Support\Carbon;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    /**
        * Create a new controller instance.
        *
        * @return void
        */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getBatchNum($lenght = 6) {
        // uniqid gives 13 chars, but you could adjust it to your needs.
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            throw new Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, $lenght);
    }


    public function resendCode($id){

        $getRequest = LoanRequest::find($id);

        if (!is_null($getRequest) && $getRequest->otp_status != 'CONFIRMED') {
                $getOTP = strtoupper($this->getBatchNum());
                $getRequest->otp_code = $getOTP;
                $getRequest->otp_sent = 'YES';
                $getRequest->otp_status = 'PENDING';
                if($getRequest->save()){
                    $phoneNumber = preg_replace('/^0/', '233', $getRequest->LoanCustomer->mobile);
                    $this->sms($phoneNumber,"Your Best Point GSL loan verification code is: $getOTP");
                    // return response()->json([
                    //     'responseCode' => '000',
                    //     'message' => 'updated'
                    // ], 200);
                    Toastr::success('Code sent successfully', 'OTP Confirmation');
                    return redirect()->back();
                }

        }else{
            // return response()->json([
            //     'responseCode' => '404',
            //     'message' => 'Request not found'
            // ], 200);
            Toastr::error('Record already confirmed', 'OTP Confirmation');
            return redirect()->back();
        }
    }


    public function otpConfirmation(Request $request){
        if($request->btnOTP == 'otpConf'){
return 441;            
        }

        $validator = Validator::make($request->all(), [
            'otp_code'=>'required'
        ]);

        if ($validator->fails()) {
            Toastr::error('OTP code field is required. Field is empty', 'Input Error!!!');
            return redirect()->back();
            //return response()->json($validator->errors(), 400);
        }

        $getRequest = LoanRequest::find($id);
        if(!is_null($getRequest) && $getRequest->otp_status != 'CONFIRMED' && $getRequest->otp_code == trim($request->otp_code)){
            $getRequest->otp_status = 'CONFIRMED';
            if($getRequest->save()){
                // return response()->json([
                //         'responseCode' => '000',
                //         'message' => 'Code Confirmed Successfully'
                //     ], 200);
                Toastr::success('OTP code confirmed successfully', 'OTP Confirmation');
                    return redirect()->back();

            }else{
                Toastr::error('Record not confirmed', 'OTP Confirmation');
                return redirect()->back();
                // return response()->json([
                //         'responseCode' => '403',
                //         'message' => 'something went wrong'
                //     ], 403);
            }
        }else{
            Toastr::error('Error confirming Record. Request not found!', 'OTP Confirmation');
            return redirect()->back();
            // return response()->json([
            //     'responseCode' => '404',
            //     'message' => 'Request not found'
            // ], 200);
        }

    }


    // public function dashboard()
    // {
    //     return view('workspace.leave.list');
    // }


    /**
        * Display a listing of the resource.
        *
        * @return Response
        */
    public function index($stage = '0')
    {
        $btnNewLoan = 0;
        $btnAmend = 0;
        $searchDisbursement = 0;
        $btnExport = 0;

        switch ($stage) {
            case 'stage_1':
                $allRequest = LoanRequest::where('stage_info','stage_1')
                                ->where('branch_code',Auth::user()->branch_code)
                                ->get();
                break;

            case 'stage_2':
                $allRequest = LoanRequest::where('stage_info','stage_2')
                                ->where('branch_code',Auth::user()->branch_code)
                                ->where('frequency','NEW')
                                ->get();
                break;

            case 'stage_3':
                $allRequest = LoanRequest::where('stage_info','stage_3')
                                ->where('branch_code',Auth::user()->branch_code)
                                ->get();
                break;

            case 'stage_4':
                $searchDisbursement = 1;
                $allRequest = LoanRequest::where('stage_info','stage_4')
                                ->where('branch_code',Auth::user()->branch_code)
                                ->get();
                break;

            case 'stage_5':
                $btnExport = 1;
                $searchDisbursement = 1;
                $allRequest = LoanRequest::where('stage_info','stage_5')
                                ->where('branch_code',Auth::user()->branch_code)
                                ->where('status_flag','DISBURSED')
                                ->get();
                                //$allRequest = LoanRequest::whereNotNull('acct_created_by')
                break;

            case 'stage_6':
                $allRequest = LoanRequest::where('stage_info','stage_6')->get();
                break;

            case 'stage_7':
                $allRequest = LoanRequest::where('stage_info','stage_7')->get();
                break;

            case 'stage_8':
                $allRequest = LoanRequest::where('stage_info','stage_8')->get();
                break;

            case 'stage_9':
                $allRequest = LoanRequest::where('stage_info','stage_9')->get();
                break;

            case 'stage_10':
                $allRequest = LoanRequest::where('stage_info','stage_10')->get();
                break;

            case 'stage_11':
                $allRequest = LoanRequest::where('stage_info','stage_11')->get();
                break;

            case 'stage_0':
                $allRequest = LoanRequest::where('branch_code',Auth::user()->branch_code)->get();
                break;

            case 'stage_0_0':
                $allRequest = LoanRequest::where('stage_info','stage_0_0')->get();
                break;

            case '0':
                $btnNewLoan = 1;
                $btnAmend = 1;
                $allRequest = LoanRequest::where('request_by',Auth::user()->id)->get();
                break;

            case 'stage_0_1':
                //$searchDisbursement = 1;
                $allRequest = LoanRequest::where('stage_info','stage_0_1')
                                ->where('request_by',Auth::user()->id)
                                ->get();
                break;

            default:
                return redirect()->back();

        }

        return view('workspace.loan.list',['requestData'=>$allRequest,'btnNewLoan'=>$btnNewLoan,'btnAmend'=>$btnAmend,'searchDisbursement'=>$searchDisbursement,'btnExport'=>$btnExport]);
    }


    public function pendingApproval($id)
    {
        $allRequest = LeaveRequest::whereHas('Approval', function($query) use ($id){
            $query->where('request_by',$id)->where('status','Pending');
        })->get();
        $applyLeave = 0;
        return view('workspace.loan.list',['requestData'=>$allRequest,'applyLeave'=>$applyLeave]);
    }

    public function hrApproval()
    {
        $allRequest = LeaveRequest::where('stage_info','stage_two')
                            ->where('status_flag','PROCESSING')
                            ->where('approval_date',NULL)
                            ->get();
        $applyLeave = 0;
        return view('workspace.loan.list',['requestData'=>$allRequest,'applyLeave'=>$applyLeave]);
    }

    /**
        * Show the form for creating a new resource.
        *
        * @return Response
        */
    public function create($id=0)
    {
        if ($id != 0){
            $get_request = LoanRequest::find($id);
        }else{
            $get_request = LoanRequest::find(0000000000000);
        }
        // $getJobTitle = DB::table('job_titles')->get();
        // $getDepartment = DB::table('departments')->get();
        $getBranch = DB::table('branches')->get();
        $getUsers = User::where('branch_code',Auth::user()->branch_code)->orderBy('first_name')->get();

        return view('workspace.loan.create',['branches'=>$getBranch, 'users'=>$getUsers, 'data'=>$get_request ]);
    }


// {
//     "_token": "Hv4HZ62qSMUS0gQkRzA6LRaWj511xZTXVhLcGYei",
//     "ref_num":"UY868U11"
//     "first_name": "Christian",
//     "surname": "Ashun",
//     "middle_name": "KOFI",
//     "date_of_birth": "2024-02-11",
//     "age": "34",
//     "gender": "MALE",
//     "id_type": "GHANA CARD",
//     "id_number": "GHA-21010465-9",
//     "date_of_issue": "2024-02-01",
//     "expiry_date": "2025-06-28",
//     "place_of_issue": "TEMA",
//     "residential_address": "GW-1191-7271",
//     "residential_landmark": "fdssa",
//     "work_address": "sadfsa",
//     "work_landmark": "asdfas",
//     "mobile": "45345",
//     "home_phone": "sfdaf",
//     "work_phone": "34532",
//     "email": "ash11nb22@gmail.com",
//     "g_full_name": "Christian Ashun",
//     "g_relationship": "sadfads",
//     "g_mobile": "+233207955915",
//     "g_home_phone": null,
//     "g_work_phone": "sadfas",
//     "g_email": "asfda",
//     "g_residential_address": "GW-1191-7271",
//     "g_residential_landmark": "asdfasf",
//     "g_work_address": "dsafasfsa",
//     "g_work_landmark": "asdfasf",
//     "occupation": "sdafasf",
//     "employer": "asfasf",
//     "department": "asfdas",
//     "employment_date": "safas",
//     "employee_number": "asdfas",
//     "years_employed": "asfas",


//     "requested_amount": "50000",
//     "monthly_installment": "345",
//     "net_salary": "345",
//     "tenure": "345",
//     "payment_type": "BANK ACCOUNT",
//     "bank_name": "asewed",
//     "bank_branch": "dsfafa",
//     "sort_code": "safas",
//     "pay_account_number": "safdsfsa",
//     "pay_account_name": "asdfasdf",

//     "pp_attachment": {},
//     "id_attachment": {},
//     "dd_attachment": {}
// }

    /**
        * Store a newly created resource in storage.
        *
        * @return Response
        */
    public function store(Request $request)
    {

        //return $request;
        //$user = User::find(Auth::user()->id);

        $loan_request = LoanRequest::firstOrNew(['ref_num' => $request->ref_num]);

        $loan_request->ref_num = $request->ref_num;
        $loan_request->requested_amount = $request->requested_amount;
        $loan_request->monthly_installment = $request->monthly_installment;
        $loan_request->net_salary = $request->net_salary;
        $loan_request->tenure = $request->tenure;
        $loan_request->frequency = $request->frequency;
        $loan_request->branch_code = Auth::user()->branch_code;
        $loan_request->request_by = Auth::user()->id;
        $loan_request->request_date = date("Y-m-d H:i:s");

        if (!empty($request->otp_code) || $request->otp_code == "") {
            $loan_request->otp_code = $request->otp_code;
            $loan_request->otp_sent = 'YES';
            $loan_request->otp_status = 'CONFIRMED';
        }

        if ($request->hasFile('pp_attachment')) {
            $PATH = $request->file('pp_attachment')->store('attachment');
            $loan_request->pp_attachment = $PATH;
        }
        if ($request->hasFile('id_attachment')) {
            $PATH = $request->file('id_attachment')->store('attachment');
            $loan_request->id_attachment = $PATH;
        }
        if ($request->hasFile('dd_attachment')) {
            $PATH = $request->file('dd_attachment')->store('attachment');
            $loan_request->dd_attachment = $PATH;
        }
        if ($request->hasFile('affordability_doc')) {
            $PATH = $request->file('affordability_doc')->store('attachment');
            $loan_request->affordability_doc = $PATH;
        }
        if ($request->hasFile('payslip_1')) {
            $PATH = $request->file('payslip_1')->store('attachment');
            $loan_request->payslip_1 = $PATH;
        }
        if ($request->hasFile('payslip_2')) {
            $PATH = $request->file('payslip_2')->store('attachment');
            $loan_request->payslip_2 = $PATH;
        }
        if ($request->hasFile('payslip_3')) {
            $PATH = $request->file('payslip_3')->store('attachment');
            $loan_request->payslip_3 = $PATH;
        }
        if ($request->hasFile('mandate_form')) {
            $PATH = $request->file('mandate_form')->store('attachment');
            $loan_request->mandate_form = $PATH;
        }
        if($request->action == 'save'){
            $loan_request->stage_info = 'stage_0_1';
            $loan_request->status_flag = 'NEW';
        }
        if($request->action == 'submit'){
            $loan_request->stage_info = 'stage_1';
            $loan_request->status_flag = 'PROCESSING';
        }
        $loan_request->payment_type = $request->payment_type;
        if($request->payment_type == 'BANK ACCOUNT'){
            $loan_request->bank_name = $request->bank_name;
            $loan_request->bank_branch = $request->bank_branch;
            $loan_request->sort_code = $request->sort_code;
            $loan_request->pay_account_number = $request->pay_account_number;
            $loan_request->pay_account_name = $request->pay_account_name;
            $loan_request->network = 'BANK';
        }
        if($request->payment_type == 'MOBILE MONEY'){
            $loan_request->bank_name = $request->network;
            $loan_request->network = $request->network;
            $loan_request->pay_account_number = $request->pay_account_number;
            $loan_request->pay_account_name = $request->pay_account_name;
        }

        if($loan_request->save()){

            $loan_customer = LoanCustomer::firstOrNew(['request_id' => $loan_request->id]);
            $loan_customer->request_id = $loan_request->id;
            $loan_customer->first_name = $request->first_name;
            $loan_customer->surname = $request->surname;
            $loan_customer->middle_name = $request->middle_name;
            $loan_customer->date_of_birth = $request->date_of_birth;
            $loan_customer->age = $request->age;
            $loan_customer->gender = $request->gender;
            $loan_customer->id_type = $request->id_type;
            $loan_customer->id_number = $request->id_number;
            $loan_customer->date_of_issue = $request->date_of_issue;
            $loan_customer->expiry_date = $request->expiry_date;
            $loan_customer->place_of_issue = $request->place_of_issue;
            $loan_customer->residential_address = $request->residential_address;
            $loan_customer->residential_landmark = $request->residential_landmark;
            $loan_customer->work_address = $request->work_address;
            $loan_customer->work_landmark = $request->work_landmark;
            $loan_customer->mobile = $request->mobile;
            $loan_customer->home_phone = $request->home_phone;
            $loan_customer->work_phone = $request->work_phone;
            $loan_customer->email = $request->email;
            $loan_customer->occupation = $request->occupation;
            $loan_customer->employer = $request->employer;
            $loan_customer->department = $request->department;
            $loan_customer->employment_date = $request->employment_date;
            $loan_customer->employee_number = $request->employee_number;
            $loan_customer->years_employed = $request->years_employed;
            $loan_customer->g_full_name = $request->g_full_name;
            $loan_customer->g_relationship = $request->g_relationship;
            $loan_customer->g_mobile = $request->g_mobile;
            $loan_customer->g_home_phone = $request->g_home_phone;
            $loan_customer->g_work_phone = $request->g_work_phone;
            $loan_customer->g_email = $request->g_email;
            $loan_customer->g_residential_address = $request->g_residential_address;
            $loan_customer->g_residential_landmark = $request->g_residential_landmark;
            $loan_customer->g_work_address = $request->g_work_address;
            $loan_customer->g_work_landmark = $request->g_work_landmark;
            $loan_customer->created_by = Auth::user()->id;

            $loan_customer->save();

            // $message = 'A new loan request pending your supervision. From: '.Auth::user()->first_name .' '.Auth::user()->last_name.'. Click on URL to open LoanPortal App: http://localhost/staff_loan/list/stage_1 <br/><br/><br/><br/><br/><br/><br/>';
            // $subject = 'New loan request pending your supervision';
            // $to = $request->supervisor_id;
            // sendMail($subject, $message, $to);

            // if (!empty($request->amendment_stage) || $request->amendment_stage != ''){
            //     $message = 'Amended loan pending your supervision. From: '.Auth::user()->first_name .' '.Auth::user()->last_name.'.<br/><br/>Amendment Remark : '.$loan_request->amendment_remark.'<br/><br/> Click on URL to open LoanPortal App: http://localhost/staff_loan/home <br/><br/><br/><br/><br/><br/><br/>';
            //     $subject = 'Amendment supervision';
            //     $to = $loan_request->amend_request_from;
            //     sendMail($subject, $message, $to);
            // }else{
                // $message = 'A new loan request pending your supervision. From: '.Auth::user()->first_name .' '.Auth::user()->last_name.'. Click on URL to open LoanPortal App: http://localhost/staff_loan/list/stage_1 <br/><br/><br/><br/><br/><br/><br/>';
                // $subject = 'New loan request pending your supervision';
                // $to = $request->supervisor_id;
                // sendMail($subject, $message, $to);
            // }


        }

        Toastr::success('Request successful', 'New Loan Request');
        return redirect()->back();
    }




    public function storeApprovals(Request $request, $id)
    {
        //return $request;
        if($request->btnOTP == 'otpConf'){
            $validator = Validator::make($request->all(), [
                'otp_code'=>'required'
            ]);

            if ($validator->fails()) {
                Toastr::error('OTP code field is required. Field is empty', 'Input Error!!!');
                return redirect()->back();
                //return response()->json($validator->errors(), 400);
            }

            $getRequest = LoanRequest::find($id);
            if(!is_null($getRequest) && $getRequest->otp_status != 'CONFIRMED' && $getRequest->otp_code == trim($request->otp_code)){
                $getRequest->otp_status = 'CONFIRMED';
                if($getRequest->save()){
                    // return response()->json([
                    //         'responseCode' => '000',
                    //         'message' => 'Code Confirmed Successfully'
                    //     ], 200);
                    Toastr::success('OTP code confirmed successfully', 'OTP Confirmation');
                        return redirect()->back();

                }else{
                    Toastr::error('Record not confirmed', 'OTP Confirmation');
                    return redirect()->back();
                    // return response()->json([
                    //         'responseCode' => '403',
                    //         'message' => 'something went wrong'
                    //     ], 403);
                }
            }else{
                Toastr::error('Invalid OTP Code! Kindly Check and try again.', 'OTP Confirmation');
                return redirect()->back();
                // return response()->json([
                //     'responseCode' => '404',
                //     'message' => 'Request not found'
                // ], 200);
            }        
        }

        if(isset($request->decline_type) ){

            switch ($request->decline_type) {
                case 'AMENDMENT':

                    $update_data = LoanRequest::find($request->request_id);
                    $update_data->amendment_stage = $update_data->stage_info;
                    $update_data->stage_info = 'stage_0_1';
                    $update_data->amend_request_from = Auth::user()->id;
                    $update_data->amendment_remark = $request->comment_body;
                    $update_data->amendment_request_date = date("Y-m-d H:i:s");
                    $update_data->save();
                    Toastr::success('Requested successfully', 'Amendment Request');

                    // $message = 'Your loan request has been declined for amendment. Declined By: '.Auth::user()->first_name .' '.Auth::user()->last_name.'.<br/><br/>Amendment Remark : '.$update_data->amendment_remark.'<br/><br/> Click on URL to open LoanPortal App: http://localhost/staff_loan/home <br/><br/><br/><br/><br/><br/><br/>';
                    // $subject = 'Loan Request Amendment';
                    // $to = $update_data->request_by;
                    // sendMail($subject, $message, $to);

                    return redirect('list/'.$update_data->amendment_stage);
                    break;
                case 'DECLINE':

                    $update_data = LoanRequest::find($request->request_id);
                    if($update_data->status_flag != 'DISBURSED'){
                        $update_data->amendment_stage = $update_data->stage_info;
                        $update_data->stage_info = 'stage_0_0';
                        $update_data->declined_by = Auth::user()->id;
                        $update_data->declined_remark = $request->comment_body;
                        $update_data->status_flag = 'DECLINED';
                        $update_data->declined_date = date("Y-m-d H:i:s");
                        $update_data->save();
                        Toastr::success('Declined successfully', 'Decline Request');

                        // $message = 'Your loan request has been declined. Declined By: '.Auth::user()->first_name .' '.Auth::user()->last_name.'.<br/><br/>Amendment Remark : '.$update_data->amendment_remark.'<br/><br/> Click on URL to open LoanPortal App: http://localhost/staff_loan/home <br/><br/><br/><br/><br/><br/><br/>';
                        // $subject = 'Loan Request Declined';
                        // $to = $update_data->request_by;
                        // sendMail($subject, $message, $to);
                    }

                    return redirect('list/'.$update_data->amendment_stage);
                    break;
                default:
                    return redirect()->back();
            }
        }

        switch ($request->processing_stage) {
            //BM / HOD / Supervisor
            case 'stage_1':

                $update_data = LoanRequest::find($request->request_id);

               // if($update_data->frequency == 'NEW'){
                   // $update_data->stage_info = 'stage_2';
                //}else{
                    $update_data->stage_info = 'stage_3';
                //}
                
                $update_data->save();
                Toastr::success('Recommended successfully', 'Recommendation');

                // $message = 'A new loan request pending your confirmation. Click on URL to open LoanPortal App: http://localhost/staff_loan/home <br/><br/><br/><br/><br/><br/><br/>';
                // $subject = 'New Loan Confirmation';
                // sendMailGroup($subject, $message, 15);

                return redirect('list/stage_1');
                break;
                //HR Confirmation
            case 'stage_2':

                $update_data = LoanRequest::find($request->request_id);

                // if($update_data->frequency == 'NEW'){
                //     $update_data->stage_info = 'stage_2';
                // }else{
                //     $update_data->stage_info = 'stage_3';
                // }
                
                $update_data->stage_info = 'stage_4';
                $update_data->acct_created_by = Auth::user()->id;
                $update_data->acct_creation_date = date("Y-m-d H:i:s");
                $update_data->acct_creation_remark = $request->comment_body;
                $update_data->save();
                Toastr::success('Account created successfully', 'Account Creation');

                // $message = 'A new loan request pending your recommendation. Click on URL to open LoanPortal App: http://localhost/staff_loan/home <br/><br/><br/><br/><br/><br/><br/>';
                // $subject = 'New Loan Recommendation';
                // sendMailGroup($subject, $message, 9);

                return redirect('list/stage_2');
                break;
                //Credit Risk Recommend
            case 'stage_3':

                $update_data = LoanRequest::find($request->request_id);
                
                if($update_data->frequency == 'NEW'){
                    $update_data->stage_info = 'stage_2';
                }else{
                    $update_data->stage_info = 'stage_4';
                }
                //$update_data->stage_info = 'stage_4';
                $update_data->approved_by = Auth::user()->id;
                $update_data->approval_date = date("Y-m-d H:i:s");
                $update_data->status_flag = 'APPROVED';
                $update_data->approval_remark = $request->comment_body;
                $update_data->save();
                Toastr::success('Approved successfully', 'Approval');

                // $message = 'A new loan request pending your approval. Click on URL to open LoanPortal App: http://localhost/staff_loan/home <br/><br/><br/><br/><br/><br/><br/>';
                // $subject = 'New Loan Approval';
                // sendMailGroup($subject, $message, 10);

                return redirect('list/stage_3');
                break;
                //MCC Approval
            case 'stage_4':

                $data = [
                    'request_by' => Auth::user()->id,
                    'approver_name' => user(Auth::user()->id)->first_name .' '. user(Auth::user()->id)->last_name,
                    // 'a_position' => getJobTitle(user(Auth::user()->id)->position_id)->title_name,
                    'approval_date' => date("Y-m-d H:i:s"),
                    'a_remark' => $request->comment_body,
                    'a_sig' => user(Auth::user()->id)->sig,
                    'a_stage_info' => 'stage_4',
                    'approval_status' => $request->approval_status
                ];


                $update_data = LoanRequest::find($request->request_id);
                $request_id = $request->request_id;
                if($update_data->stage_info == 'stage_4' && Auth::user()->role_id == 10){
                    $check_user_approvals = $update_data->whereHas('LoanApproval', function($q) use($request_id){
                    $q->where('request_id',$request_id)->where('request_id',$request_id)->where('request_by', Auth::user()->id)->where('a_stage_info','stage_4');
                    })->count();
                    if($check_user_approvals){
                        return redirect()->back();
                    }else{
                        $update_data->LoanApproval()->create($data);
                        $update_data->total_mcc_approved += 1;
                        $update_data->save();
                        if($update_data->total_mcc_approved >= $update_data->total_mcc_approval){
                            $update_data->approval_date = date("Y-m-d H:i:s");
                            $update_data->stage_info = 'stage_5';
                            $update_data->status_flag = 'APPROVED';
                            $update_data->save();
                            Toastr::success('Approved successfully', 'Request Approval');

                            $message = 'A new loan request pending  offer letter attachment. Click on URL to open LoanPortal App: http://localhost/staff_loan/home <br/><br/><br/><br/><br/><br/><br/>';
                            $subject = 'Loan Acceptance Update';
                            if($update_data->branch_code == '0') {
                                sendMailGroup($subject, $message, 3,);
                            }else {
                                sendMailGroup($subject, $message, 5,$update_data->branch_code);
                            }

                        }
                        return redirect()->back();
                    }
                }else{
                    return redirect()->back();
                }

                return redirect('list/stage_4');
                break;
                //Business Unit Update
            case 'stage_5':

                $data = [
                    'request_by' => Auth::user()->id,
                    'approver_name' => user(Auth::user()->id)->first_name .' '. user(Auth::user()->id)->last_name,
                    'a_position' => getJobTitle(user(Auth::user()->id)->position_id)->title_name,
                    'approval_date' => date("Y-m-d H:i:s"),
                    'a_remark' => $request->comment_body,
                    'a_sig' => user(Auth::user()->id)->sig,
                    'a_stage_info' => 'stage_5'
                ];

                $update_data = LoanRequest::find($request->request_id);
                $request_id = $request->request_id;
                if(($update_data->stage_info == 'stage_5') && ($update_data->branch_code == Auth::user()->branch_code) && (Auth::user()->role_id == 5 || Auth::user()->role_id == 3)){
                    $check_user_approvals = $update_data->whereHas('LoanApproval', function($q) use($request_id){
                    $q->where('request_id',$request_id)->where('request_by', Auth::user()->id)->where('a_stage_info','stage_5');
                    })->count();
                    if($check_user_approvals){
                        return redirect()->back();
                    }else{
                        if(empty($update_data->attachment2)){
                            Toastr::error('Offer Letter Not Attached', 'Field Omitted');
                            return redirect()->back();
                        }else{
                            $update_data->LoanApproval()->create($data);
                            $update_data->stage_info = 'stage_6';
                            $update_data->save();
                            Toastr::success('Updated successfully', 'Request Update');

                            $message = 'A new loan request pending your Review. Click on URL to open LoanPortal App: http://localhost/staff_loan/home <br/><br/><br/><br/><br/><br/><br/>';
                            $subject = 'New Loan Review';
                            sendMailGroup($subject, $message, 14);
                        }

                    }
                }else{
                    return redirect()->back();
                }

                return redirect('list/stage_5');
                break;
                //Credit Risk Review
            case 'stage_6':

                $data = [
                    'request_by' => Auth::user()->id,
                    'approver_name' => user(Auth::user()->id)->first_name .' '. user(Auth::user()->id)->last_name,
                    'a_position' => getJobTitle(user(Auth::user()->id)->position_id)->title_name,
                    'approval_date' => date("Y-m-d H:i:s"),
                    'a_remark' => $request->comment_body,
                    'a_sig' => user(Auth::user()->id)->sig,
                    'a_stage_info' => 'stage_6'
                ];

                $update_data = LoanRequest::find($request->request_id);
                $request_id = $request->request_id;
                if($update_data->stage_info == 'stage_6' && (Auth::user()->role_id == 9 || Auth::user()->role_id == 14)){
                    $check_user_approvals = $update_data->whereHas('LoanApproval', function($q) use($request_id){
                    $q->where('request_id',$request_id)->where('request_by', Auth::user()->id)->where('a_stage_info','stage_6');
                    })->count();
                    if($check_user_approvals){
                        return redirect()->back();
                    }else{
                        $update_data->LoanApproval()->create($data);
                        $update_data->stage_info = 'stage_7';
                        $update_data->save();
                        Toastr::success('Reviewed successfully', 'Request Review');

                        $message = 'A new request pending loan origination. Click on URL to open LoanPortal App: http://localhost/staff_loan/home <br/><br/><br/><br/><br/><br/><br/>';
                        $subject = 'New Loan Origination';
                        sendMailGroup($subject, $message, 11);
                    }
                }else{
                    return redirect()->back();
                }

                return redirect('list/stage_6');
                break;
                //Disbursement Origination
            case 'stage_7':

                $data = [
                    'request_by' => Auth::user()->id,
                    'approver_name' => user(Auth::user()->id)->first_name .' '. user(Auth::user()->id)->last_name,
                    'a_position' => getJobTitle(user(Auth::user()->id)->position_id)->title_name,
                    'approval_date' => date("Y-m-d H:i:s"),
                    'a_remark' => $request->comment_body,
                    'a_sig' => user(Auth::user()->id)->sig,
                    'a_stage_info' => 'stage_7'
                ];

                $update_data = LoanRequest::find($request->request_id);
                $request_id = $request->request_id;
                if($update_data->stage_info == 'stage_7' && (Auth::user()->role_id == 11 || Auth::user()->role_id == 12)){
                    $check_user_approvals = $update_data->whereHas('LoanApproval', function($q) use($request_id){
                    $q->where('request_id',$request_id)->where('request_by', Auth::user()->id)->where('a_stage_info','stage_7');
                    })->count();
                    if($check_user_approvals){
                        return redirect()->back();
                    }else{
                        $update_data->LoanApproval()->create($data);
                        $update_data->stage_info = 'stage_8';
                        $update_data->save();
                        Toastr::success('Originated successfully', 'Loan Origination');

                        $message = 'New request pending loan origination review. Click on URL to open LoanPortal App: http://localhost/staff_loan/home <br/><br/><br/><br/><br/><br/><br/>';
                        $subject = 'New Loan Origination Review';
                        sendMailGroup($subject, $message, 14);
                    }
                }else{
                    return redirect()->back();
                }

                return redirect('list/stage_7');
                break;
                //Risk Origination Review
            case 'stage_8':

                $data = [
                    'request_by' => Auth::user()->id,
                    'approver_name' => user(Auth::user()->id)->first_name .' '. user(Auth::user()->id)->last_name,
                    'a_position' => getJobTitle(user(Auth::user()->id)->position_id)->title_name,
                    'approval_date' => date("Y-m-d H:i:s"),
                    'a_remark' => $request->comment_body,
                    'a_sig' => user(Auth::user()->id)->sig,
                    'a_stage_info' => 'stage_8'
                ];

                $update_data = LoanRequest::find($request->request_id);
                $request_id = $request->request_id;
                if($update_data->stage_info == 'stage_8' && (Auth::user()->role_id == 9 || Auth::user()->role_id == 14)){
                    $check_user_approvals = $update_data->whereHas('LoanApproval', function($q) use($request_id){
                    $q->where('request_id',$request_id)->where('request_by', Auth::user()->id)->where('a_stage_info','stage_8');
                    })->count();
                    if($check_user_approvals){
                        return redirect()->back();
                    }else{
                        $update_data->LoanApproval()->create($data);
                        $update_data->stage_info = 'stage_9';
                        $update_data->save();
                        Toastr::success('Reviewed successfully', 'Request Review');

                        $message = 'New request pending loan origination Approval. Click on URL to open LoanPortal App: http://localhost/staff_loan/home <br/><br/><br/><br/><br/><br/><br/>';
                        $subject = 'New Loan Origination Approval';
                        sendMailGroup($subject, $message, 12);
                    }
                }else{
                    return redirect()->back();
                }

                return redirect('list/stage_8');
                break;
                //CPU Manager\'s Approval
            case 'stage_9':

                $data = [
                    'request_by' => Auth::user()->id,
                    'approver_name' => user(Auth::user()->id)->first_name .' '. user(Auth::user()->id)->last_name,
                    'a_position' => getJobTitle(user(Auth::user()->id)->position_id)->title_name,
                    'approval_date' => date("Y-m-d H:i:s"),
                    'a_remark' => $request->comment_body,
                    'a_sig' => user(Auth::user()->id)->sig,
                    'a_stage_info' => 'stage_9'
                ];

                $update_data = LoanRequest::find($request->request_id);
                $request_id = $request->request_id;
                if($update_data->stage_info == 'stage_9' && (Auth::user()->role_id == 11 || Auth::user()->role_id == 12)){
                    $check_user_approvals = $update_data->whereHas('LoanApproval', function($q) use($request_id){
                    $q->where('request_id',$request_id)->where('request_by', Auth::user()->id)->where('a_stage_info','stage_9');
                    })->count();
                    if($check_user_approvals){
                        return redirect()->back();
                    }else{
                        $update_data->LoanApproval()->create($data);
                        $update_data->stage_info = 'stage_10';
                        $update_data->save();
                        Toastr::success('Approved successfully', 'Request Approval');

                        $message = 'New loan request pending disbursement. Click on URL to open LoanPortal App: http://localhost/staff_loan/home <br/><br/><br/><br/><br/><br/><br/>';
                        $subject = 'New Loan Disbursement';
                        sendMailGroup($subject, $message, 11);
                    }
                }else{
                    return redirect()->back();
                }

                return redirect('list/stage_9');
                break;
                //CPU Final Disbursement
            case 'stage_10':

                $data = [
                    'request_by' => Auth::user()->id,
                    'approver_name' => user(Auth::user()->id)->first_name .' '. user(Auth::user()->id)->last_name,
                    'a_position' => getJobTitle(user(Auth::user()->id)->position_id)->title_name,
                    'approval_date' => date("Y-m-d H:i:s"),
                    'a_remark' => $request->comment_body,
                    'a_sig' => user(Auth::user()->id)->sig,
                    'a_stage_info' => 'stage_10'
                ];

                $update_data = LoanRequest::find($request->request_id);
                $request_id = $request->request_id;
                if($update_data->stage_info == 'stage_10' && (Auth::user()->role_id == 11 || Auth::user()->role_id == 12)){
                    $check_user_approvals = $update_data->whereHas('LoanApproval', function($q) use($request_id){
                    $q->where('request_id',$request_id)->where('request_by', Auth::user()->id)->whereIn('a_stage_info',['stage_9','stage_10']);
                    })->count();
                    if($check_user_approvals){
                        return redirect()->back();
                    }else{
                        $update_data->LoanApproval()->create($data);
                        $update_data->stage_info = 'stage_11';
                        $update_data->status_flag = 'DISBURSED';
                        $update_data->save();
                        Toastr::success('Disbursed successfully', 'Loan Disbursement');

                        $message = 'Your loan has been disbursed. Click on URL to open LoanPortal App: http://localhost/staff_loan/home <br/><br/><br/><br/><br/><br/><br/>';
                        $subject = 'Loan Disbursement';
                        $to = $update_data->request_by;
                        sendMail($subject, $message, $to);
                    }
                }else{
                    return redirect()->back();
                }

                return redirect('list/stage_10');
                break;
            default:
                return redirect()->back();
        }





        // if($getRequestInfo->stage_info == 'stage_one')
        // {
        //     $getApprover = Approval::where('leave_request_id',$id)
        //                         ->where('user_id', Auth::user()->id)
        //                         ->where('status','Pending')->first();

        //     if(!empty($getApprover))
        //     {
        //         $updateApproval = Approval::find($getApprover->id);

        //         $updateApproval->approver_name = Auth::user()->first_name .' '. Auth::user()->last_name;
        //         $updateApproval->approval_date = date("Y-m-d H:i:s");
        //         $updateApproval->remark = 'APPROVED';
        //         $updateApproval->next_approver = $request->next_approver;
        //         $updateApproval->status = 'Approved';
        //         if($updateApproval->save())
        //         {
        //             $nextApproverPosition = user($request->next_approver)->position;
        //             if($nextApproverPosition == 'HR')
        //             {
        //                 $getRequestInfo->stage_info = 'stage_two';
        //                 $getRequestInfo->approved_by = $request->next_approver;
        //                 $getRequestInfo->save();
        //             }else{
        //                 $newApproval = new Approval;

        //                 $newApproval->leave_request_id = $updateApproval->leave_request_id;
        //                 $newApproval->user_id = $request->next_approver;
        //                 $newApproval->position = user($request->next_approver)->position;
        //                 $newApproval->status = 'Pending';
        //                 $newApproval->save();
        //             }

        //             Toastr::success('Approved Successfully', 'Approval');
        //             return redirect()->back();
        //         }
        //     }
        // }elseif ($getRequestInfo->stage_info == 'stage_two') {

        //     $getRequestInfo->stage_info = 'stage_three';
        //     $getRequestInfo->status_flag = 'APPROVED';
        //     $getRequestInfo->approved_by = Auth::user()->id;
        //     $getRequestInfo->approval_date = date("Y-m-d H:i:s");
        //     if($getRequestInfo->save()){

        //         $updateLeaveDay = LeaveDay::find($getRequestInfo->user_id);

        //         $updateLeaveDay->days_taken += $getRequestInfo->requested_days;
        //         $updateLeaveDay->outstanding_days -= $getRequestInfo->requested_days;
        //         $updateLeaveDay->save();

        //     }

        //     Toastr::success('Approved Successfully', 'Approval');
        //     return redirect()->back();

        // }

        return redirect()->back();
    }

    /**
        * Display the specified resource.
        *
        * @param  int  $id
        * @return Response
        */
    public function show($id)
    {
        $selectedRequest = LoanRequest::where('id',$id)->first();
        //$books = ::with('author', 'publisher')->get();


        return view('workspace.loan.details',['data'=>$selectedRequest]);
    }

    /**
        * Show the form for editing the specified resource.
        *
        * @param  int  $id
        * @return Response
        */
    public function edit($id)
    {
        //
    }

    /**
        * Update the specified resource in storage.
        *
        * @param  int  $id
        * @return Response
        */
    public function prePayment(Request $request, $updateType)
    {
        if($updateType == 'single'){
            $pre_payment = LoanRequest::find($request->request_id);
            if($pre_payment->stage_info == 'stage_4'){
                $pre_payment->stage_info = 'stage_5';
                $pre_payment->status_flag = 'DISBURSED';
                $pre_payment->payment_by = Auth::user()->id;
                $pre_payment->payment_date = date("Y-m-d H:i:s");
                $pre_payment->save();
                return redirect('list/stage_4');
            }
            
        }

        if($updateType == 'multiple'){
            $dateFrom = Carbon::parse($request->fromdate)->startOfDay();
            $dateTo = Carbon::parse($request->todate)->endOfDay();
            if($request->btnType == 'btnExport'){
                $btnNewLoan = 0;
                $btnAmend = 0;
                $btnExport = 1;
                $searchDisbursement = 1;

                

                $allRequest = LoanRequest::where('stage_info','stage_5')->where('branch_code',Auth::user()->branch_code)->whereBetween('approval_date', [$dateFrom,$dateTo])->get();

                return view('workspace.loan.list',['requestData'=>$allRequest,'btnNewLoan'=>$btnNewLoan,'btnAmend'=>$btnAmend,'payFrom'=>$request->payFrom,'payTo'=>$request->payTo,'searchDisbursement'=>$searchDisbursement,'btnExport'=>$btnExport]);
            }
            if($request->btnType == 'btnSearch'){
                $btnNewLoan = 0;
                $btnAmend = 0;
                $btnExport = 0;
                $searchDisbursement = 1;

                $allRequest = LoanRequest::where('stage_info','stage_4')->where('branch_code',Auth::user()->branch_code)->whereBetween('approval_date', [$dateFrom,$dateTo])->get();

                return view('workspace.loan.list',['requestData'=>$allRequest,'btnNewLoan'=>$btnNewLoan,'btnAmend'=>$btnAmend,'payFrom'=>$request->payFrom,'payTo'=>$request->payTo,'searchDisbursement'=>$searchDisbursement,'btnExport'=>$btnExport]);
            }
            if($request->btnType == 'btnDisbursement'){

                $pre_payment = LoanRequest::where('stage_info','stage_4')->where('branch_code',Auth::user()->branch_code)->whereBetween('approval_date', [$dateFrom,$dateTo])->get();

                foreach ($pre_payment as $record) {
                    $record->stage_info = 'stage_5';
                    $record->status_flag = 'DISBURSED';
                    $record->payment_by = Auth::user()->id;
                    $record->payment_date = date("Y-m-d H:i:s");
                    $record->save();
                }

                return redirect('list/stage_4');
            }
        }

        return redirect()->back();

    }

    public function hrUpdate(Request $request, $id){

        $fileFields = [ 'dd_attachment', 'affordability_doc', 'payslip_1', 'payslip_2', 'payslip_3', 'mandate_form' ]; 
        $data = [ 
            'disbursed_amount' => $request->disbursed_amount, 
            'monthly_installment' => $request->monthly_installment, 
            'account_number' => $request->account_number, 
            'customer_number' => $request->customer_number, 
            'mandate_number' => $request->mandate_number, 
            'mandate_pin' => $request->mandate_pin, 
            'tenure' => $request->tenure, 
            'rate' => $request->rate, 
            'processor_remark' => $request->processor_remark, 
            'processed_by' => Auth::user()->id, 
            'processed_date' => date("Y-m-d H:i:s") 
        ]; 

        if (!empty($request->otp_code) || $request->otp_code == "") {
            $data['otp_code'] = $request->otp_code;
            $data['otp_sent'] = 'YES';
            $data['otp_status'] = 'CONFIRMED';
        }

        foreach ($fileFields as $field) { 
            if ($request->hasFile($field)) { 
                $PATH = $request->file($field)->store('attachment'); 
                $data[$field] = $PATH; 
            } 
        }

        $update_data = LoanRequest::whereId($request->request_id)->update($data);

        return redirect()->back();

    }

    public function creditRiskUpdate(Request $request, $id){
        $data = [
            'interest_rate' => $request->interest_rate,
            'recommend_amount' => $request->recommend_amount,
            'monthly_installment' => $request->monthly_installment,
            'debt_ratio' => $request->debt_ratio,
            'pf_balance' => $request->pf_balance,
            'loan_tenor' => $request->loan_tenor,
            'guarantor_provided' => $request->guarantor_provided,
            'Pension_form_sined' => $request->Pension_form_sined,
            'existing_loan' => $request->existing_loan,
            'existing_loan_repayment' => $request->existing_loan_repayment,
            'existing_loan_info' => $request->existing_loan_info,
            'risk_review_remark' => $request->risk_review_remark,
            'risk_review_by' => Auth::user()->id,
            'risk_review_date' => date("Y-m-d H:i:s")
        ];

        $update_data = LoanRequest::whereId($request->risk_request_id)->update($data);

        return redirect()->back();

    }

    /**
        * Remove the specified resource from storage.
        *
        * @param  int  $id
        * @return Response
        */
    public function decline($id)
    {
        $getRequestInfo = LeaveRequest::find($id);

            $getRequestInfo->stage_info = 'stage_three';
            $getRequestInfo->status_flag = 'DECLINED';
            $getRequestInfo->declined_by = Auth::user()->id;
            $getRequestInfo->declined_date = date("Y-m-d H:i:s");
            $getRequestInfo->save();

            return redirect()->back();
    }

    public function changeBranch(Request $request, $newBranch='0000'){
        //return $request->user();
        $getBranchCode = DB::table('branches')->where('branch_code',$newBranch)->select('branch_code')->first();
        $getUser = DB::statement('UPDATE users SET branch_code = "'. strval($getBranchCode->branch_code) .'" WHERE id ='. Auth::user()->id);
        return redirect()->back();
        //return DB::connection('authdb')->table('users')->get();
    }

    public function changeRequestBranch(Request $request, $newBranch= 0){
    $getBranchCode = DB::table('branches')->where('branch_code',$newBranch)->select('branch_code')->first();
    $getUser = DB::statement('UPDATE requests SET branch_code = "'. strval($getBranchCode->branch_code) .'" WHERE id ='. $request->request_id);
    return redirect()->back();
    //return DB::connection('authdb')->table('users')->get();
    }

    private function sms($to,$otp){
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
    }
}

