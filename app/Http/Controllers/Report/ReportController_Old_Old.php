<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\LoanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    //

    public function index() {

        return view('workspace.report.run-report');
    }

    public function export(Request $request){
        $title = match ($request->stage_info) {
            'ALL' => $this->reportAllQuery($request),
            'PROCESSING' => $this->reportQuery($request),
            'APPROVED' => $this->reportQuery($request),
            'DECLINED' => $this->reportQuery($request)
        };
    }

    public function reportAllQuery($request){
        if($request->type == "without_periods"){
            $reportData = DB::table('leave_requests')->where('status_flag', '!=',NULL)->get();
            $this->show($reportData, $request->stage_info);
        }

        if( $request->type == "within_periods"){
            $dateFrom = Carbon::parse($request->fromdate)->startOfDay();
            $dateTo = Carbon::parse($request->todate)->endOfDay();

            $with_periods = DB::table('leave_requests')->where('status_flag', '!=', NULL)
                ->whereBetween('created_at',[$dateFrom,$dateTo])->get();
            $this->show($with_periods, $request->stage_info);
        }

        return redirect()->back();
    }

    public function reportQuery($request){
        if($request->type == "without_periods"){
            $reportData = DB::table('leave_requests')->where('status_flag', $request->stage_info)->get();
            $this->show($reportData, $request->stage_info);
        }

        if( $request->type == "within_periods"){
            $dateFrom = Carbon::parse($request->fromdate)->startOfDay();
            $dateTo = Carbon::parse($request->todate)->endOfDay();

            $with_periods = DB::table('leave_requests')->where('status_flag', $request->stage_info)
                ->whereBetween('created_at',[$dateFrom,$dateTo])->get();
            $this->show($with_periods, $request->stage_info);
        }

        return redirect()->back();
    }

    public function exportPayments(Request $request){
        $dateFrom = Carbon::parse($request->payFrom)->startOfDay();
        $dateTo = Carbon::parse($request->payTo)->endOfDay();
         $getRequest = LoanRequest::where('stage_info','stage_5')->whereBetween('approval_date', [$dateFrom, $dateTo])->get(['id','customer','account_number','payment_type','bank_name','pay_account_number','pay_account_name','bank_branch','sort_code']);
        $this->show($getRequest, 'payments');
    }



    public function show($query, $stage_info){
        $res = [];
        foreach($query as $getRow){
            $getRow->customer = ((customer($getRow->id)->first_name ?? '') . ' ' . (customer($getRow->id)->surname ?? '') .' '. (customer($getRow->id)->middle_name ?? '') ) ?? '';
            $getRow->account_number = "'".$getRow->account_number;
            $getRow->pay_account_number = "'".$getRow->pay_account_number;
            $res []= $getRow;
        }
        $data = json_decode(json_encode($res), True);
        function cleanData(&$str)
        {
            if ($str == 't') $str = 'TRUE';
            if ($str == 'f') $str = 'FALSE';
            if (preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str) || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$str)) {
                $str = " $str";
            }
            if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
        }
        // filename for download
        $filename = $stage_info.'_'. date('Ymd') . ".csv";

        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: text/csv");

        $out = fopen("php://output", 'w');

        $flag = false;
        foreach ($data as $row) {
            if (!$flag) {
                // display field/column names as first row
                fputcsv($out, array_keys($row), ',', '"');
                $flag = true;
            }
            array_walk($row, __NAMESPACE__ . '\cleanData');
            fputcsv($out, array_values($row), ',', '"');
        }

        fclose($out);
    }
}
