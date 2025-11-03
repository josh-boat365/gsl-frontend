<?php

namespace App\Http\Controllers;

use App\Models\LoanGuarantor;
use App\Models\LoanCustomer;
use App\Models\LoanRequest;
use App\Models\LoanApproval;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Fpdf;
use DateTime;
use NumberFormatter;

class DocPrinterController extends Controller
{
    public function printDisCet($id) {
        $get_request_info = LoanRequest::find($id);


        $approval_info = LoanApproval::where('loan_request_id',$id)->get();
        if(isset($approval_info)){

            //Credit Risk Recommend
            $risk_recommender = $approval_info->where('a_stage_info','stage_3')->first();
            if(isset($risk_recommender)){
                $risk_recommender_img= asset('storage/app/signature/' . $risk_recommender->a_sig);
            }else{
                $risk_recommender = '';
                $risk_recommender_img = '';
            }

            //Disbursement Origination
            $originator = $approval_info->where('a_stage_info','stage_7')->first();
            if(isset($originator)){
                $originator_img= asset('storage/app/signature/' . $originator->a_sig);
            }else{
                $originator = '';
                $originator_img = '';
            }

            //CPU Manager\'s Approval
            $disbursement_approver = $approval_info->where('a_stage_info','stage_9')->first();
            if(isset($disbursement_approver)){
                $disbursement_approver_img = asset('storage/app/signature/' . $disbursement_approver->a_sig);
            }else{
                $disbursement_approver = '';
                $disbursement_approver_img = '';
            }

            $disbursement = $approval_info->where('a_stage_info','stage_10')->first();
        }else{

            $disbursement = '';

        }

		$date = new DateTime('now');
		$date->modify( $get_request_info->loan_tenor.' month');
		$expiryDate = $date->format('d/m/Y');

// if (!empty($NewRequests[0]->processing_fee) && !empty($NewRequests[0]->approved_amount)) {
//         $processingFee = ($NewRequests[0]->processing_fee / 100) * $NewRequests[0]->approved_amount;
// }else{
// 	$processingFee = '';
// }

if (!empty($get_request_info->insurance_fee && !empty($get_request_info->recommend_amount))) {
        $InsuranceFee = ($get_request_info->insurance_fee / 100) * $get_request_info->recommend_amount;
}else{
	$InsuranceFee = '';
}
		$digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
		$tenorInWords = $digit->format($get_request_info->loan_tenor);



		Fpdf::AddPage();
		Fpdf::SetFont('Arial', 'B', 14);
		Fpdf::Cell(0, 5, 'BEST POINT SAVINGS AND LOANS LIMITED', 0, 2, 'C');
		Fpdf::SetFont('Arial', 'BU', 14);
		Fpdf::Cell(0, 5, 'FACILITY DISBURSEMENT CERTIFICATE', 0, 1, 'C');
		Fpdf::Ln(5);
		Fpdf::SetFont('Arial', 'B', 12);
		Fpdf::Cell(0, 10, 'ACCOUNT NAME : ' . $get_request_info->LoanCustomer->c_full_name , 'LTR', 2,'L');
		Fpdf::Cell(0, 10, 'ACCOUNT NUMBER : ' . $get_request_info->LoanCustomer->c_account_number , 'LR', 0,'L');
		Fpdf::Cell(0, 10, 'REF : ' . $get_request_info->ref_num , 'LR', 1,'R');
		Fpdf::Cell(0, 10, 'BRANCH : ' . getBranchName($get_request_info->branch_code)->branch_name, 'LB', 0,'L');
if (isset($get_request_info->approval_date)) {
	$newDateTime = new DateTime($get_request_info->approval_date);
	$approvalDate = $newDateTime->format('jS F Y');
		Fpdf::Cell(0, 10, 'DATE : ' . $approvalDate, 'BR', 1,'R');
}else{
		Fpdf::Cell(0, 10, 'DATE : ' . date('jS F Y'), 'BR', 1,'R');
}
		Fpdf::SetFont('Arial', 'B', 12);
		Fpdf::Cell(40, 10, 'FACILITY', 1, 0);
		Fpdf::Cell(35, 10, 'AMOUNT', 1, 0);
		Fpdf::Cell(40, 10, 'DATE APPROVED', 1, 0);
		Fpdf::Cell(35, 10, 'TENOR', 1, 0);
		Fpdf::Cell(40, 10, 'EXPIRY DATE', 1, 1);

		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(40, 15, $get_request_info->product_type, 1, 0);
		Fpdf::Cell(35, 15, $get_request_info->recommend_amount, 1, 0);
if (isset($get_request_info->approval_date)) {
	$newDateTime2 = new DateTime($get_request_info->approval_date);
	$approvalDate2 = $newDateTime2->format('d/m/Y');
		Fpdf::Cell(40, 15, $approvalDate2, 1, 0);
}else{
		Fpdf::Cell(40, 15, date("d/m/Y"), 1, 0);
}
		Fpdf::Cell(35, 15, $get_request_info->loan_tenor.' MONTHS', 1, 0);
if ($get_request_info->expiry_date1 !='' || $get_request_info->expiry_date1 != NULL) {
	$newExpiryDate = new DateTime($get_request_info->expiry_date1);
	$loanExpiryDate = $newExpiryDate->format('d/m/Y');
		Fpdf::Cell(40, 15, $loanExpiryDate, 1, 1);
}else{
		Fpdf::Cell(40, 15, $expiryDate, 1, 1);
}

		Fpdf::Cell(40, 15, 'Total', 1, 0);
		Fpdf::Cell(35, 15, $get_request_info->recommend_amount, 1, 0);
		Fpdf::Cell(40, 15, '', 1, 0);
		Fpdf::Cell(35, 15, '', 1, 0);
		Fpdf::Cell(40, 15, '', 1, 1);

		Fpdf::Ln(10);
		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 5, 'CHARGES', 0, 1, 'L');

		Fpdf::Ln();

		Fpdf::SetFont('Arial', 'B', 12);
		Fpdf::Cell(45, 10, 'Interest Rate (%) p.a', 1, 0);
		Fpdf::Cell(45, 10, 'Processing Fee (%)', 1, 0);
		Fpdf::Cell(50, 10, 'Insurance Fee (%)', 1, 0);
		Fpdf::Cell(40, 10, 'Penal Rate (%) p.a', 1, 1);

		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(45, 10, $get_request_info->interest_rate, 1, 0, 'C');
		Fpdf::Cell(45, 10, $get_request_info->processing_fee, 1, 0, 'C');
		Fpdf::Cell(50, 10, $get_request_info->insurance_fee, 1, 0, 'C');
		Fpdf::Cell(40, 10, '6', 1, 1, 'C');

		Fpdf::SetFont('Arial', 'B', 12);
		Fpdf::Cell(180, 10, 'Amount to Be Charged (GHS)', 1, 1);

		Fpdf::SetFont('Arial', 'B', 12);
		Fpdf::Cell(80, 10, 'Processing Fee', 1, 0, 'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(100, 10, '0.00', 1, 1, 'L');
		Fpdf::SetFont('Arial', 'B', 12);
		Fpdf::Cell(80, 10, 'Insurance Fee', 1, 0, 'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(100, 10, $InsuranceFee, 1, 1, 'L');
		Fpdf::SetFont('Arial', 'B', 12);
		Fpdf::Cell(80, 10, 'Total', 1, 0, 'L');
		Fpdf::Cell(100, 10, $InsuranceFee, 1, 1, 'L');

		Fpdf::Ln(10);
		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 5, 'BOOKING DETAILS', 0, 1, 'L');

		Fpdf::Ln();

		Fpdf::SetFont('Arial', 'B', 12);
		Fpdf::Cell(35, 10, 'Facility Type', 1, 0);
		Fpdf::Cell(30, 10, 'Ref. No.', 1, 0);
		Fpdf::Cell(30, 10, 'Title', 1, 0);
		Fpdf::Cell(40, 10, 'Name', 1, 0);
		Fpdf::Cell(30, 10, 'Signature', 1, 0);
		Fpdf::Cell(25, 10, 'Date', 1, 1);

		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(35, 30, $get_request_info->product_type, 1, 0);
		Fpdf::SetFont('Arial', '', 9);
		Fpdf::Cell(30, 30, $get_request_info->ref_num, 1, 0);
		Fpdf::SetFont('Arial', '', 10);
		Fpdf::Cell(30, 30, 'CPU OFFICER', 1, 0);
		Fpdf::SetFont('Arial', '', 10);
		Fpdf::Cell(40, 30, $originator != '' ? $originator->approver_name : '', 1, 0);
		Fpdf::SetFont('Arial', '', 10);
		Fpdf::Cell(30, 30, '', 1, 0);
if ($disbursement != '') {
	$newDisbursementDate = new DateTime($disbursement->approval_date);
	$disbursementDate = $newDisbursementDate->format('d/m/Y');
		Fpdf::Cell(25, 30, $disbursementDate, 1, 1);
		Fpdf::Image($originatorIMG, 144, 210, 30);
}else{
    Fpdf::Cell(25, 30, '', 1, 1);
}

		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(35, 30, $get_request_info->product_type, 1, 0);
		Fpdf::SetFont('Arial', '', 9);
		Fpdf::Cell(30, 30, $get_request_info->ref_num, 1, 0);
		Fpdf::SetFont('Arial', '', 10);
		Fpdf::Cell(30, 30, 'CPU MANAGER', 1, 0);
		Fpdf::SetFont('Arial', '', 10);
		Fpdf::Cell(40, 30, $disbursement_approver != '' ? $disbursement_approver->approver_name : '', 1, 0);
		Fpdf::SetFont('Arial', '', 10);
		Fpdf::Cell(30, 30, '', 1, 0);
if ($disbursement != '') {
	$newDisbursementAppDate = new DateTime($disbursement->approval_date);
	$disbursementAppDate = $newDisbursementAppDate->format('d/m/Y');
		Fpdf::Cell(25, 30, $disbursementAppDate, 1, 1);
		Fpdf::Image($disbursement_approver_img, 144, 240, 30);
}else{
		Fpdf::Cell(25, 30, '', 1, 1);
}
		Fpdf::AddPage('P');
		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 5, 'CHECKLIST', 0, 1, 'L');

		Fpdf::Ln();
		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 10, 'Loan Purpose:', 'LTR', 2,'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(0, 5, '		' . $get_request_info->loan_type, 'LR', 2,'L');

		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 10, 'Repayment Schedule:', 'LR', 2,'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(0, 5, '		' .ucfirst($tenorInWords). ' (' . $get_request_info->loan_tenor . ')  equal Monthly instalments of both principal and interest commencing thirty (30) days', 'LR', 2,'L');
		Fpdf::Cell(0, 5, '		 from the date of disbursement.', 'LR', 2,'L');

// if ($NewRequests[0]->loan_type == 'SUSU') {
// 		Fpdf::SetFont('Arial', 'BU', 12);
// 		Fpdf::Cell(0, 10, 'Pre- Disbursement Conditions', 'LR', 2,'L');
// 		Fpdf::SetFont('Arial', '', 12);
// 		Fpdf::Cell(0, 5, '		*    Duly accepted offer letter signed by the authorized signatories to the account of the borrower.', 'LR', 2,'L');
// 		Fpdf::Cell(0, 5, '		*    Upfront payment of the facility fees.', 'LR', 2,'L');
// 		Fpdf::Cell(0, 5, '		*    Third party Guarantee duly signed.', 'LR', 2,'L');

// 		Fpdf::SetFont('Arial', 'BU', 12);
// 		Fpdf::Cell(0, 10, 'Post- Disbursement Conditions:', 'LR', 2,'L');
// 		Fpdf::SetFont('Arial', '', 12);
// 		Fpdf::Cell(0, 5, '		Daily susu contribution of at least GHC '. $NewRequests[0]->average_daily_contribution, 'LR', 2,'L');

// 		Fpdf::SetFont('Arial', 'BU', 12);
// 		Fpdf::Cell(0, 10, 'Security:', 'LR', 2,'L');
// 		Fpdf::SetFont('Arial', '', 12);
// 		Fpdf::Cell(0, 5, '		Lien of GHS '. $NewRequests[0]->security_amount, 'LBR', 1,'L');
// }elseif ($NewRequests[0]->loan_type == 'SALARY') {

// 	if ($NewRequests[0]->product_type == 'GSL') {
// 		Fpdf::SetFont('Arial', 'BU', 12);
// 		Fpdf::Cell(0, 10, 'Pre- Disbursement Conditions', 'LR', 2,'L');
// 		Fpdf::SetFont('Arial', '', 12);
// 		Fpdf::Cell(0, 5, '		*    Acceptance / Execution of Best Point offer.', 'LR', 2,'L');
// 		Fpdf::Cell(0, 5, '		*    Completion of relevant document.', 'LR', 2,'L');

// 		Fpdf::SetFont('Arial', 'BU', 12);
// 		Fpdf::Cell(0, 10, 'Post- Disbursement Conditions:', 'LR', 2,'L');
// 		Fpdf::SetFont('Arial', '', 12);
// 		Fpdf::Cell(0, 5, '		N/A', 'LR', 2,'L');

// 		Fpdf::SetFont('Arial', 'BU', 12);
// 		Fpdf::Cell(0, 10, 'Caution:', 'LR', 2,'L');
// 		Fpdf::SetFont('Arial', '', 12);
// 		Fpdf::Cell(0, 5, '		Complete  disbursement certificate before disbursement.', 'LBR', 1,'L');
// 	}else{

		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 10, 'Pre- Disbursement Conditions', 'LR', 2,'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(0, 5, '		*    Duly accepted offer letter signed by the authorized signatories to the account of the borrower.', 'LR', 2,'L');
		Fpdf::Cell(0, 5, '		*    Duly signed third party guarantee.', 'LR', 2,'L');
		Fpdf::Cell(0, 5, '		*    Letter of undertaking signed by client\'s employer.', 'LR', 2,'L');
		Fpdf::Cell(0, 5, '		*    Third party guarantee contract.', 'LR', 2,'L');

		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 10, 'Post- Disbursement Conditions:', 'LR', 2,'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(0, 5, '		Monitoring client\'s account operation to ensure client\'s monthly salary continues to pass', 'LR', 2,'L');
		Fpdf::Cell(0, 5, '		through the account.', 'LR', 2,'L');

		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 10, 'Security:', 'LR', 2,'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(0, 5, '		Third party guarantee contract duly signed.', 'LBR', 1,'L');
// 	}

// }elseif ($NewRequests[0]->loan_type == 'CASH_BACKED') {
// 		Fpdf::SetFont('Arial', 'BU', 12);
// 		Fpdf::Cell(0, 10, 'Pre- Disbursement Conditions', 'LR', 2,'L');
// 		Fpdf::SetFont('Arial', '', 12);
// 		Fpdf::Cell(0, 5, '		*    Duly accepted offer letter signed by the authorized signatories to the account of the borrower.', 'LR', 2,'L');
// 		Fpdf::Cell(0, 5, '		*    Upfront payment of the facility fees.', 'LR', 2,'L');
// 		Fpdf::Cell(0, 5, '		*    Original Fixed Deposit Certificate to be kept at the branch.', 'LR', 2,'L');
// 		Fpdf::Cell(0, 5, '		*    FD to be set on auto roll over.', 'LR', 2,'L');

// 		Fpdf::SetFont('Arial', 'BU', 12);
// 		Fpdf::Cell(0, 10, 'Post- Disbursement Conditions:', 'LR', 2,'L');
// 		Fpdf::SetFont('Arial', '', 12);
// 		Fpdf::Cell(0, 5, '		N/A', 'LR', 2,'L');

// 		Fpdf::SetFont('Arial', 'BU', 12);
// 		Fpdf::Cell(0, 10, 'Security:', 'LR', 2,'L');
// 		Fpdf::SetFont('Arial', '', 12);
// 		Fpdf::Cell(0, 5, '		FD of GHS'. $NewRequests[0]->security_amount, 'LBR', 1,'L');
// }else{

// }

		Fpdf::Ln();

		Fpdf::SetFont('Arial', 'B', 12);
		Fpdf::Cell(80, 8, 'Collateral Execution', 1, 0);
		Fpdf::Cell(10, 8, 'Yes', 1, 0);
		Fpdf::Cell(10, 8, 'No', 1, 0);
		Fpdf::Cell(80, 8, 'Comments', 1, 1);

		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(80, 8, 'Site Visit/ Surveyor Confirmation', 1, 0, 'L');
		Fpdf::Cell(10, 8, '', 1, 0, 'L');
		Fpdf::Cell(10, 8, '', 1, 0, 'L');
		Fpdf::Cell(80, 8, '', 1, 1, 'L');

		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(80, 8, 'Title Search', 1, 0, 'L');
		Fpdf::Cell(10, 8, '', 1, 0, 'L');
		Fpdf::Cell(10, 8, '', 1, 0, 'L');
		Fpdf::Cell(80, 8, '', 1, 1, 'L');

		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(80, 8, 'Execution of Mortgage Deed', 1, 0, 'L');
		Fpdf::Cell(10, 8, '', 1, 0, 'L');
		Fpdf::Cell(10, 8, '', 1, 0, 'L');
		Fpdf::Cell(80, 8, '', 1, 1, 'L');

		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(80, 8, 'Confirmation of Vehicle Documents', 1, 0, 'L');
		Fpdf::Cell(10, 8, '', 1, 0, 'L');
		Fpdf::Cell(10, 8, '', 1, 0, 'L');
		Fpdf::Cell(80, 8, '', 1, 1, 'L');

		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(80, 8, 'Vehicle Valuation', 1, 0, 'L');
		Fpdf::Cell(10, 8, '', 1, 0, 'L');
		Fpdf::Cell(10, 8, '', 1, 0, 'L');
		Fpdf::Cell(80, 8, '', 1, 1, 'L');

		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(80, 8, 'Assignment of Ownership to Best Point', 1, 0, 'L');
		Fpdf::Cell(10, 8, '', 1, 0, 'L');
		Fpdf::Cell(10, 8, '', 1, 0, 'L');
		Fpdf::Cell(80, 8, '', 1, 1, 'L');

		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(80, 8, 'Registration of Mortgage', 1, 0, 'L');
		Fpdf::Cell(10, 8, '', 1, 0, 'L');
		Fpdf::Cell(10, 8, '', 1, 0, 'L');
		Fpdf::Cell(80, 8, '', 1, 1, 'L');

		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(80, 8, 'Other', 1, 0, 'L');
		Fpdf::Cell(10, 8, '', 1, 0, 'L');
		Fpdf::Cell(10, 8, '', 1, 0, 'L');
		Fpdf::Cell(80, 8, '', 1, 1, 'L');

		Fpdf::Cell(0, 20, '**Disbursement can go ahead after signing of mortgage Deed ..............................................', 0, 1, 'L');

		Fpdf::Cell(0, 10, 'Legal Department', 0, 1, 'L');

		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 5, 'APPROVAL', 0, 1, 'L');

		Fpdf::SetFont('Arial', 'B', 12);
		Fpdf::Cell(60, 10, 'DESIGNATION', 1, 0);
		Fpdf::Cell(60, 10, 'SIGNATURE', 1, 0);
		Fpdf::Cell(50, 10, 'DATE', 1, 1);

		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(60, 10, 'Credit Risk', 'LR', 0);
		Fpdf::Cell(60, 10, '', 'LR', 0);
		Fpdf::Cell(50, 10, '', 'LR', 1);

		Fpdf::SetFont('Arial', '', 10);
		Fpdf::Cell(60, 20, $risk_recommender != '' ? $risk_recommender->approver_name : '', 'LBR', 0);
		Fpdf::Cell(60, 20, '', 'LBR', 0);
		Fpdf::SetFont('Arial', '', 12);
if ($risk_recommender != '') {
	$newPushDate = new DateTime($risk_recommender->approval_date);
	$pushDate = $newPushDate->format('d/m/Y');
		Fpdf::Cell(50, 20, $pushDate, 'LBR', 1);
		Fpdf::Image($risk_recommender_img, 80, 245, 40);

}else{
		Fpdf::Cell(50, 20, '', 'LBR', 1);
}
		Fpdf::Output();
        exit;
    }


    public function printOfferLetter($id) {


// 		$NewRequests_susu = loan_request::join('customers','customers.id','=','loan_requests.customer_id')
//         ->Join('susu_loans','susu_loans.request_id','=','loan_requests.id')
//         ->where('loan_requests.id', $id)
//         ->select('susu_loans.*','customers.*','loan_requests.*')
//         ->get();

// $NewRequests_salary = loan_request::join('customers','customers.id','=','loan_requests.customer_id')
//         ->Join('salary_loans','salary_loans.request_id','=','loan_requests.id')
//         ->where('loan_requests.id', $id)
//         ->select('salary_loans.*','customers.*','loan_requests.*')
//         ->get();

// $NewRequests_cash_backed = loan_request::join('customers','customers.id','=','loan_requests.customer_id')
//         ->Join('cash_backed_loans','cash_backed_loans.request_id','=','loan_requests.id')
//         ->where('loan_requests.id', $id)
//         ->select('cash_backed_loans.*','customers.*','loan_requests.*')
//         ->get();

// $NewRequests = $NewRequests_susu->merge($NewRequests_salary)->merge($NewRequests_cash_backed);
// $customerFullName = $NewRequests[0]->first_name . ' ' . $NewRequests[0]->middle_name . ' ' . $NewRequests[0]->surname;

// $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
// $tenorInWords = $digit->format($NewRequests[0]->loan_tenor);



// if ($NewRequests[0]->officer_id) {
// $officerInfo = User::find($NewRequests[0]->officer_id);
// $officerFullName = $officerInfo->first_name . ' ' . $officerInfo->last_name;
// $officerSig = $officerInfo->sig;

// $getOfficerSig = asset('storage/signature/' . $officerSig);
// }else{
// $getOfficerSig = '';
// $officerInfo = '';
// $officerFullName = '';
// }


// $bmInfo = tb_approval::where('request_id',$id)
//                ->where('stage_info','stage_2')
//                ->first();

// if(is_null($bmInfo) || empty($bmInfo)){
// $puth = '';
// $bmFullName = '';
// }else{
// $bmFullName = $bmInfo->approver_name;
// $bmSig = $bmInfo->sig;

// $puth = asset('storage/signature/' . $bmSig);
// }


// if ($NewRequests[0]->loan_type == 'SUSU') {
// $loanType = 'Susu Loan';
// }elseif ($NewRequests[0]->loan_type == 'SALARY') {
// $loanType = 'Private Salary Loan';
// }elseif ($NewRequests[0]->loan_type == 'CASH_BACKED') {
// $loanType = 'Cash Backed Loan';
// }

        $get_request_info = LoanRequest::find($id);
//return $get_request_info->LoanCustomer->c_full_name;
        $approval_info = LoanApproval::where('loan_request_id',$id)->get();
        if(isset($approval_info)){

            //BM / HOD / Supervisor 1
            $supervisor = $approval_info->where('a_stage_info','stage_1')->first();
            if(isset($supervisor)){
                $supervisor_img= asset('storage/app/signature/' . $supervisor->a_sig);
            }else{
                $supervisor = '';
                $supervisor_img = '';
            }

            //Business Unit Update 5
            $business_unit = $approval_info->where('a_stage_info','stage_5')->first();
            if(isset($business_unit)){
                $business_unit_img= asset('storage/app/signature/' . $business_unit->a_sig);
            }else{
                $business_unit = '';
                $business_unit_img = '';
            }

            $disbursement = $approval_info->where('a_stage_info','stage_10')->first();
        }else{

            $disbursement = '';

        }

		$date = new DateTime('now');
		$date->modify( $get_request_info->loan_tenor.' month');
		$expiryDate = $date->format('d/m/Y');

        $dT = new DateTime($get_request_info->request_date);
        $requestDate = $dT->format('F jS, Y');


// if (!empty($NewRequests[0]->processing_fee) && !empty($NewRequests[0]->approved_amount)) {
//         $processingFee = ($NewRequests[0]->processing_fee / 100) * $NewRequests[0]->approved_amount;
// }else{
// 	$processingFee = '';
// }

if (!empty($get_request_info->insurance_fee && !empty($get_request_info->recommend_amount))) {
        $InsuranceFee = ($get_request_info->insurance_fee / 100) * $get_request_info->recommend_amount;
}else{
	$InsuranceFee = '';
}
		$digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
		$tenorInWords = $digit->format($get_request_info->loan_tenor);

Fpdf::AddPage();
Fpdf::SetFont('Arial', 'B', 12);
Fpdf::Ln(10);
Fpdf::Cell(0, 5, 'REF: '. $get_request_info->ref_num, 0, 2, 'L');
Fpdf::Ln();
Fpdf::Cell(0, 5, $get_request_info->LoanCustomer->c_full_name, 0, 1, 'L');
Fpdf::Cell(0, 5, $get_request_info->LoanCustomer->c_residential_address, 0, 2, 'L');

Fpdf::Ln();
Fpdf::Cell(0, 5, date_format(date_create($get_request_info->approval_date),'F jS, Y'), 0, 2, 'L');
Fpdf::Ln();
if ($get_request_info->LoanCustomer->c_gender == 'MALE') {
Fpdf::Cell(0, 5, 'Dear Sir,', 0, 2, 'L');
}elseif ($get_request_info->LoanCustomer->c_gender == 'FEMALE') {
Fpdf::Cell(0, 5, 'Dear Madam,', 0, 2, 'L');
}
Fpdf::Ln();
Fpdf::SetFont('Arial', 'BU', 12);
Fpdf::Cell(0, 5, 'CREDIT FACILITY - OFFER LETTER', 0, 2, 'L');
Fpdf::Ln();
Fpdf::SetFont('Arial', '', 12);
Fpdf::Cell(0, 5, 'Reference to your request dated '.$requestDate.'; we are pleased to advise that Best Point', 0, 2, 'L');
Fpdf::Cell(0, 5, 'Savings and Loans Ltd have approved your request for a credit facility under the following terms', 0, 2, 'L');
Fpdf::Cell(0, 5, 'and conditions:', 0, 2, 'L');
Fpdf::Ln();
Fpdf::Cell(50, 10, 'Lender', 0, 0, 'L');
Fpdf::SetFont('Arial', 'B', 12);
Fpdf::Cell(100, 10, ':  Best Point Savings and Loans Limited (Best Point)', 0, 1, 'L');

Fpdf::SetFont('Arial', '', 12);
Fpdf::Cell(50, 10, 'Borrower', 0, 0, 'L');
Fpdf::Cell(100, 10, ':  '.$get_request_info->LoanCustomer->c_full_name, 0, 1, 'L');

Fpdf::Cell(50, 10, 'Facility Type', 0, 0, 'L');
Fpdf::Cell(100, 10, ':  Short Term Loan ('.$get_request_info->product_type.')', 0, 1, 'L');


Fpdf::Cell(50, 10, 'Amount', 0, 0, 'L');
Fpdf::Cell(100, 10, ':  GHS '. $get_request_info->recommend_amount, 0, 1, 'L');

Fpdf::Cell(50, 10, 'Monthly installment', 0, 0, 'L');
Fpdf::Cell(100, 10, ':  GHS '. $get_request_info->monthly_installment, 0, 1, 'L');

Fpdf::Cell(50, 10, 'Purpose', 0, 0, 'L');
Fpdf::Cell(100, 10, ':  '. $get_request_info->loan_type, 0, 1, 'L');

Fpdf::Cell(50, 10, 'Duration', 0, 0, 'L');
Fpdf::Cell(100, 10, ':  '. ucfirst($tenorInWords) . ' ('.$get_request_info->loan_tenor.') Months from the date of disbursement', 0, 1, 'L');

Fpdf::SetFont('Arial', 'BU', 12);
Fpdf::Cell(50, 10, 'Pricing', 0, 1, 'L');
Fpdf::SetFont('Arial', '', 12);

Fpdf::Cell(50, 10, 'Processing Fees', 0, 0, 'L');
Fpdf::Cell(100, 10, ':  0% flat on the facility amount payable upfront', 0, 1, 'L');

Fpdf::Cell(50, 10, 'Interest Rate', 0, 0, 'L');
Fpdf::Cell(100, 10, ':  '. $get_request_info->interest_rate .'% per annum on straight line method. Interest shall continue to apply ', 0, 1, 'L');
Fpdf::Cell(50, 5, '', 0, 0, 'L');
Fpdf::Cell(100, 5, '  until the facility is fully repaid.', 0, 1, 'L');

Fpdf::Cell(50, 10, 'Insurance', 0, 0, 'L');
Fpdf::Cell(100, 10, ':  '.$get_request_info->insurance_fee.'% flat on the loan amounts as premium for insurance cover against', 0, 1, 'L');
Fpdf::Cell(50, 5, '', 0, 0, 'L');
Fpdf::Cell(100, 5, '    death and permanent disability disability payable upfront. The insurance', 0, 1, 'L');
Fpdf::Cell(50, 5, '', 0, 0, 'L');
Fpdf::Cell(100, 5, '   cover will only be for the period of the loan.', 0, 1, 'L');

// if ($NewRequests[0]->loan_type != 'SALARY') {

// Fpdf::Cell(50, 10, 'Penal Interest Rate', 0, 0, 'L');
// Fpdf::Cell(100, 10, ':  Any installment which is not funded on due date shall attract a penal', 0, 1, 'L');
// Fpdf::Cell(50, 5, '', 0, 0, 'L');
// Fpdf::Cell(100, 5, '    rate of 6 % per annum above the approved interest rate.', 0, 1, 'L');
// }

Fpdf::Cell(50, 10, 'Penal Interest Rate', 0, 0, 'L');
Fpdf::Cell(100, 10, ':  Any installment which is not funded on due date shall attract a penal', 0, 1, 'L');
Fpdf::Cell(50, 5, '', 0, 0, 'L');
Fpdf::Cell(100, 5, '    rate of 6 % per annum above the approved interest rate.', 0, 1, 'L');

Fpdf::AddPage('P');
Fpdf::SetFont('Arial', 'BU', 12);
Fpdf::Cell(50, 10, 'Commencement', 0, 0, 'L');
Fpdf::SetFont('Arial', '', 12);
Fpdf::Cell(100, 10, ':  The commencement date of the facility shall be the date of the ', 0, 1, 'L');
Fpdf::Cell(50, 5, '', 0, 0, 'L');
Fpdf::Cell(100, 5, '  disbursement of the facility by BestPoint to the Borrower\'s account.', 0, 1, 'L');

Fpdf::Ln(5);
Fpdf::SetFont('Arial', 'BU', 12);
Fpdf::Cell(50, 10, 'Mode of Repayment', 0, 1, 'L');

Fpdf::SetFont('Arial', 'B', 12);
Fpdf::Cell(50, 10, 'Primary Source', 0, 0, 'L');
Fpdf::SetFont('Arial', '', 12);
// if ($NewRequests[0]->loan_type == 'SUSU') {
// Fpdf::Cell(100, 10, ':  Proceed from the client\'s business operations channel through his ', 0, 1, 'L');
// Fpdf::Cell(50, 5, '', 0, 0, 'L');
// Fpdf::Cell(100, 5, '  account with Best Point.', 0, 1, 'L');
// }elseif ($NewRequests[0]->loan_type == 'SALARY') {
// if ($NewRequests[0]->product_type == 'GSL') {
// Fpdf::Cell(100, 10, ':  Deduction from controller and accountant general department', 0, 1, 'L');
// Fpdf::Cell(50, 5, '', 0, 0, 'L');
// Fpdf::Cell(100, 5, '   on monthly basis.', 0, 1, 'L');
// }else{
Fpdf::Cell(100, 10, ':  Monthly salary channeled through Best Point Account.', 0, 1, 'L');
Fpdf::Cell(100, 5, '', 0, 1, 'L');
// }

// }elseif ($NewRequests[0]->loan_type == 'CASH_BACKED') {
// Fpdf::Cell(100, 10, ':  FD of GHS '. $NewRequests[0]->security_amount, 0, 1, 'L');
// Fpdf::Cell(100, 5, '', 0, 1, 'L');
// }
Fpdf::Ln(5);
Fpdf::SetFont('Arial', 'B', 12);
Fpdf::Cell(50, 10, 'Repayment Schedule', 0, 0, 'L');
Fpdf::SetFont('Arial', '', 12);
Fpdf::Cell(100, 10, ':  '. ucfirst($tenorInWords) . ' ('.$get_request_info->loan_tenor.') equal monthly installment of both principal and interest ', 0, 1, 'L');
Fpdf::Cell(50, 5, '', 0, 0, 'L');
Fpdf::Cell(100, 5, '  Commencing thirty (30) days from date of disbursement.', 0, 1, 'L');

Fpdf::Ln(5);

// if ($NewRequests[0]->loan_type == 'SUSU') {
// Fpdf::SetFont('Arial', 'BU', 12);
// Fpdf::Cell(0, 10, 'Pre- Disbursement Conditions', 0, 2,'L');
// Fpdf::SetFont('Arial', '', 12);
// Fpdf::Cell(0, 10, '		*    Duly accepted offer letter signed by the authorized signatories to the account of the borrower.', 0, 2,'L');
// Fpdf::Cell(0, 10, '		*    Upfront payment of the facility fees.', 0, 2,'L');
// Fpdf::Cell(0, 10, '		*    Third party Guarantee duly signed.', 0, 2,'L');

// Fpdf::SetFont('Arial', 'BU', 12);
// Fpdf::Cell(0, 10, 'Post- Disbursement Conditions:', 0, 2,'L');
// Fpdf::SetFont('Arial', '', 12);
// Fpdf::Cell(0, 10, '		Daily susu contribution of at least GHC '. $NewRequests[0]->average_daily_contribution, 0, 2,'L');

// Fpdf::SetFont('Arial', 'BU', 12);
// Fpdf::Cell(0, 10, 'Security:', 0, 2,'L');
// Fpdf::SetFont('Arial', '', 12);
// Fpdf::Cell(0, 10, '		Lien of GHS '. $NewRequests[0]->security_amount, 0, 1,'L');
// }elseif ($NewRequests[0]->loan_type == 'SALARY') {
Fpdf::SetFont('Arial', 'BU', 12);
Fpdf::Cell(0, 10, 'Pre- Disbursement Conditions', 0, 2,'L');
Fpdf::SetFont('Arial', '', 12);
Fpdf::Cell(0, 10, '		*    Duly accepted offer letter signed by the authorized signatories to the account of the borrower.', 0, 2,'L');

Fpdf::SetFont('Arial', 'BU', 12);
Fpdf::Cell(0, 10, 'Post- Disbursement Conditions:', 0, 2,'L');
Fpdf::SetFont('Arial', '', 12);
Fpdf::Cell(0, 10, '		Monitoring client\'s account operation to ensure client\'s monthly salary continues to pass', 0, 2,'L');
Fpdf::Cell(0, 10, '		through the account.', 0, 2,'L');

// }elseif ($NewRequests[0]->loan_type == 'CASH_BACKED') {
// Fpdf::SetFont('Arial', 'BU', 12);
// Fpdf::Cell(0, 10, 'Pre- Disbursement Conditions', 0, 2,'L');
// Fpdf::SetFont('Arial', '', 12);
// Fpdf::Cell(0, 10, '		*    Duly accepted offer letter signed by the authorized signatories to the account of the borrower.', 0, 2,'L');
// Fpdf::Cell(0, 10, '		*    Upfront payment of the facility fees.', 0, 2,'L');
// Fpdf::Cell(0, 10, '		*    Original Fixed Deposit Certificate to be kept at the branch.', 0, 2,'L');
// Fpdf::Cell(0, 10, '		*    FD to be set on auto roll over.', 0, 2,'L');

// Fpdf::SetFont('Arial', 'BU', 12);
// Fpdf::Cell(0, 10, 'Post- Disbursement Conditions:', 0, 2,'L');
// Fpdf::SetFont('Arial', '', 12);
// Fpdf::Cell(0, 10, '		N/A', 0, 2,'L');

// Fpdf::SetFont('Arial', 'BU', 12);
// Fpdf::Cell(0, 10, 'Security:', 0, 2,'L');
// Fpdf::SetFont('Arial', '', 12);
// Fpdf::Cell(0, 10, '		FD of GHS'. $NewRequests[0]->security_amount, 0, 1,'L');
// }else{

// }

Fpdf::Ln(5);
Fpdf::SetFont('Arial', 'BU', 12);
Fpdf::Cell(0, 10, 'Other Terms and Condition', 0, 2,'L');
Fpdf::SetFont('Arial', '', 12);
Fpdf::Cell(0, 10, '		1.    In the event of default in repaying the facility thus necessitating legal action, the approved ', 0, 2,'L');
Fpdf::Cell(0, 10, '		interest rate shall continue to be applied to the facility even after judgment has been', 0, 2,'L');
Fpdf::Cell(0, 10, '		obtained by BestPoint against the Borrower and that interest on the judgment debt shall be', 0, 2,'L');
Fpdf::Cell(0, 10, '		compounded until final payment.', 0, 2,'L');

Fpdf::Cell(0, 10, '		2.    All charges in respect of legal documentation shall be for the account of the Borrower.', 0, 2,'L');
Fpdf::Cell(0, 10, '		3.    In the event that the terms and conditions of the facility have to be reviewed per your', 0, 2,'L');
Fpdf::Cell(0, 10, '		request, the review shall attract a minimum charge of 3% flat on the facility amount.', 0, 2,'L');
Fpdf::Cell(0, 10, '		4.	All administrative and insurance fees would be borne by the client.', 0, 1,'L');

Fpdf::AddPage('P');
Fpdf::Ln(10);
Fpdf::SetFont('Arial', 'BU', 12);
Fpdf::Cell(0, 10, 'Events of Default', 0, 2,'L');
Fpdf::SetFont('Arial', '', 12);
Fpdf::Cell(0, 10, 'The Principal amount, Interest, and other charges shall become due and payable immediately on ', 0, 2,'L');
Fpdf::Cell(0, 10, 'the occurrence of any one of the following events:', 0, 1,'L');
Fpdf::Cell(0, 10, '		i.	Where the facility is not used for its intended purpose.', 0, 1,'L');
Fpdf::Cell(0, 10, '		ii.	Where there is a material adverse change in the Borrower\'s business, management, and/or', 0, 1,'L');
Fpdf::Cell(0, 5, '				  financial condition.', 0, 1,'L');
Fpdf::Cell(0, 10, '		iii.	Non-compliance with any of the terms and conditions of the facility.', 0, 1,'L');
Fpdf::Cell(0, 10, '		iv.	Failure to pay off the facility when due or if called in.', 0, 1,'L');
Fpdf::Cell(0, 10, '		v.	Failure to pay or make any one installment payment of the loan.', 0, 1,'L');
Fpdf::Cell(0, 10, '		vi.	Where the Borrower is a party to any judicial or other administrative/arbitration', 0, 1,'L');
Fpdf::Cell(0, 5, '				  proceedings, which may adversely affect his operations, financial position or assets or', 0, 1,'L');
Fpdf::Cell(0, 5, '				  jeopardize the performance of his obligations under the terms and conditions of the facility.', 0, 1,'L');
Fpdf::Cell(0, 10, '		vii.	If the Borrower is unable to pay his debts or compromises with his creditors.', 0, 1,'L');

Fpdf::Ln(5);
Fpdf::Cell(0, 10, 'Please note that BestPoint reserves the right to amend or cancel the above terms and conditions', 0, 1,'L');
Fpdf::Cell(0, 10, 'upon advice to you.', 0, 1,'L');
Fpdf::Ln(5);
Fpdf::Cell(0, 10, 'By requirement, information on this facility shall be furnished from time to time to the Credit ', 0, 1,'L');
Fpdf::Cell(0, 10, 'Reference Bureau for their records and for ease of reference.', 0, 1,'L');
Fpdf::Ln(5);
Fpdf::Cell(0, 10, 'This offer shall lapse if not accepted within 30 days of the date of this letter.', 0, 1,'L');
Fpdf::Ln(5);
Fpdf::Cell(0, 10, 'Kindly confirm your agreement to the above terms and conditions by signing and returning to ', 0, 1,'L');
Fpdf::Cell(0, 10, 'BestPoint, the attached copy of this letter.', 0, 1,'L');
Fpdf::Ln(5);
Fpdf::Cell(0, 10, 'Yours faithfully,', 0, 1,'L');

Fpdf::Ln(10);

Fpdf::Cell(100, 10, '...............................', 0, 0, 'L');
Fpdf::Cell(100, 10, '', 0, 1, 'L');
Fpdf::Cell(100, 5, $business_unit != '' ? $business_unit->approver_name : '', 0, 0, 'L');
Fpdf::Cell(100, 5, $supervisor != '' ? $supervisor->approver_name : '', 0, 1, 'L');
Fpdf::Cell(100, 5, '(Business Development Officer)', 0, 0, 'L');
Fpdf::Cell(100, 5, '(Branch Manager / HOD / Supervisor)', 0, 1, 'L');

Fpdf::Image($supervisor_img != '' ? $supervisor_img : asset('storage/app/signature/empty.jpg'), 110, 235, 40);

Fpdf::Image($business_unit_img != '' ? $business_unit_img : asset('storage/app/signature/empty.jpg'), 10, 238, 40);

Fpdf::AddPage('P');
Fpdf::Ln(10);
Fpdf::SetFont('Arial', 'BU', 12);
Fpdf::Cell(0, 10, 'ACCEPTANCE', 0, 2,'L');
Fpdf::SetFont('Arial', '', 12);
Fpdf::Cell(0, 10, 'I accept fully the terms and conditions contained in this offer letter', 0, 1,'L');
Fpdf::SetFont('Arial', 'B', 12);
Fpdf::Cell(30, 15, 'Name', 0, 0,'L');
Fpdf::Cell(150, 15, ': ..........................................................................................', 0, 1,'L');
Fpdf::Cell(30, 15, 'Signature', 0, 0,'L');
Fpdf::Cell(150, 15, ': ..........................................................................................', 0, 1,'L');
Fpdf::Cell(30, 15, 'Date', 0, 0,'L');
Fpdf::Cell(150, 15, ': ..........................................................................................', 0, 1,'L');


Fpdf::Output();
exit;
    }

}
