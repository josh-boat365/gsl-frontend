<?php

namespace App\Http\Controllers;

use App\Models\LoanGuarantor;
use App\Models\LoanCustomer;
use App\Models\LoanRequest;
use App\Models\LoanApproval;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Toastr;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $loan_request = LoanRequest::where('request_by',Auth::user()->id)->get();
        $RequestInProcess = $loan_request->whereIn('status_flag','PROCESSING')->count();
        $TotalApproved = $loan_request->whereIn('status_flag','APPROVED')->count();
        $TotalDisbursed = $loan_request->where('status_flag','DISBURSED')->count();
        $TotalDeclined = $loan_request->where('status_flag','DECLINED')->count();


        $getLoanRequest = LoanRequest::where('branch_code',Auth::user()->branch_code)->get();
        $getRequestInProcess = $getLoanRequest->whereIn('status_flag','PROCESSING')->count();
        $getTotalApproved = $getLoanRequest->whereIn('status_flag','APPROVED')->count();
        $getTotalDisbursed = $getLoanRequest->where('status_flag','DISBURSED')->count();
        $getTotalDeclined = $getLoanRequest->where('status_flag','DECLINED')->count();

        $allRequest = LoanRequest::with('LoanCustomer')->where('request_by',Auth::user()->id)->orderBy('id','DESC')->skip(0)->take(1)->get();
        return view('home',
            [
                'user_request'=>$allRequest,
                'in_process'=>$RequestInProcess,
                'total_approved'=>$TotalApproved,
                'total_disbursed'=>$TotalDisbursed,
                'total_declined'=>$TotalDeclined,

                'get_in_process'=>$getRequestInProcess,
                'get_total_approved'=>$getTotalApproved,
                'get_total_disbursed'=>$getTotalDisbursed,
                'get_total_declined'=>$getTotalDeclined
            ]);
        /*Toastr::success('Messages in here', 'Title', ["positionClass" => "toast-top-center"]);*/
    }
}
