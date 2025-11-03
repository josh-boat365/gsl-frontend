<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Fpdf;
use Toastr;
use App\Models\Branches;
use App\Models\tb_approval;
use App\Models\User;
use Auth;
use DateTime;
use NumberFormatter;

use App\Models\customer;
use App\Models\susu_loan;
use App\Models\salary_loan;
use App\Models\cash_backed_loan;
use App\Models\loan_request;
use Carbon\Carbon;

class ReportPrinter extends Controller
{
	

    public function printDisCet($id) {

		$users = User::all();
		
		$approvals = tb_approval::all();
		$NewRequests_susu = loan_request::join('customers','customers.id','=','loan_requests.customer_id')
						->Join('susu_loans','susu_loans.request_id','=','loan_requests.id')
						->where('loan_requests.id', $id)
						->select('susu_loans.*','customers.*','loan_requests.*')
						->get();

		$NewRequests_salary = loan_request::join('customers','customers.id','=','loan_requests.customer_id')
						->Join('salary_loans','salary_loans.request_id','=','loan_requests.id')
						->where('loan_requests.id', $id)
						->select('salary_loans.*','customers.*','loan_requests.*')
						->get();

		$NewRequests_cash_backed = loan_request::join('customers','customers.id','=','loan_requests.customer_id')
						->Join('cash_backed_loans','cash_backed_loans.request_id','=','loan_requests.id')
						->where('loan_requests.id', $id)
						->select('cash_backed_loans.*','customers.*','loan_requests.*')
						->get();

		$NewRequests = $NewRequests_susu->merge($NewRequests_salary)->merge($NewRequests_cash_backed);

		$branche = Branches::where('branch_code',$NewRequests[0]->branch_code)->get();

		$date = new DateTime('now');
		$date->modify( $NewRequests[0]->loan_tenor.' month');
		$expiryDate = $date->format('d/m/Y');

if (!empty($NewRequests[0]->processing_fee) && !empty($NewRequests[0]->approved_amount)) {
        $processingFee = ($NewRequests[0]->processing_fee / 100) * $NewRequests[0]->approved_amount;
}else{
	$processingFee = '';
}

if (!empty($NewRequests[0]->insurance_fee && !empty($NewRequests[0]->approved_amount))) {
        $InsuranceFee = ($NewRequests[0]->insurance_fee / 100) * $NewRequests[0]->approved_amount;
}else{
	$InsuranceFee = '';
}
		$digit = new NumberFormatter("en", NumberFormatter::SPELLOUT); 
		$tenorInWords = $digit->format($NewRequests[0]->loan_tenor);

//////////////////////////////////////////////////////////////////////////////////////
if ($NewRequests[0]->risk_review_by) {
		$creditRiskInfo = User::find($NewRequests[0]->risk_review_by);
		$creditRiskName = $creditRiskInfo->first_name .' '. $creditRiskInfo->last_name;
   		$creditRiskSig = $creditRiskInfo->sig;

   		$puth = asset('storage/signature/' . $creditRiskSig);
}else{
	$puth = '';
	$creditRiskInfo = '';
	$creditRiskName = '';
	$creditRiskSig = '';
}
		
//////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////
 if ($NewRequests[0]->disbursed_by) {
   		$cpuOfficerInfo = User::find($NewRequests[0]->disbursed_by);
   		$cpuOfficerName = $cpuOfficerInfo->first_name .' '. $cpuOfficerInfo->last_name;
   		$cpuOfficerSig = $cpuOfficerInfo->sig;

   		$cpuOfficerPuth = asset('storage/signature/' . $cpuOfficerSig);
 }else{
 	$cpuOfficerPuth = '';
 	$cpuOfficerInfo = '';
 	$cpuOfficerName = '';
 }

//////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////
if ($NewRequests[0]->disbursed_by) {
		$cpuMgrInfo = User::find($NewRequests[0]->disbursed_by);
		$cpuMgrName = $cpuMgrInfo->first_name .' '.$cpuMgrInfo->last_name;
   		$cpuMgrSig = $cpuMgrInfo->sig;

   		$cpuMgrPuth = asset('storage/signature/' . $cpuMgrSig);
}else{
	$cpuMgrPuth = '';
	$cpuMgrInfo = '';
	$cpuMgrName = '';
	$cpuMgrPuth = '';
}
   		
//////////////////////////////////////////////////////////////////////////////////////


		Fpdf::AddPage();
		Fpdf::SetFont('Arial', 'B', 14);
		Fpdf::Cell(0, 5, 'BEST POINT SAVINGS AND LOANS LIMITED', 0, 2, 'C');
		Fpdf::SetFont('Arial', 'BU', 14);
		Fpdf::Cell(0, 5, 'FACILITY DISBURSEMENT CERTIFICATE', 0, 1, 'C');
		Fpdf::Ln(5);
		Fpdf::SetFont('Arial', 'B', 12);
		Fpdf::Cell(0, 10, 'ACCOUNT NAME : ' . $NewRequests[0]->first_name . ' ' . $NewRequests[0]->middle_name . ' ' . $NewRequests[0]->surname , 'LTR', 2,'L');
		Fpdf::Cell(0, 10, 'ACCOUNT NUMBER : ' . $NewRequests[0]->customer_account , 'LR', 0,'L');
		Fpdf::Cell(0, 10, 'REF : ' . $NewRequests[0]->ref_num , 'LR', 1,'R');
		Fpdf::Cell(0, 10, 'BRANCH : ' . $branche[0]->branch_name, 'LB', 0,'L');
if ($NewRequests[0]->approval_date !='' || $NewRequests[0]->approval_date != NULL) {
	$newDateTime = new DateTime($NewRequests[0]->approval_date);
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
		Fpdf::Cell(40, 15, $NewRequests[0]->product_type, 1, 0);
		Fpdf::Cell(35, 15, $NewRequests[0]->approved_amount, 1, 0);
if ($NewRequests[0]->approval_date !='' || $NewRequests[0]->approval_date != NULL) {
	$newDateTime2 = new DateTime($NewRequests[0]->approval_date);
	$approvalDate2 = $newDateTime2->format('d/m/Y');
		Fpdf::Cell(40, 15, $approvalDate2, 1, 0);
}else{
		Fpdf::Cell(40, 15, date("d/m/Y"), 1, 0);
}
		Fpdf::Cell(35, 15, $NewRequests[0]->loan_tenor.' MONTHS', 1, 0);
if ($NewRequests[0]->expiry_date1 !='' || $NewRequests[0]->expiry_date1 != NULL) {
	$newExpiryDate = new DateTime($NewRequests[0]->expiry_date1);
	$loanExpiryDate = $newExpiryDate->format('d/m/Y');
		Fpdf::Cell(40, 15, $loanExpiryDate, 1, 1);
}else{
		Fpdf::Cell(40, 15, $expiryDate, 1, 1);
}

		Fpdf::Cell(40, 15, 'Total', 1, 0);
		Fpdf::Cell(35, 15, $NewRequests[0]->approved_amount, 1, 0);
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
	if ($NewRequests[0]->loan_type == 'SALARY') {
		Fpdf::Cell(40, 10, 'Early Settlement Fee', 1, 1);
	}else{
		Fpdf::Cell(40, 10, 'Penal Rate (%) p.a', 1, 1);
	}
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(45, 10, $NewRequests[0]->annual_interest_rate, 1, 0, 'C');
		Fpdf::Cell(45, 10, $NewRequests[0]->processing_fee, 1, 0, 'C');
		Fpdf::Cell(50, 10, $NewRequests[0]->insurance_fee, 1, 0, 'C');
	if ($NewRequests[0]->loan_type == 'SALARY') {
		Fpdf::Cell(40, 10, '0.25%', 1, 1, 'C');
	}else{
		Fpdf::Cell(40, 10, '6', 1, 1, 'C');
	}

		Fpdf::SetFont('Arial', 'B', 12);
		Fpdf::Cell(180, 10, 'Amount to Be Charged (GHS)', 1, 1);
		
		Fpdf::SetFont('Arial', 'B', 12);
		Fpdf::Cell(80, 10, 'Processing Fee', 1, 0, 'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(100, 10, $processingFee, 1, 1, 'L');
		Fpdf::SetFont('Arial', 'B', 12);
		Fpdf::Cell(80, 10, 'Insurance Fee', 1, 0, 'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(100, 10, $InsuranceFee, 1, 1, 'L');
		Fpdf::SetFont('Arial', 'B', 12);
		Fpdf::Cell(80, 10, 'Total', 1, 0, 'L');
		Fpdf::Cell(100, 10, $processingFee + $InsuranceFee, 1, 1, 'L');
		
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
		Fpdf::Cell(35, 30, $NewRequests[0]->product_type, 1, 0);
		Fpdf::SetFont('Arial', '', 9);
		Fpdf::Cell(30, 30, $NewRequests[0]->ref_num, 1, 0);
		Fpdf::SetFont('Arial', '', 10);
		Fpdf::Cell(30, 30, 'CPU OFFICER', 1, 0);
		Fpdf::SetFont('Arial', '', 10);
		Fpdf::Cell(40, 30, $cpuOfficerName, 1, 0);
		Fpdf::SetFont('Arial', '', 10);
		Fpdf::Cell(30, 30, '', 1, 0);
if ($NewRequests[0]->disbursement_date !='' || $NewRequests[0]->disbursement_date != NULL) {
	$newDisbursementDate = new DateTime($NewRequests[0]->disbursement_date);
	$disbursementDate = $newDisbursementDate->format('d/m/Y');
		Fpdf::Cell(25, 30, $disbursementDate, 1, 1);
		(!empty($cpuOfficerPuth)) ? Fpdf::Image($cpuOfficerPuth, 144, 210, 30) : "";
}else{
		Fpdf::Cell(25, 30, '', 1, 1);
}
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(35, 30, $NewRequests[0]->product_type, 1, 0);
		Fpdf::SetFont('Arial', '', 9);
		Fpdf::Cell(30, 30, $NewRequests[0]->ref_num, 1, 0);
		Fpdf::SetFont('Arial', '', 10);
		Fpdf::Cell(30, 30, 'CPU MANAGER', 1, 0);
		Fpdf::SetFont('Arial', '', 10);
		Fpdf::Cell(40, 30, $cpuMgrName, 1, 0);
		Fpdf::SetFont('Arial', '', 10);
		Fpdf::Cell(30, 30, '', 1, 0);
if ($NewRequests[0]->disbursed_app_date !='' || $NewRequests[0]->disbursed_app_date != NULL) {
	$newDisbursementAppDate = new DateTime($NewRequests[0]->disbursed_app_date);
	$disbursementAppDate = $newDisbursementAppDate->format('d/m/Y');
		Fpdf::Cell(25, 30, $disbursementAppDate, 1, 1);
		(!empty($cpuMgrPuth)) ? Fpdf::Image($cpuMgrPuth, 144, 240, 30) : "";
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
		Fpdf::Cell(0, 5, '		' . $NewRequests[0]->loan_purpose, 'LR', 2,'L');

		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 10, 'Repayment Schedule:', 'LR', 2,'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(0, 5, '		' .ucfirst($tenorInWords). ' (' . $NewRequests[0]->loan_tenor . ')  equal Monthly instalments of both principal and interest commencing thirty (30) days', 'LR', 2,'L');
		Fpdf::Cell(0, 5, '		 from the date of disbursement.', 'LR', 2,'L');

if ($NewRequests[0]->loan_type == 'SUSU') {
		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 10, 'Pre- Disbursement Conditions', 'LR', 2,'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(0, 5, '		*    Duly accepted offer letter signed by the authorized signatories to the account of the borrower.', 'LR', 2,'L');
		Fpdf::Cell(0, 5, '		*    Upfront payment of the facility fees.', 'LR', 2,'L');
		Fpdf::Cell(0, 5, '		*    Third party Guarantee duly signed.', 'LR', 2,'L');

		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 10, 'Post- Disbursement Conditions:', 'LR', 2,'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(0, 5, '		Daily susu contribution of at least GHC '. $NewRequests[0]->average_daily_contribution, 'LR', 2,'L');

		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 10, 'Security:', 'LR', 2,'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(0, 5, '		Lien of GHS '. $NewRequests[0]->security_amount, 'LBR', 1,'L');
}elseif ($NewRequests[0]->loan_type == 'SALARY') {

	if ($NewRequests[0]->product_type == 'GSL') {
		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 10, 'Pre- Disbursement Conditions', 'LR', 2,'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(0, 5, '		*    Acceptance / Execution of Best Point offer.', 'LR', 2,'L');
		Fpdf::Cell(0, 5, '		*    Completion of relevant document.', 'LR', 2,'L');

		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 10, 'Post- Disbursement Conditions:', 'LR', 2,'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(0, 5, '		N/A', 'LR', 2,'L');

		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 10, 'Caution:', 'LR', 2,'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(0, 5, '		Complete  disbursement certificate before disbursement.', 'LBR', 1,'L');
	}else{

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
	}
		
}elseif ($NewRequests[0]->loan_type == 'CASH_BACKED') {
		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 10, 'Pre- Disbursement Conditions', 'LR', 2,'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(0, 5, '		*    Duly accepted offer letter signed by the authorized signatories to the account of the borrower.', 'LR', 2,'L');
		Fpdf::Cell(0, 5, '		*    Upfront payment of the facility fees.', 'LR', 2,'L');
		Fpdf::Cell(0, 5, '		*    Original Fixed Deposit Certificate to be kept at the branch.', 'LR', 2,'L');
		Fpdf::Cell(0, 5, '		*    FD to be set on auto roll over.', 'LR', 2,'L');

		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 10, 'Post- Disbursement Conditions:', 'LR', 2,'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(0, 5, '		N/A', 'LR', 2,'L');

		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 10, 'Security:', 'LR', 2,'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(0, 5, '		FD of GHS'. $NewRequests[0]->security_amount, 'LBR', 1,'L');
}else{

}

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
		Fpdf::Cell(60, 20,$creditRiskName, 'LBR', 0);
		Fpdf::Cell(60, 20, '', 'LBR', 0);
		Fpdf::SetFont('Arial', '', 12);
if ($NewRequests[0]->push_to_branch_date !='' || $NewRequests[0]->push_to_branch_date != NULL) {
	$newPushDate = new DateTime($NewRequests[0]->push_to_branch_date);
	$pushDate = $newPushDate->format('d/m/Y');
		Fpdf::Cell(50, 20, $pushDate, 'LBR', 1);
		(!empty($puth)) ? Fpdf::Image($puth, 80, 239, 40) : "";
		
}else{
		Fpdf::Cell(50, 20, '', 'LBR', 1);
}
		Fpdf::Output();
		//Fpdf::Output();
	}

	public function printOfferLetter($id) {

		$NewRequests_susu = loan_request::join('customers','customers.id','=','loan_requests.customer_id')
						->Join('susu_loans','susu_loans.request_id','=','loan_requests.id')
						->where('loan_requests.id', $id)
						->select('susu_loans.*','customers.*','loan_requests.*')
						->get();

		$NewRequests_salary = loan_request::join('customers','customers.id','=','loan_requests.customer_id')
						->Join('salary_loans','salary_loans.request_id','=','loan_requests.id')
						->where('loan_requests.id', $id)
						->select('salary_loans.*','customers.*','loan_requests.*')
						->get();

		$NewRequests_cash_backed = loan_request::join('customers','customers.id','=','loan_requests.customer_id')
						->Join('cash_backed_loans','cash_backed_loans.request_id','=','loan_requests.id')
						->where('loan_requests.id', $id)
						->select('cash_backed_loans.*','customers.*','loan_requests.*')
						->get();

		$NewRequests = $NewRequests_susu->merge($NewRequests_salary)->merge($NewRequests_cash_backed);
		$customerFullName = $NewRequests[0]->first_name . ' ' . $NewRequests[0]->middle_name . ' ' . $NewRequests[0]->surname;

		$dT = new DateTime($NewRequests[0]->request_date);
		$requestDate = $dT->format('F jS, Y');

		$digit = new NumberFormatter("en", NumberFormatter::SPELLOUT); 
		$tenorInWords = $digit->format($NewRequests[0]->loan_tenor);

		

 if ($NewRequests[0]->officer_id) {
   		$officerInfo = User::find($NewRequests[0]->officer_id);
   		$officerFullName = $officerInfo->first_name . ' ' . $officerInfo->last_name;
   		$officerSig = $officerInfo->sig;

   		$getOfficerSig = asset('storage/signature/' . $officerSig);
 }else{
 	$getOfficerSig = '';
 	$officerInfo = '';
 	$officerFullName = '';
 }


   		$bmInfo = tb_approval::where('request_id',$id)
   							->where('stage_info','stage_2')
   							->first();

if(is_null($bmInfo) || empty($bmInfo)){
   		$puth = '';
   		$bmFullName = '';
}else{
		$bmFullName = $bmInfo->approver_name;
   		$bmSig = $bmInfo->sig;

   		$puth = asset('storage/signature/' . $bmSig);
}


if ($NewRequests[0]->loan_type == 'SUSU') {
	$loanType = 'Susu Loan';
}elseif ($NewRequests[0]->loan_type == 'SALARY') {
	$loanType = 'Private Salary Loan';
}elseif ($NewRequests[0]->loan_type == 'CASH_BACKED') {
	$loanType = 'Cash Backed Loan';
}

		Fpdf::AddPage();
		Fpdf::SetFont('Arial', 'B', 12);
		Fpdf::Ln(10);
		Fpdf::Cell(0, 5, 'REF: '. $NewRequests[0]->ref_num, 0, 2, 'L');
		Fpdf::Ln();
		Fpdf::Cell(0, 5, $customerFullName, 0, 1, 'L');
		Fpdf::Cell(0, 5, $NewRequests[0]->residential_land_mark, 0, 2, 'L');
		//Fpdf::Cell(0, 5, 'DIGITAL ADRES: GL-034-1977', 0, 2, 'L');
		Fpdf::Ln();
		Fpdf::Cell(0, 5, date_format(date_create($NewRequests[0]->approval_date),'F jS, Y'), 0, 2, 'L');
		Fpdf::Ln();
if ($NewRequests[0]->gender == 'MALE') {
		Fpdf::Cell(0, 5, 'Dear Sir,', 0, 2, 'L');
}elseif ($NewRequests[0]->gender == 'FEMALE') {
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
		Fpdf::Cell(100, 10, ':  '.$customerFullName, 0, 1, 'L');

		Fpdf::Cell(50, 10, 'Facility Type', 0, 0, 'L');
if ($NewRequests[0]->loan_type == 'SALARY') {
	if ($NewRequests[0]->product_type == 'GSL') {
		Fpdf::Cell(100, 10, ':  Short Term Loan ( GSL )', 0, 1, 'L');
	}else{
		Fpdf::Cell(100, 10, ':  Short Term Loan ('.$loanType.')', 0, 1, 'L');
	}
}else{
	Fpdf::Cell(100, 10, ':  Short Term Loan ('.$loanType.')', 0, 1, 'L');
}

		Fpdf::Cell(50, 10, 'Amount', 0, 0, 'L');
		Fpdf::Cell(100, 10, ':  GHS '. $NewRequests[0]->approved_amount, 0, 1, 'L');

		Fpdf::Cell(50, 10, 'Monthly installment', 0, 0, 'L');
		Fpdf::Cell(100, 10, ':  GHS '. $NewRequests[0]->monthly_installment, 0, 1, 'L');

		Fpdf::Cell(50, 10, 'Purpose', 0, 0, 'L');
		Fpdf::Cell(100, 10, ':  '. $NewRequests[0]->loan_purpose, 0, 1, 'L');

		Fpdf::Cell(50, 10, 'Duration', 0, 0, 'L');
		Fpdf::Cell(100, 10, ':  '. ucfirst($tenorInWords) . ' ('.$NewRequests[0]->loan_tenor.') Months from the date of disbursement', 0, 1, 'L');

		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(50, 10, 'Pricing', 0, 1, 'L');
		Fpdf::SetFont('Arial', '', 12);

		Fpdf::Cell(50, 10, 'Processing Fees', 0, 0, 'L');
		Fpdf::Cell(100, 10, ':  '. $NewRequests[0]->processing_fee .'% flat on the facility amount payable upfront', 0, 1, 'L');

		Fpdf::Cell(50, 10, 'Interest Rate', 0, 0, 'L');
		Fpdf::Cell(100, 10, ':  '. $NewRequests[0]->annual_interest_rate .'% per annum on straight line method. Interest shall continue to apply ', 0, 1, 'L');
		Fpdf::Cell(50, 5, '', 0, 0, 'L');
		Fpdf::Cell(100, 5, '  until the facility is fully repaid.', 0, 1, 'L');

		Fpdf::Cell(50, 10, 'Insurance', 0, 0, 'L');
		Fpdf::Cell(100, 10, ':  '.$NewRequests[0]->insurance_fee.'% flat on the loan amounts as premium for insurance cover against', 0, 1, 'L');
		Fpdf::Cell(50, 5, '', 0, 0, 'L');
		Fpdf::Cell(100, 5, '    death and permanent disability disability payable upfront. The insurance', 0, 1, 'L');
		Fpdf::Cell(50, 5, '', 0, 0, 'L');
		Fpdf::Cell(100, 5, '   cover will only be for the period of the loan.', 0, 1, 'L');

	if ($NewRequests[0]->loan_type != 'SALARY') {
	
		Fpdf::Cell(50, 10, 'Penal Interest Rate', 0, 0, 'L');
		Fpdf::Cell(100, 10, ':  Any installment which is not funded on due date shall attract a penal', 0, 1, 'L');
		Fpdf::Cell(50, 5, '', 0, 0, 'L');
		Fpdf::Cell(100, 5, '    rate of 6 % per annum above the approved interest rate.', 0, 1, 'L');
	}

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
if ($NewRequests[0]->loan_type == 'SUSU') {
		Fpdf::Cell(100, 10, ':  Proceed from the client\'s business operations channel through his ', 0, 1, 'L');
		Fpdf::Cell(50, 5, '', 0, 0, 'L');
		Fpdf::Cell(100, 5, '  account with Best Point.', 0, 1, 'L');
}elseif ($NewRequests[0]->loan_type == 'SALARY') {
	if ($NewRequests[0]->product_type == 'GSL') {
		Fpdf::Cell(100, 10, ':  Deduction from controller and accountant general department', 0, 1, 'L');
		Fpdf::Cell(50, 5, '', 0, 0, 'L');
		Fpdf::Cell(100, 5, '   on monthly basis.', 0, 1, 'L');
	}else{
		Fpdf::Cell(100, 10, ':  Monthly salary channeled through Best Point Account.', 0, 1, 'L');
		Fpdf::Cell(100, 5, '', 0, 1, 'L');
	}
		
}elseif ($NewRequests[0]->loan_type == 'CASH_BACKED') {
		Fpdf::Cell(100, 10, ':  FD of GHS '. $NewRequests[0]->security_amount, 0, 1, 'L');
		Fpdf::Cell(100, 5, '', 0, 1, 'L');
}
		Fpdf::Ln(5);
		Fpdf::SetFont('Arial', 'B', 12);
		Fpdf::Cell(50, 10, 'Repayment Schedule', 0, 0, 'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(100, 10, ':  '. ucfirst($tenorInWords) . ' ('.$NewRequests[0]->loan_tenor.') equal monthly installment of both principal and interest ', 0, 1, 'L');
		Fpdf::Cell(50, 5, '', 0, 0, 'L');
		Fpdf::Cell(100, 5, '  Commencing thirty (30) days from date of disbursement.', 0, 1, 'L');

		Fpdf::Ln(5);

if ($NewRequests[0]->loan_type == 'SUSU') {
		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 10, 'Pre- Disbursement Conditions', 0, 2,'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(0, 10, '		*    Duly accepted offer letter signed by the authorized signatories to the account of the borrower.', 0, 2,'L');
		Fpdf::Cell(0, 10, '		*    Upfront payment of the facility fees.', 0, 2,'L');
		Fpdf::Cell(0, 10, '		*    Third party Guarantee duly signed.', 0, 2,'L');

		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 10, 'Post- Disbursement Conditions:', 0, 2,'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(0, 10, '		Daily susu contribution of at least GHC '. $NewRequests[0]->average_daily_contribution, 0, 2,'L');

		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 10, 'Security:', 0, 2,'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(0, 10, '		Lien of GHS '. $NewRequests[0]->security_amount, 0, 1,'L');
}elseif ($NewRequests[0]->loan_type == 'SALARY') {
		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 10, 'Pre- Disbursement Conditions', 0, 2,'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(0, 10, '		*    Duly accepted offer letter signed by the authorized signatories to the account of the borrower.', 0, 2,'L');
		// Fpdf::Cell(0, 10, '		*    Duly signed third party guarantee.', 0, 2,'L');
		// Fpdf::Cell(0, 10, '		*    Letter of undertaking signed by client\'s employer.', 0, 2,'L');
		// Fpdf::Cell(0, 10, '		*    Third party guarantee contract.', 0, 2,'L');

		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 10, 'Post- Disbursement Conditions:', 0, 2,'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(0, 10, '		Monitoring client\'s account operation to ensure client\'s monthly salary continues to pass', 0, 2,'L');
		Fpdf::Cell(0, 10, '		through the account.', 0, 2,'L');

		// Fpdf::SetFont('Arial', 'BU', 12);
		// Fpdf::Cell(0, 10, 'Security:', 0, 2,'L');
		// Fpdf::SetFont('Arial', '', 12);
		// Fpdf::Cell(0, 10, '		Third party guarantee contract duly signed.', 0, 1,'L');
}elseif ($NewRequests[0]->loan_type == 'CASH_BACKED') {
		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 10, 'Pre- Disbursement Conditions', 0, 2,'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(0, 10, '		*    Duly accepted offer letter signed by the authorized signatories to the account of the borrower.', 0, 2,'L');
		Fpdf::Cell(0, 10, '		*    Upfront payment of the facility fees.', 0, 2,'L');
		Fpdf::Cell(0, 10, '		*    Original Fixed Deposit Certificate to be kept at the branch.', 0, 2,'L');
		Fpdf::Cell(0, 10, '		*    FD to be set on auto roll over.', 0, 2,'L');

		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 10, 'Post- Disbursement Conditions:', 0, 2,'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(0, 10, '		N/A', 0, 2,'L');

		Fpdf::SetFont('Arial', 'BU', 12);
		Fpdf::Cell(0, 10, 'Security:', 0, 2,'L');
		Fpdf::SetFont('Arial', '', 12);
		Fpdf::Cell(0, 10, '		FD of GHS'. $NewRequests[0]->security_amount, 0, 1,'L');
}else{

}

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
		Fpdf::Cell(100, 5, $officerFullName, 0, 0, 'L');
		Fpdf::Cell(100, 5, $bmFullName, 0, 1, 'L');
		Fpdf::Cell(100, 5, '(Business Development Officer)', 0, 0, 'L');
		Fpdf::Cell(100, 5, '(Branch Manager)', 0, 1, 'L');
if ($puth != '' || !empty($puth)) {
	(!empty($puth)) ? Fpdf::Image($puth, 110, 235, 40) : "";
		
}

if ($getOfficerSig != '' || !empty($getOfficerSig)) {
	(!empty($getOfficerSig)) ? Fpdf::Image($getOfficerSig, 10, 238, 40) : "";
		
}

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
	}

	public function catform($id){


		$users = User::all();
		
		$approvals = tb_approval::all();
		$NewRequests_susu = loan_request::join('customers','customers.id','=','loan_requests.customer_id')
						->Join('susu_loans','susu_loans.request_id','=','loan_requests.id')
						->where('loan_requests.id', $id)
						->select('susu_loans.*','customers.*','loan_requests.*')
						->get();

		$NewRequests_salary = loan_request::join('customers','customers.id','=','loan_requests.customer_id')
						->Join('salary_loans','salary_loans.request_id','=','loan_requests.id')
						->where('loan_requests.id', $id)
						->select('salary_loans.*','customers.*','loan_requests.*')
						->get();

		$NewRequests_cash_backed = loan_request::join('customers','customers.id','=','loan_requests.customer_id')
						->Join('cash_backed_loans','cash_backed_loans.request_id','=','loan_requests.id')
						->where('loan_requests.id', $id)
						->select('cash_backed_loans.*','customers.*','loan_requests.*')
						->get();

		$NewRequests = $NewRequests_susu->merge($NewRequests_salary)->merge($NewRequests_cash_backed);

		$branche = Branches::where('branch_code',$NewRequests[0]->branch_code)->get();

		$date = new DateTime('now');
		$date->modify( $NewRequests[0]->loan_tenor.' month');
		$expiryDate = $date->format('d/m/Y');

		$digit = new NumberFormatter("en", NumberFormatter::SPELLOUT); 
		$tenorInWords = $digit->format($NewRequests[0]->loan_tenor);

		$officerInfo = User::find($NewRequests[0]->officer_id);
   		$officerFullName = $officerInfo->first_name . ' ' . $officerInfo->last_name;

   		$dT = new DateTime($NewRequests[0]->request_date);
		$requestDate = $dT->format('F jS, Y');

		$customerFullName = $NewRequests[0]->first_name . ' ' . $NewRequests[0]->middle_name . ' ' . $NewRequests[0]->surname;

if ($NewRequests[0]->loan_type == 'SUSU') {
	$loanType = 'Susu Loan';
}elseif ($NewRequests[0]->loan_type == 'SALARY') {
	$loanType = 'Private Salary Loan';
}elseif ($NewRequests[0]->loan_type == 'CASH_BACKED') {
	$loanType = 'Cash Backed Loan';
}

/////////////////////////////////////////////////////////////////
$credMgrInfo = tb_approval::where('request_id',$id)
					->where('stage_info','stage_5')
					->first();

if(is_null($credMgrInfo) || empty($credMgrInfo)){
   		$getCredMgrSig = '';
   		$credMgrName = '';
   		$credMgrPosition = '';
   		$credMgrAppDate = '';
   		$credMgrSig = '';
}else{
		$credMgrName = $credMgrInfo->approver_name;
   		$credMgrPosition = $credMgrInfo->position;
   		$credMgrAppDate = $credMgrInfo->approval_date;
   		$credMgrSig = $credMgrInfo->sig;

   		$getCredMgrSig = asset('storage/signature/' . $credMgrSig);
}

///////////////////////////////////////////////////////////////////////////
$riskMgrInfo = tb_approval::where('request_id',$id)
					->where('stage_info','stage_6')
					->first();

if(is_null($riskMgrInfo) || empty($riskMgrInfo)){
   		$getRiskMgrSig = '';
   		$riskMgrName = '';
   		$riskMgrPosition = '';
   		$riskMgrAppDate = '';
   		$riskMgrRemark = '';
   		$riskMgrSig = '';
}else{
		$riskMgrName = $riskMgrInfo->approver_name;
   		$riskMgrPosition = $riskMgrInfo->position;
   		$riskMgrAppDate = $riskMgrInfo->approval_date;
   		$riskMgrRemark = $riskMgrInfo->remark;
   		$riskMgrSig = $riskMgrInfo->sig;

   		$getRiskMgrSig = asset('storage/signature/' . $riskMgrSig);
}
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////////
if ($NewRequests[0]->risk_review_by) {
		$creditRiskInfo = User::find($NewRequests[0]->risk_review_by);
   		$creditRiskSig = $creditRiskInfo->sig;

   		$puth = asset('storage/signature/' . $creditRiskSig);
}else{
	$puth = '';
}
		
//////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////
 if ($NewRequests[0]->disbursed_by) {
   		$cpuOfficerInfo = User::find($NewRequests[0]->disbursed_by);
   		$cpuOfficerSig = $cpuOfficerInfo->sig;

   		$cpuOfficerPuth = asset('storage/signature/' . $cpuOfficerSig);
 }else{
 	$cpuOfficerPuth = '';
 }

//////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////
if ($NewRequests[0]->disbursed_by) {
		$cpuMgrInfo = User::find($NewRequests[0]->disbursed_by);
   		$cpuMgrSig = $cpuMgrInfo->sig;

   		$cpuMgrPuth = asset('storage/signature/' . $cpuMgrSig);
}else{
	$cpuMgrPuth = '';
}
   		
//////////////////////////////////////////////////////////////////////////////////////
if (!empty($NewRequests[0]->processing_fee) && !empty($NewRequests[0]->approved_amount)) {
        $processingFee = ($NewRequests[0]->processing_fee / 100) * $NewRequests[0]->approved_amount;
}else{
	$processingFee = '';
}

if (!empty($NewRequests[0]->insurance_fee && !empty($NewRequests[0]->approved_amount))) {
        $InsuranceFee = ($NewRequests[0]->insurance_fee / 100) * $NewRequests[0]->approved_amount;
}else{
	$InsuranceFee = '';
}
		
        

        // $puth = url('public/assets/images/bestpointlogo.jpg');
        Fpdf::AddPage();
        // Fpdf::Image($puth, 90, 30, 80);
        // Fpdf::Cell(0,10,'',0,1);


        Fpdf::SetFont('Arial', 'B', 14);
        Fpdf::SetTextColor(25,25,25);
        Fpdf::Text(80, 12, 'CREDIT APPROVAL TICKET (CAT)', 50);
        Fpdf::Cell(0,4,'',0,1);

        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(20,10,'','',0,'L');
        Fpdf::Cell(40,10,'Branch : '.$branche[0]->branch_name,'',0,'L');
        Fpdf::Cell(80,10,'Name of Credit Officer : '.$officerFullName,'',0,'L');
        Fpdf::Cell(30,10,'Date : '.$requestDate,'',0,'L');
        Fpdf::Cell(20,10,'','',0,'L');
        Fpdf::Cell(20,10,'',0,1);
        
        // client Name
        Fpdf::Cell(20,10,'','',0,'L');
        Fpdf::Cell(40,10,'Loan Type : '.$loanType,'',0,'L');
        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(150,10,'Customer : '.$customerFullName,'',0,'L');
        Fpdf::Cell(20,10,'','',0,'L');
        Fpdf::Cell(20,5,'',0,1);


        //SPACE STARTS
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);
        //SPACE ENDS

        // Fpdf::SetFont('Arial', '', 11);
        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(190,5,'Management Credit Commitee Decision','LRT',0,'L');
        Fpdf::Cell(190,5,'',0,1);

        
        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(55,8,'Loan OD/Amount GHS '.$NewRequests[0]->recommend_amount,'L',0,'L');
        Fpdf::Cell(40,8,' Interest Rate: '.$NewRequests[0]->interest_rate.'%','',0,'L');
        Fpdf::Cell(47.5,8,'Rate Type: '.$NewRequests[0]->rate_type,'',0,'L');
        Fpdf::Cell(47.5,8,'Facility Fee: '.$NewRequests[0]->processing_fee.'%='.$processingFee ,'R',0,'L');
        Fpdf::Cell(47.5,8,'',0,1);


        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(55,8,'Insurance Fee: '.$NewRequests[0]->insurance_fee.'%='.$InsuranceFee,'L',0,'L');
        Fpdf::Cell(40,8,'Installment Amount GHS '.$NewRequests[0]->monthly_installment,'',0,'L');
        Fpdf::Cell(47.5,8,'','',0,'L');
        Fpdf::Cell(47.5,8,'Maturity (N0. of Months) : '.$NewRequests[0]->loan_tenor,'R',0,'L');
        Fpdf::Cell(47.5,8,'',0,1);
        
        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(40,8,'Frequency : '.$NewRequests[0]->frequency,'L',0,'L');
        Fpdf::Cell(40,8,'','',0,'L');
        Fpdf::Cell(57.5,8,'','',0,'L');
        Fpdf::Cell(52.5,8,'','R',0,'L');
        Fpdf::Cell(95,8,'',0,1);
        
        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(190,8,'Loan Purpose: '.$NewRequests[0]->loan_purpose,'RL',0,'L');
        Fpdf::Cell(190,8,'',0,1);




        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(30,8,'Collateral Pledge: ','L',0,'L');
        Fpdf::Cell(5,8,$NewRequests[0]->collateral_pledge,'',0,'L');
        Fpdf::Cell(155,8,'','R',0,'L');
        Fpdf::Cell(190,6,'',0,1);
        
        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(30,6,'','L',0,'L');
        Fpdf::Cell(5,6,'','',0,'L');
        Fpdf::Cell(155,6,'__________________________________________________________________________________','R',0,'L');
        Fpdf::Cell(190,6,'',0,1);
        
        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(30,6,'','L',0,'L');
        Fpdf::Cell(5,6,'','',0,'L');
        Fpdf::Cell(155,6,'__________________________________________________________________________________','R',0,'L');
        Fpdf::Cell(190,6,'',0,1);
        
        
        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(55,6,'','LB',0,'L');
        Fpdf::Cell(40,6,' ','B',0,'L');
        Fpdf::Cell(47.5,6,' ','B',0,'L');
        Fpdf::Cell(47.5,6,' ','RB',0,'L');
        Fpdf::Cell(47.5,6,'',0,1);
        
    
 //SPACE STARTS
        // Fpdf::SetFont('Arial', '', 11);
        // Fpdf::Cell(190,5,' ','',0,'L');
        // Fpdf::SetFont('Arial', '', 10);
        // Fpdf::Cell(190,5,'',0,1);
        //SPACE ENDS

        // Fpdf::SetFont('Arial', '', 11);
        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(190,5,'','LRT',0,'L');
        Fpdf::Cell(190,5,'',0,1);



        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(15,5,'Conditions : ','L',0,'L');
        Fpdf::Cell(175,5,'','R',0,'L');
        Fpdf::Cell(190,5,'',0,1);
        
        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(15,6,' ','L',0,'');
        Fpdf::Cell(175,6,$NewRequests[0]->conditions,'R',0,'L');
        Fpdf::Cell(190,6,'',0,1);
        
        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(15,6,' ','L',0,'');
        Fpdf::Cell(175,6,'__________________________________________________________________________________','R',0,'L');
        Fpdf::Cell(190,6,'',0,1);


        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(190,6,'Received and Recommended by Head, Credit & Business Development ','LR',0,'');
        Fpdf::Cell(190,6,'',0,1);


         Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(47.5,5,'Name ','L',0,'');
        Fpdf::Cell(47.5,5,'Position','',0,'');
        Fpdf::Cell(47.5,5,'Signature','',0,'');
        Fpdf::Cell(47.5,5,'Date','R',0,'');
        Fpdf::Cell(47.5,5,'',0,1);
        


        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(47.5,15,$credMgrName,'L',0,'');
        Fpdf::Cell(47.5,15,$credMgrPosition,'',0,'');
        Fpdf::Cell(47.5,15,'.................... ','',0,'');
if ($getCredMgrSig != '' || !empty($getCredMgrSig)) {
	(!empty($getCredMgrSig)) ? Fpdf::Image($getCredMgrSig, 99, 130, 30) : "";
		
}
        Fpdf::Cell(47.5,15,$credMgrAppDate,'R',0,'');
        Fpdf::Cell(47.5,15,'',0,1);




        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(55,6,'','L',0,'L');
        Fpdf::Cell(40,6,' ','',0,'L');
        Fpdf::Cell(47.5,6,' ','',0,'L');
        Fpdf::Cell(47.5,6,' ','R',0,'L');
        Fpdf::Cell(47.5,6,'',0,1);
        

        // Fpdf::SetFont('Arial', '', 11);
        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(190,5,'Credit Risk Management','LRB',0,'L');
        Fpdf::Cell(190,5,'',0,1);



        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(15,6,'Comments:','L',0,'L');
        Fpdf::Cell(175,6,'','R',0,'L');
        Fpdf::Cell(190,6,'',0,1);
        
        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(15,6,' ','L',0,'');
        Fpdf::Cell(175,6,$riskMgrRemark,'R',0,'L');
        Fpdf::Cell(190,6,'',0,1);
        
        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(15,6,' ','L',0,'');
        Fpdf::Cell(175,6,'__________________________________________________________________________________','R',0,'L');
        Fpdf::Cell(190,6,'',0,1);




         Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(90,4,'Name: ','L',0,'');
        Fpdf::Cell(50,4,'Signature:','',0,'');
        Fpdf::Cell(50,4,'Date:','R',0,'');
        Fpdf::Cell(30,4,'',0,1);
        


        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(90,15,$riskMgrName,'L',0,'');
        Fpdf::Cell(50,15,'..............','',0,'');
if ($getRiskMgrSig != '' || !empty($getRiskMgrSig)) {
	(!empty($getRiskMgrSig)) ? Fpdf::Image($getRiskMgrSig, 97, 178, 30) : "";
		
}
        Fpdf::Cell(50,15,$riskMgrAppDate,'R',0,'');
        Fpdf::Cell(30,15,'',0,1);




        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(55,6,'','LB',0,'L');
        Fpdf::Cell(40,6,' ','B',0,'L');
        Fpdf::Cell(47.5,6,' ','B',0,'L');
        Fpdf::Cell(47.5,6,' ','RB',0,'L');
        Fpdf::Cell(47.5,6,'',0,1);
        

    

 
        // Fpdf::SetFont('Arial', '', 11);
        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(190,5,'Committee Members','LR',0,'L');
        Fpdf::Cell(190,5,'',0,1);



        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(47.5,4,'Name: ','L',0,'');
        Fpdf::Cell(47.5,4,'Position: ','',0,'');
        Fpdf::Cell(47.5,4,'Signature:','',0,'');
        Fpdf::Cell(47.5,4,'Date:','R',0,'');
        Fpdf::Cell(47.5,4,'',0,1);   

        // LOOP HERE
    $mccInfos = tb_approval::where('request_id',$id)
				->where('stage_info','stage_7')
				->get();
if(!empty($mccInfos)){
$i = 206;
	foreach ($mccInfos as $mccInfo) {
		$mccSig = $mccInfo->sig;
		$getMCCSig = asset('storage/signature/' . $mccSig);
		Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(47.5,13,$mccInfo->approver_name,'L',0,'');
        Fpdf::Cell(47.5,13,$mccInfo->position,'',0,'');
        Fpdf::Cell(47.5,13,'....................','',0,'');
if ($getMCCSig != '' || !empty($getMCCSig)) {
	(!empty($getMCCSig)) ? Fpdf::Image($getMCCSig, 105, $i, 28) : "";
		
}
        Fpdf::Cell(47.5,13,$mccInfo->approval_date,'R',0,'');
        Fpdf::Cell(47.5,13,'',0,1);

        $i = $i + 13;
    }
}else{

        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(47.5,10,'.................... ','L',0,'');
        Fpdf::Cell(47.5,10,'.................... ','',0,'');
        Fpdf::Cell(47.5,10,'.................... ','',0,'');
        Fpdf::Cell(47.5,10,'.................... ','R',0,'');
        Fpdf::Cell(47.5,10,'',0,1);



        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(47.5,10,'.................... ','L',0,'');
        Fpdf::Cell(47.5,10,'.................... ','',0,'');
        Fpdf::Cell(47.5,10,'.................... ','',0,'');
        Fpdf::Cell(47.5,10,'.................... ','R',0,'');
        Fpdf::Cell(47.5,10,'',0,1);



        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(47.5,10,'.................... ','L',0,'');
        Fpdf::Cell(47.5,10,'.................... ','',0,'');
        Fpdf::Cell(47.5,10,'.................... ','',0,'');
        Fpdf::Cell(47.5,10,'.................... ','R',0,'');
        Fpdf::Cell(47.5,10,'',0,1);



        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(47.5,10,'.................... ','L',0,'');
        Fpdf::Cell(47.5,10,'.................... ','',0,'');
        Fpdf::Cell(47.5,10,'.................... ','',0,'');
        Fpdf::Cell(47.5,10,'.................... ','R',0,'');
        Fpdf::Cell(47.5,10,'',0,1);
}


        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(55,6,'','LB',0,'L');
        Fpdf::Cell(40,6,' ','B',0,'L');
        Fpdf::Cell(47.5,6,' ','B',0,'L');
        Fpdf::Cell(47.5,6,' ','RB',0,'L');
        Fpdf::Cell(47.5,6,'',0,1);
        

        Fpdf::Output();
	}

	public function susuForm($id=0){
		
		$approvals = tb_approval::all();
		$NewRequests_susu = loan_request::join('customers','customers.id','=','loan_requests.customer_id')
						->Join('susu_loans','susu_loans.request_id','=','loan_requests.id')
						->where('loan_requests.id', $id)
						->select('susu_loans.*','customers.*','loan_requests.*')
						->get();

		$NewRequests_salary = loan_request::join('customers','customers.id','=','loan_requests.customer_id')
						->Join('salary_loans','salary_loans.request_id','=','loan_requests.id')
						->where('loan_requests.id', $id)
						->select('salary_loans.*','customers.*','loan_requests.*')
						->get();

		$NewRequests_cash_backed = loan_request::join('customers','customers.id','=','loan_requests.customer_id')
						->Join('cash_backed_loans','cash_backed_loans.request_id','=','loan_requests.id')
						->where('loan_requests.id', $id)
						->select('cash_backed_loans.*','customers.*','loan_requests.*')
						->get();

		$NewRequests = $NewRequests_susu->merge($NewRequests_salary)->merge($NewRequests_cash_backed);

		$branche = Branches::where('branch_code',$NewRequests[0]->branch_code)->first();

		$customerInfo = customer::where('id',$NewRequests[0]->customer_id)->first();

		$customerAge = Carbon::parse($customerInfo->date_of_birth)->age;

		$date = new DateTime('now');
		$date->modify( $NewRequests[0]->loan_tenor.' month');
		$expiryDate = $date->format('d/m/Y');

		$processingFee = ($NewRequests[0]->processing_fee / 100) * $NewRequests[0]->approved_amount;
		$InsuranceFee = ($NewRequests[0]->insurance_fee / 100) * $NewRequests[0]->approved_amount;
		$digit = new NumberFormatter("en", NumberFormatter::SPELLOUT); 
		$tenorInWords = $digit->format($NewRequests[0]->loan_tenor);

		$officerInfo = User::find($NewRequests[0]->officer_id);
   		$officerFullName = $officerInfo->first_name . ' ' . $officerInfo->last_name;

   		$dT = new DateTime($NewRequests[0]->request_date);
		$requestDate = $dT->format('F jS, Y');

		$customerFullName = $NewRequests[0]->first_name . ' ' . $NewRequests[0]->middle_name . ' ' . $NewRequests[0]->surname;

		$processingFee = ($NewRequests[0]->processing_fee / 100) * $NewRequests[0]->approved_amount;
		$InsuranceFee = ($NewRequests[0]->insurance_fee / 100) * $NewRequests[0]->approved_amount;

if ($NewRequests[0]->loan_type == 'SUSU') {
	$loanType = 'Susu Loan';
}elseif ($NewRequests[0]->loan_type == 'SALARY') {
	$loanType = 'Private Salary Loan';
}elseif ($NewRequests[0]->loan_type == 'CASH_BACKED') {
	$loanType = 'Cash Backed Loan';
}

/////////////////////////////////////////////////////////////////
$bmInfo = tb_approval::where('request_id',$id)
					->where('stage_info','stage_2')
					->first();

if(is_null($bmInfo) || empty($bmInfo)){
   		$getBMSig = '';
   		$bmName = '';
   		$bmPosition = '';
   		$bmRemark = '';
   		$bmAppDate = '';
   		$bmSig = '';
}else{
		$bmName = $bmInfo->approver_name;
   		$bmPosition = $bmInfo->position;
   		$bmRemark = $bmInfo->remark;
   		$bmAppDate = $bmInfo->approval_date;
   		$bmSig = $bmInfo->sig;

   		$getBMSig = asset('storage/signature/' . $bmSig);
}

///////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////
if ($NewRequests[0]->officer_id) {
		$creditOfficerInfo = User::find($NewRequests[0]->officer_id);
		$creditOfficerName = $creditOfficerInfo->first_name .' '.$creditOfficerInfo->last_name;
   		$creditOfficerSig = $creditOfficerInfo->sig;

   		$getOfficerSig = asset('storage/signature/' . $creditOfficerSig);
}else{
	$getOfficerSig = '';
	$creditOfficerName = '';
}
		
//////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////
        

        $puth = url('public/img/logo/bpsl_logo.jpg');
        Fpdf::AddPage();
        (!empty($puth)) ? Fpdf::Image($puth, 90, 25, 80) : "";
        
        Fpdf::Cell(0,10,'',0,1);


        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::SetTextColor(25,25,25);
        Fpdf::Text(10, 70, 'BRANCH NAME: '.$branche->branch_name.'                    Account No: '. $customerInfo->customer_account ,50);
        Fpdf::Cell(0,10,'',0,1);
        Fpdf::Cell(0,10,'',0,1);
        Fpdf::Cell(0,10,'',0,1);

        Fpdf::SetFont('Arial', 'BU', 12);
        Fpdf::SetTextColor(25,25,25);
        Fpdf::Cell(30,10,'',0,10,'C'); //empty space to hel get to next line
        Fpdf::Text(57, 80, 'SUSU LOAN APPLICATION FORM', 1,10,'C');

        Fpdf::Cell(40,10,'',0,1);
        Fpdf::Cell(40,10,'',0,1);
        Fpdf::Cell(40,10,'',0,1);

        
        Fpdf::SetFont('Arial', 'B', 12,12);
        Fpdf::Text(10, 89, 'Borrower\'s Details', 1,20,'C');

        // Fpdf::SetFont('Arial', '', 9);
        // Fpdf::Cell(85,10,'Supporting materials attached to this request',1,0,'C');
        // Fpdf::Cell(105,10,'Banking details for refund',1,0,'C');
        // // Fpdf::Cell(65,5,'Banking details for refund',1,0,'C');
        // Fpdf::Cell(0,10,'',0,1);


        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(85,10,'Name:'.$customerInfo->title.' '.$customerInfo->first_name.' '.$customerInfo->middle_name.' '.$customerInfo->surname,'LRT',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(80,10,'Date Of Birth: '.$customerInfo->date_of_birth,'TB',0,'L');
        Fpdf::Cell(25,10,'Age: '.$customerAge,'TBR',0,'L');
        Fpdf::Cell(0,10,'',0,1);

        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(85,10,'TelNo: '.$customerInfo->phone_number,1,0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(40,10,'Gender : '.$customerInfo->gender,'TB',0,'L');
        Fpdf::Cell(65,10,'Marital Status : ','TBR',0,'L');
        Fpdf::Cell(0,10,'',0,1);

     


        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(85,10,'Current Residence / GPS Address: '.$customerInfo->digital_address,'LR',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(105,10,'Current Bus. Location: ','LR',0,'L');
        Fpdf::Cell(0,10,'',0,1); 

        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(85,10,$customerInfo->residential_address,'LR',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(105,10, $NewRequests[0]->business_location ,'LR',0,'L');
        Fpdf::Cell(0,10,'',0,1); 

        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(85,10,'Key Land Mark :','LR',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(105,10,'Key Land Mark :','LR',0,'L');
        Fpdf::Cell(0,10,'',0,1); 

        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(85,10,$customerInfo->residential_land_mark,'LR',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(105,10,$NewRequests[0]->business_land_mark,'LR',0,'L');
        Fpdf::Cell(0,10,'',0,1); 


        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(85,4,'Nature of Business :','TLR',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(105,4,'Occupation : ','TLR',0,'L');
        Fpdf::Cell(0,4,'',0,1); 



        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(85,20,$NewRequests[0]->nature_of_stock,'LBR',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(105,20,$customerInfo->occupation,'BR',0,'L');
        Fpdf::Cell(0,20,'',0,1); 


        Fpdf::SetFont('Arial', 'B', 12,12);
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(85,6,'','T',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(80,6,'','T',0,'L');
        Fpdf::Cell(25,6,'','T',0,'L');
        Fpdf::Cell(0,6,'',0,1);

        
        Fpdf::SetFont('Arial', 'B', 12,12);
        Fpdf::Cell(85,4,'Loan Details','B',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(80,4,'','B',0,'L');
        Fpdf::Cell(25,4,'','B',0,'R');
        Fpdf::Cell(0,4,'',0,1);
        Fpdf::SetFont('Arial', '', 11);

        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(85,5,'Amount Requested: GHS'.$NewRequests[0]->requested_amount,'LR',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(105,5,'Monthly interest rate (%)'.$NewRequests[0]->monthly_interest_rate,'LR',0,'L');
        Fpdf::Cell(0,5,'',0,1);
        
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(85,5,'','LR',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(105,5,'','LR',0,'L');
        Fpdf::Cell(0,5,'',0,1);

        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(85,5,'','RL',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(105,5,'Annual interest rate (%)'.$NewRequests[0]->annual_interest_rate,'LR',0,'L');
        Fpdf::Cell(0,5,'',0,1);


        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(85,5,'Loan Tenor ( Monthly) '.$NewRequests[0]->loan_tenor,'TLR',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(105,5,$NewRequests[0]->processing_fee.'% Upfront Processing Fees','TR',0,'L');
        Fpdf::Cell(0,5,'',0,1);
        
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(85,5,'','RL',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(105,5,$processingFee,'LR',0,'L');
        Fpdf::Cell(0,5,'',0,1);


        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(85,5,'','LR',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(105,5,'','LR',0,'L');
        Fpdf::Cell(0,5,'',0,1);


        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(85,5,'Proposed Monthly Installment Amount','TLR',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(105,5,$NewRequests[0]->insurance_fee.'% Loan insurance Fees','TRL',0,'L');
        Fpdf::Cell(0,5,'',0,1);
        
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(85,5,'GHS'.$NewRequests[0]->proposed_installment,'RL',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(105,5,$InsuranceFee,'LR',0,'L');
        Fpdf::Cell(0,5,'',0,1);

        // Fpdf::SetFont('Arial', '', 11);
        // Fpdf::Cell(85,5,'','RL',0,'L');
        // Fpdf::SetFont('Arial', '', 10);
        // Fpdf::Cell(105,5,'Annual interest rate (%) ............','R',0,'L');
        // Fpdf::Cell(0,5,'',0,1);

		Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(85,5,'Average Daily Susu Contribution:','TLR',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(105,5,'Amount Recommended: ','TRL',0,'L');
        Fpdf::Cell(0,5,'',0,1);
        
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(85,5,'','RL',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(105,5,'','LR',0,'L');
        Fpdf::Cell(0,5,'',0,1);

        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(85,5,'GHS'.$NewRequests[0]->average_daily_contribution,'RL',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(105,5,'GHS'.$NewRequests[0]->recommend_amount,'R',0,'L');
        Fpdf::Cell(0,5,'',0,1);

        // Fpdf::SetFont('Arial', 'B', 12,12);
        // Fpdf::Cell(85,4,'Loan Purpose','B',0,'L');
        // Fpdf::SetFont('Arial', '', 10);
        // Fpdf::Cell(80,4,'','B',0,'L');
        // Fpdf::Cell(25,4,'','B',0,'R');
        // Fpdf::Cell(0,4,'',0,1);
     

        // Fpdf::SetFont('Arial', '', 11);
        Fpdf::SetFont('Arial', 'B', 12,12);
        Fpdf::Cell(190,5,'Loan Purpose :','TLR',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(0,5,'',0,1); 



        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,10,$NewRequests[0]->loan_purpose,'LBR',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(0,10,'',0,1); 






        Fpdf::SetFont('Arial', 'B', 12,12);
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(85,6,'','T',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(80,6,'','T',0,'L');
        Fpdf::Cell(25,6,'','T',0,'L');
        Fpdf::Cell(0,6,'',0,1);

        
        Fpdf::SetFont('Arial', 'B', 12,12);
        Fpdf::Cell(85,4,'Security','B',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(80,4,'','B',0,'L');
        Fpdf::Cell(25,4,'','B',0,'L');
        Fpdf::Cell(0,4,'',0,1);
        Fpdf::SetFont('Arial', '', 9);

        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,'Security Type : '.$NewRequests[0]->security_type,1,0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);

        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,'Lien Amount : '.$NewRequests[0]->security_amount,1,0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);



        Fpdf::SetFont('Arial', '', 12,12);
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(85,6,'','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(80,6,'','',0,'L');
        Fpdf::Cell(25,6,'','',0,'L');
        Fpdf::Cell(0,6,'',0,1);

        
        Fpdf::SetFont('Arial', 'B', 12,12);
        Fpdf::Cell(85,4,'EXCEPTION TO CREDIT POLICY','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(80,4,'','',0,'L');
        Fpdf::Cell(25,4,'','',0,'L');
        Fpdf::Cell(0,4,'',0,1);
        Fpdf::SetFont('Arial', '', 9);

        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,$NewRequests[0]->policy_exception,'LRT',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);
        
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' ','LR',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);
        
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' ','LR',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);
        
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' ','LR',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);

        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,'','LRB',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);



        //SPACE STARTS
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);

        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);

        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);
        //SPACE ENDS


        Fpdf::SetFont('Arial', 'B', 12,12);
        Fpdf::Cell(85,4,'Value of Trading Stocks:','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(80,4,'','',0,'L');
        Fpdf::Cell(25,4,'','',0,'L');
        Fpdf::Cell(0,4,'',0,1);
        Fpdf::SetFont('Arial', '', 9);


        //SPACE STARTS
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);
        //SPACE ENDS

        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(140,5,'Nature of Stock ','LRTB',0,'L');
        Fpdf::Cell(50,5,'Amount (GHS) ','LRTB',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(95,5,'',0,1);
        
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(140,5,$NewRequests[0]->nature_of_stock,'LR',0,'L');
        Fpdf::Cell(50,5,'GHS'.$NewRequests[0]->stock_amount,'LR',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(95,5,'',0,1);
        

        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(140,5,'','LRB',0,'L');
        Fpdf::Cell(50,5,'','LRB',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(95,5,'',0,1);




         //SPACE STARTS
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);

        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);

        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);
        //SPACE ENDS


        Fpdf::SetFont('Arial', 'B', 12,12);
        Fpdf::Cell(85,4,'Declaration by Applicant:','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(80,4,'','',0,'L');
        Fpdf::Cell(25,4,'','',0,'L');
        Fpdf::Cell(0,4,'',0,1);
        Fpdf::SetFont('Arial', '', 9);


        //SPACE STARTS
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);
        //SPACE ENDS
        
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' I '.$customerInfo->title.' '.$customerInfo->first_name.' '.$customerInfo->middle_name.' '.$customerInfo->surname.' here by declare that the above information given by ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);


        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' me is true and accurate.','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);

        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);


        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' I also further consent that information on me and the facility obtained from Best Point','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);


        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' shall be made available to licensed Credit Bureaus in accordance with the Credit Reporting Act 2007 (Act726).','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);
        

        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);


        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,'Applicant Signature/Thumbprint........................................................ ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);


        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,'','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);

        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,'Date ....................................................................................... ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);

         //SPACE STARTS
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);

        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);
        //SPACE ENDS


        Fpdf::SetFont('Arial', 'B', 12,12);
        Fpdf::Cell(85,4,'OFFICE USE ONLY:','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(80,4,'','',0,'L');
        Fpdf::Cell(25,4,'','',0,'L');
        Fpdf::Cell(0,4,'',0,1);
        Fpdf::SetFont('Arial', '', 9);


        //SPACE STARTS
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);
        //SPACE ENDS
        
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' I .......................................................... (name of mobile banker) certify that  ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);


        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' Mr./Mrs./Miss............................................................ is a member of our Susu Scheme ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);

        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' and has been consistent in his/her contributions.I therefore support his/her loan request.','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);


        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' shall be made available to licensed Credit Bureaus in accordance with the Credit Reporting Act 2007 (Act726).','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);
        

        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);


        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,'SIGNATURE........................................................      DATE.....................................................','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);


        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,'','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);


        Fpdf::SetFont('Arial', '', 12,12);
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(85,6,'','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(80,6,'','',0,'L');
        Fpdf::Cell(25,6,'','',0,'L');
        Fpdf::Cell(0,6,'',0,1);


        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(10,5,' ','',0,'L');
        Fpdf::Cell(10,5,'2. ','',0,'L');
        Fpdf::Cell(170,5,'REMARKS BY SUSU CO-ORDINATOR ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);
        

        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(10,5,'','',0,'L');
        Fpdf::Cell(10,5,'','',0,'L');
        Fpdf::Cell(170,5,$NewRequests[0]->officer_remark,'',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);

         //SPACE STARTS
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);
        //SPACE ENDS


        //  Fpdf::SetFont('Arial', '', 11);
        // Fpdf::Cell(10,5,'','',0,'L');
        // Fpdf::Cell(10,5,'','',0,'L');
        // Fpdf::Cell(170,5,'Name.......................................  Signature.......................  Date......................','',0,'L');
        // Fpdf::SetFont('Arial', '', 10);
        // Fpdf::Cell(190,5,'',0,1);
        Fpdf::SetFont('Arial', '', 10.5);
        Fpdf::Cell(20,10,'','',0,'L');
        Fpdf::Cell(75,10,'Name : '.$creditOfficerName,'',0,'L');
if ($getOfficerSig != '' || !empty($getOfficerSig)) {
		(!empty($getOfficerSig)) ? Fpdf::Image($getOfficerSig, 122, 226, 30) : "";
		
}
        Fpdf::Cell(45,10,'Signature:','',0,'L');
        Fpdf::Cell(30,10,'Date : '.$NewRequests[0]->request_date,'',0,'L');
        Fpdf::Cell(20,10,'','',0,'L');
        Fpdf::Cell(20,10,'',0,1);


        Fpdf::SetFont('Arial', '', 12,12);
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(85,6,'','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(80,6,'','',0,'L');
        Fpdf::Cell(25,6,'','',0,'L');
        Fpdf::Cell(0,6,'',0,1);


        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(10,5,' ','',0,'L');
        Fpdf::Cell(10,5,'3. ','',0,'L');
        Fpdf::Cell(170,5,'BRANCH MANAGER\'S COMMEMTS','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);
        
        

        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(10,5,'','',0,'L');
        Fpdf::Cell(10,5,'','',0,'L');
        Fpdf::Cell(170,5,$bmRemark,'',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);


         //SPACE STARTS
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);
        //SPACE ENDS


        Fpdf::SetFont('Arial', '', 10.5);
        Fpdf::Cell(20,10,'','',0,'L');
        Fpdf::Cell(75,10,'Name : '.$bmName,'',0,'L');
	if ($getBMSig != '' || !empty($getBMSig)) {
			(!empty($getBMSig)) ? Fpdf::Image($getBMSig, 122, 257, 30) : "";
			
	}
        Fpdf::Cell(45,10,'Signature:','',0,'L');
        Fpdf::Cell(30,10,'Date : '.$bmAppDate,'',0,'L');
        Fpdf::Cell(20,10,'','',0,'L');
        Fpdf::Cell(20,10,'',0,1);


        Fpdf::Output();
	}



	public function printSalaryLoan(){

        $pathFile = storage_path(). '/test.pdf';
         $puth = url('public/assets/images/bestpointlogo.jpg');
        Fpdf::AddPage();
        Fpdf::SetFont('Arial', 'B', 9);
        Fpdf::Cell(190,120,'','TLR',0,'L'); //HEIGHT OF MAIN BOX INCREASE ANYTIME EMPTY SPACE APPEARS//
        Fpdf::Image($puth, 110, 20, 80);
         Fpdf::Cell(0,40,'',0,1);


        Fpdf::SetFont('Arial', 'B', 14);
        Fpdf::SetTextColor(25,25,25);
        Fpdf::Text(60, 60, 'SALARY LOAN APPLICATION FORM', 100);
        Fpdf::Cell(0,10,'',0,1);




        //SPACE STARTS
        Fpdf::SetFont('Arial', '', 11);
        Fpdf::Cell(190,5,' ','',0,'L');
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(190,5,'',0,1);
        //SPACE ENDS

        // Fpdf::SetFont('Arial', '', 11);
        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(2,7,'','',0,'');
        Fpdf::Cell(186,7,'Please add 1 new passport sized picture. Interest on this Facility will be charged for the duration of','LRT',0,'');
        Fpdf::Cell(190,7,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(2,5,'','',0,'');
        Fpdf::Cell(186,5,'this loan at BPSL’s Salary Loan rate in force when the loan commences:','LR',0,'');
        Fpdf::Cell(190,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(2,5,'','',0,'');
        Fpdf::Cell(186,5,'and is subject to change at BPSL’s option.  Loans are payable within a period of up to five (5) ','LR',0,'');
        Fpdf::Cell(190,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(2,7,'','',0,'');
        Fpdf::Cell(186,7,'years. (Terms and conditions however apply).','LRB',0,'');
        Fpdf::Cell(190,7,'',0,1);


        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(186,5,'','L',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(186,5,'1.  PERSONAL DETAILS ','L',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(186,5,'Title________ (Mr. Mrs. MS. Dr. Hon. Prof., etc)','L',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(93,5,'Surname_________________________','',0,'L');
        Fpdf::Cell(93,5,'Firstname_______________________','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(93,10,'Middle Name________ ','L',0,'L');
        Fpdf::Cell(48,10,'Gender________ Male ','',0,'');
        Fpdf::Cell(49,10,'Female ','R',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(93,10,'Maiden Name________ ','L',0,'L');
        Fpdf::Cell(48,10,'Date of Birth_________________ ','',0,'');
        Fpdf::Cell(49,10,'Age_________','R',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(63,10,'Nationality________ ','L',0,'L');
        Fpdf::Cell(68,10,'Country of Birth______________ ','',0,'');
        Fpdf::Cell(59,10,'Place of Birth_________','R',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(190,10,'Mailing Address: ________________________________________________________________','RL',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(190,10,'Residential Address: ____________________________________________________________','RL',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(190,10,'Key Land Mark: ________________________________________________________________','RL',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(190,10,'Residential Phone Number: _______________________________________________________','RL',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);



        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(190,10,'Mobile Number(s):  ______________________________________________________________','RL',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);



        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(190,10,'Email:  _______________________________________________________________________','RL',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);




        Fpdf::SetFont('Arial', 'U', 12);
        Fpdf::Cell(190,10,'Identification','RL',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(47.5,10,'Voters ID  ','L',0,'L');
        Fpdf::Cell(47.5,10,'Drivers License  ','',0,'L');
        Fpdf::Cell(47.5,10,'Passport  ','',0,'L');
        Fpdf::Cell(47.5,10,'NHIS Card  ','R',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(190,10,'Other (s) Specify:  ______________________________________________________________','RL',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);



        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(190,10,'ID No.:  _______________________________________________________________________','LR',0,'L');


        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::Cell(190,17,'','LRB',0,'L');
        Fpdf::Cell(47.5,17,'',0,1);




        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(190,260,'','TLRB',0,'L'); //HEIGHT OF MAIN BOX INCREASE ANYTIME EMPTY SPACE APPEARS//

        Fpdf::Cell(47.5,10,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(85,5,'Date of Issue: _________________________','',0,'L');
        Fpdf::Cell(85,5,'Expiry Date: _______________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Place of Issue: _________________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(85,5,'Marital Status: ______________________','',0,'L');
        Fpdf::Cell(85,5,'Name of Dependants: ______________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);



        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Name of Contact Person: _________________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Address: _________________________________________________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Phone Number: ___________________________________________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(85,5,'Residential Status:','',0,'L');
        Fpdf::Cell(85,5,'Educational Status:','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(85,5,'Own House','',0,'L');
        Fpdf::Cell(85,5,'Primary','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);



        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(85,5,'Tenant','',0,'L');
        Fpdf::Cell(85,5,'Secondary','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);



        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','L',0,'L');
        Fpdf::Cell(85,5,'With relatives','',0,'L');
        Fpdf::Cell(85,5,'Graduate','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','L',0,'L');
        Fpdf::Cell(85,5,'With Parents','',0,'L');
        Fpdf::Cell(85,5,'Post Graduate','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','L',0,'L');
        Fpdf::Cell(180,5,'Others (Specify) ___________________________________________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', '', 9);
        Fpdf::Cell(186,5,'','L',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(186,5,'2.  EMPLOYMENT DETAILS ','L',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Profession / Occupation: ___________________________________________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Name of Employer: ___________________________________________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Employers Location / Address: _____________________________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Unit: ___________________________________________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'District: ___________________________________________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Key Land Mark: ___________________________________________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Office Location: ___________________________________________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Key Land Mark: ___________________________________________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Length of Time with Employer: ___________________________________________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);



        //NEXT PAGE
        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(190,260,'','TLRB',0,'L'); //HEIGHT OF MAIN BOX INCREASE ANYTIME EMPTY SPACE APPEARS//

        Fpdf::Cell(47.5,10,'',0,1);

        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Bank Details: (for non-customers)','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,15,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Staff Number: _________________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Office Phone Number(s): _________________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Net Salary: _________________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'(Submit last three months pay slips)','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,20,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Name of Bank: _________________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);



        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(85,5,'Branch: ______________________','',0,'L');
        Fpdf::Cell(85,5,'Sort Code: ______________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Account Number','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,15,'',0,1);



        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Loan Details','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,15,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(85,5,'Loan Amount required GHS: ___________','',0,'L');
        Fpdf::Cell(85,5,'Loan Term Proposed (Months): ____________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Purpose of Loan: ______________________________________________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,15,'',0,1);

        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'CONFIRMATION by the Applicant / Beneficiary','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,15,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(85,5,'I, __________________________________','',0,'L');
        Fpdf::Cell(85,5,'Of ________________________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,7,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Hereby irrevocably authorize you to debit my account with the monthly installment of GHS_______ ','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,7,'',0,1);


Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'to the credit of my loan account on the ______ day of each month commencing on _______ and  ','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,7,'',0,1);


Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'to continue making such payments until a total sum of GHS________ (____________________)','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,7,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'(Excluding interest) is paid.  I hereby further declare that BPSL shall be entitled (as well before as  ','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,7,'',0,1);


  Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'after demand) to set off my liability under this loan against any credit balance in any account','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,7,'',0,1);


  Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'whatsoever in my name with BPSL if I default to pay off the loan.','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,7,'',0,1);


  Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Declare the above information provided to be true.  I agree and hereby undertake to abide by the','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,7,'',0,1);



Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'terms and conditions as indicated herein and in the overleaf pages. Any false presentation should ','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,7,'',0,1);



Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,' disqualify me.','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,7,'',0,1);



Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'I also agree that the terms of payment and interest calculation have been explained to me and ','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,7,'',0,1);



Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'I understand the terms perfectly.','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);


 //NEXT PAGE
        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(190,260,'','TLRB',0,'L'); //HEIGHT OF MAIN BOX INCREASE ANYTIME EMPTY SPACE APPEARS//

        Fpdf::Cell(47.5,10,'',0,1);




        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'I agree further and consent that information on me and the facilities obtained from BPSL shall be','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,7,'',0,1);


Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'made available to licensed Credit Bureaus in accordance with the Credit Reporting Act 2007','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,7,'',0,1);


Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'(Act726) and the Central Databank of the Ghana Association of Bankers and/or as required by law.','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,25,'',0,1);





        Fpdf::SetFont('Arial', 'B', 14);
        Fpdf::SetTextColor(25,25,25);
        Fpdf::Text(70, 55, 'TERMS AND CONDITIONS', 100);
        Fpdf::Cell(0,5,'',0,1);
//
//
        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(10,3,'o','',0,'R');
        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(170,5,'REPAYMENT OF THE LOAN, INCLUDING interest shall be made in accordance with the ','',0,'L');
        Fpdf::Cell(47.5,4,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(10,3,'','',0,'R');
        Fpdf::Cell(170,5,'payment schedule prepared by BPSL. ','',0,'L');
        Fpdf::Cell(47.5,7,'',0,1);


        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(10,3,'o','',0,'R');
        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(170,5,'The Beneficiary shall be deemed to be in default of payment of the loan in the ','',0,'L');
        Fpdf::Cell(47.5,4,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(10,3,'','',0,'R');
        Fpdf::Cell(170,5,'event of the occurrence of the following events:','',0,'L');
        Fpdf::Cell(47.5,7,'',0,1);


        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(10,3,'','',0,'R');
        Fpdf::Cell(10,3,'1.','',0,'R');
        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(160,5,'Where the beneficiary misses two consecutive monthly payments. ','',0,'L');
        Fpdf::Cell(47.5,4,'',0,1);

        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(10,3,'','',0,'R');
        Fpdf::Cell(10,3,'2.','',0,'R');
        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(160,5,'The death of the beneficiary before the completion of full payment of the loan.','',0,'L');
        Fpdf::Cell(47.5,7,'',0,1);

        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(10,3,'','',0,'R');
        Fpdf::Cell(10,3,'3.','',0,'R');
        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(160,5,'Failure to repay loan amount and accrued interest as and when any payment falls due.','',0,'L');
        Fpdf::Cell(47.5,17,'',0,1);



//GOVERNING LAW
        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'GOVERNING LAW','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,8,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'The Agreement shall be governed by and construed in accordance with the laws of the Republic','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'of Ghana and be subject to the exclusive jurisdiction of the law courts of Ghana.','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,20,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Signature of Applicant: _________________________________________________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,15,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(70,5,'DATED THIS  ____________','',0,'L');
        Fpdf::Cell(80,5,'DAY OF _____________','',0,'L');
        Fpdf::Cell(30,5,'20 _________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,7,'',0,1);


        Fpdf::Output('F', $pathFile);
        $headers = ['Content-Type' => 'application/pdf'];
        return response()->file($pathFile, $headers);
    }



    public function printCashBacked()
    {


        $pathFile = storage_path(). '/test.pdf';
        // $puth = url('public/assets/images/bestpointlogo.jpg');
        Fpdf::AddPage();
        // Fpdf::Image($puth, 90, 30, 80);
        // Fpdf::Cell(0,10,'',0,1);


        Fpdf::SetFont('Arial', 'B', 14);
        Fpdf::SetTextColor(25,25,25);
        Fpdf::Text(40, 12, 'BESTPOINT SAVINGS AND LOANS COMPANY LIMITED', 50);
        // Fpdf::Cell(0,4,'',0,1);

        Fpdf::SetFont('Arial', 'I', 14);
        Fpdf::Cell(20,10,'','',0,'L');
        Fpdf::Cell(40,10,'','',0,'L');
        Fpdf::Cell(80,10,'FACILITY REQUEST FORM','',0,'L');
        Fpdf::Cell(30,10,'','',0,'L');
        Fpdf::Cell(20,10,'','',0,'L');
        Fpdf::Cell(20,10,'',0,1);

        // client Name
        Fpdf::SetFont('Arial', 'I', 14);
        Fpdf::Cell(20,5,'','',0,'L');
        Fpdf::Cell(40,5,'','',0,'L');
        Fpdf::Cell(80,5,'FULLY CASH BACKED','',0,'L');
        Fpdf::Cell(30,5,'','',0,'L');
        Fpdf::Cell(20,5,'','',0,'L');
        Fpdf::Cell(20,5,'',0,1);

        // client Name

        Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(186,5,'1.  Business Data ','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Name of Customer:','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Account number:','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Account Opening Date:','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Branch: ','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Date Business Established/ Commencement (if applicable):','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Brief Profile of Directors/Shareholders (if applicable):','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,15,'',0,1);




        Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(186,5,'2. Details of Facility Request ','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Type Of Facility:','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Amount Required:','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
            Fpdf::Cell(180,5,'Purpose:','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Name and Address of the Beneficiary (if Facility requested is a Guarantee)____________ ','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'______________________________________________________________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Tenor:','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Repayment Source:','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Repayment Terms:','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,15,'',0,1);



        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Interest Rate:','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'NB: The applicable interest is subject to change at the discretion of the company in ','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'accordance with prevailing money market conditions. This interest rate is applicable  ','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);


        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'through court proceedings and after judgment.','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Processing Fee.','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,7,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Commission:____% payable in advance on the face value of the _______ (type of Guarantee) ','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'amount and _______% thereafter on a quarterly basis.','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(186,5,'3.  Existing Facility:','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(36,5,'Facility Type','TLRB',0,'C');
        Fpdf::Cell(36,5,'Approved Limit','TRB',0,'C');
        Fpdf::Cell(36,5,'Expiry Date','TRB',0,'C');
        Fpdf::Cell(43,5,'Outstanding Balance','TRB',0,'C');
        Fpdf::Cell(30,5,'Security','TRB',0,'C');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);


         Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(36,5,'1.','TLRB',0,'C');
        Fpdf::Cell(36,5,'{{DATA}}','TLRB',0,'C');
        Fpdf::Cell(36,5,'{{DATA}}','TLRB',0,'C');
        Fpdf::Cell(43,5,'{{DATA}}','TLRB',0,'C');
        Fpdf::Cell(30,5,'{{DATA}}','TLRB',0,'C');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(36,5,'2.','TLRB',0,'C');
        Fpdf::Cell(36,5,'{{DATA}}','TLRB',0,'C');
        Fpdf::Cell(36,5,'{{DATA}}','TLRB',0,'C');
        Fpdf::Cell(43,5,'{{DATA}}','TLRB',0,'C');
        Fpdf::Cell(30,5,'{{DATA}}','TLRB',0,'C');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(36,5,'','TLRB',0,'C');
        Fpdf::Cell(36,5,'{{DATA}}','TLRB',0,'C');
        Fpdf::Cell(36,5,'{{DATA}}','TLRB',0,'C');
        Fpdf::Cell(43,5,'{{DATA}}','TLRB',0,'C');
        Fpdf::Cell(30,5,'{{DATA}}','TLRB',0,'C');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);




        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');

        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);




        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');

        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');

        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);


        Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(186,5,'4. Security/Collateral','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Type of Security:','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Value of Security:','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Account Number:','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Name of Account/Security holder: ','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Loan to Value:','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);

        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'NB: The total exposure to be covered by cash collateral of 100% or Loan Amount shall','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'be 90% of Security Value.','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,15,'',0,1);



        Fpdf::SetFont('Arial', 'B', 14);
        Fpdf::SetTextColor(25,25,25);
        Fpdf::Text(40, 12, 'BESTPOINT SAVINGS AND LOANS COMPANY LIMITED', 50);
        // Fpdf::Cell(0,4,'',0,1);

        Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(186,5,'5. Right of Lien and Set Off','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'I/We, ________________in consideration of Best Point Savings and Loans Company Limited','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

      Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'having granted us a _____Loan_______________of __________(the "Facility") hereby assign ','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'the balance on our Fixed Deposit Investment worth__________________________________','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'(Amount in words) to Best Point Savings and Loans Company Limited security for the Facility. ','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'I/We____________________agree that the Company reserves the right to extend this Right of ','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Lien and Set Off to all other accounts owned by us with the Company and is at liberty to  ','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'exercise the rights hereby granted at anytime during the term or upon maturity of the Facility.','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(186,5,'6. CONDITIONS PRECEDENT TO DRAWDOWN','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'The facility will become available to the Borrower once BestPoint Savings and Loans Company','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

      Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Limited have received payment of all upfront fees and commissions.','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


 Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(186,5,'7. OTHER CONDITIONS ','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'(a)  All legal expenses (including cost of recovery in case of default) incidental to this transaction ','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

      Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,15,'','',0,'L');
        Fpdf::Cell(160,5,'Limited have received payment of all upfront fees and commissions.','',0,'L');
        Fpdf::Cell(5,15,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);


 Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'(b)  Reach global audiences instantly through our messaging platform','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'(c)  Early repayment of the facility shall not attract any penalty','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);


        Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(186,5,'8.  EVENTS OF DEFAULT','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'In the event of default on the repayment terms of the Facility the Company reserves the right to ','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,15,'','',0,'L');
        Fpdf::Cell(160,5,'call in the Facility upon the giving of seven days (7) days notice in writing to the Borrower and ','',0,'L');
        Fpdf::Cell(5,15,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);


        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'thereafter offset the Borrower’s overdue balance with the sum charged as security under the ','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(180,5,'Facility.','',0,'L');
        Fpdf::Cell(5,5,'','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(190,5,'Please confirm your agreement to the terms and conditions of this letter by counter-signing .','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(190,5,'and dating the enclosed copy and returning it to us not later than 30 days from the date of ','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(190,5,'this offer, after which date this offer expires.','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(190,5,'Yours faithfully,','',0,'L');
        Fpdf::Cell(47.5,25,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(190,5,'_______________________________','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', 'BU', 12);
        Fpdf::Cell(190,5,'MEMORANDUM OF ACCEPTANCE (BOARD RESOLUTION TO BE SUBMITTED WHERE','',0,'L');
        Fpdf::Cell(47.5,5,'',0,1);

        Fpdf::SetFont('Arial', 'BU', 12);
        Fpdf::Cell(190,5,'BORROWER IS A LIMITED LIABILITY ENTITY)','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);

        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(190,5,'SIGNED BY ______________________IN ACCEPTANCE OF THE OFFER ','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


        Fpdf::SetFont('Arial', 'B', 12);
        Fpdf::Cell(190,5,'FOR AND ON BEHALF OF (IF APPLICABLE)_______________________','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);

        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(190,5,'In the presence of:','',0,'L');
        Fpdf::Cell(47.5,15,'',0,1);


  Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(190,5,'Signature:______________________','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


  Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(190,5,'Name:___________________________','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


  Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(190,5,'Title:___________________________','',0,'L');
        Fpdf::Cell(47.5,20,'',0,1);




        Fpdf::SetFont('Arial', 'B', 14);
        Fpdf::SetTextColor(25,25,25);
        Fpdf::Text(40, 20, 'BESTPOINT SAVINGS AND LOANS COMPANY LIMITED', 50);
        // Fpdf::Cell(0,4,'',0,1);



        Fpdf::SetFont('Arial', '', 12);
        Fpdf::Cell(190,5,'Date:__________________________________','',0,'L');
        Fpdf::Cell(47.5,20,'',0,1);



        Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(30,5,'','',0,'L');
        Fpdf::Cell(160,5,'1.  For Company use only','',0,'L');
        Fpdf::Cell(47.5,4,'',0,1);

        Fpdf::SetFont('Arial', 'U', 10);
        Fpdf::Cell(35,5,'','',0,'L');
        Fpdf::Cell(155,5,'(a)  Branch ','',0,'L');
        Fpdf::Cell(47.5,15,'',0,1);


        Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(35,5,'','',0,'L');
        Fpdf::Cell(155,5,'Signature verification by CO/CSO____________________________________','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);

        Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(35,5,'','',0,'L');
        Fpdf::Cell(155,5,'(b)Approved for Disbursement','',0,'L');
        Fpdf::Cell(47.5,15,'',0,1);

        Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(35,5,'','',0,'L');
        Fpdf::Cell(155,5,' Branch Manager (Name):_____________________Signature/Date____________________','',0,'L');
        Fpdf::Cell(47.5,15,'',0,1);



        Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(35,5,'','',0,'L');
        Fpdf::Cell(155,5,'(c)Exceptional Approval','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


  Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(35,5,'','',0,'L');
        Fpdf::Cell(155,5,'Head,Credit and Marketing Name):_____________________Signature/Date______________','',0,'L');
        Fpdf::Cell(47.5,15,'',0,1);



 Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(35,5,'','',0,'L');
        Fpdf::Cell(155,5,'Head, Finance&Administration(Name):_________________Signature/Date____________________','',0,'L');
        Fpdf::Cell(47.5,15,'',0,1);



Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(35,5,'','',0,'L');
        Fpdf::Cell(155,5,'MD(Name): _______________________________Signature/Date __________________________','',0,'L');
        Fpdf::Cell(47.5,10,'',0,1);


Fpdf::SetFont('Arial', 'B', 10);
        Fpdf::Cell(35,5,'','',0,'L');
        Fpdf::Cell(155,5,'(d) Disbursement ','',0,'L');
        Fpdf::Cell(47.5,15,'',0,1);



Fpdf::SetFont('Arial', '', 10);
        Fpdf::Cell(35,5,'','',0,'L');
        Fpdf::Cell(155,5,'Disbursement Officer (Name): ____________________Signature/Date ___________________','',0,'L');
        Fpdf::Cell(47.5,15,'',0,1);



        Fpdf::Output('F', $pathFile);
        $headers = ['Content-Type' => 'application/pdf'];
        return response()->file($pathFile, $headers);
    }


}
