<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Toastr;


class BackEndController extends Controller
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

    private function sms($to,$otp){
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://2kp9nw.api.infobip.com/sms/2/text/advanced',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"messages":[{"destinations":[{"to":"'.$to.'"}],"from":"BestPoint","text":"'.$otp.'"}]}',
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic YnBvaW50OkJwb2ludHgxMDA/',
                'Content-Type: application/json',
                'Accept: application/json'
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);
        return 1;
    }

    private function getID($acct, $otp) {
        $getID = Customer::where('account_number', $acct)
                            ->where('otp',$otp)
                            ->pluck('id')->first();
         if (!is_null($getID)) {
            return $getID;
         }else{
            return 0;
         }
        
    }

    private function accountExists($acct) {
        $getAccount = Customer::where('account_number', $acct)
                            ->where('otp_sent', 'YES')
                            ->where('otp_status', 'CONFIRMED')
                            ->where('ghana_card_number', '!=', NULL)
                            ->pluck('account_number')->first();
         if (is_null($getAccount)) {
            return false;
         }else{
            return true;
         }
        
    }


    public function viewProcessing()
    {
        $requestData = Customer::where('otp_sent', 'YES')
                            ->where('otp_status', 'CONFIRMED')
                            ->where('ghana_card_number', '!=', NULL)
                            ->where('update_status', 'PROCESSING')
                            ->where('account_branch', getBranchDescription(Auth::user()->branch_code))
                            ->get();
        
        return view('workspace.customer.list',['requestData'=>$requestData]);
    }

    public function viewPendingApproval()
    {
        $requestData = Customer::where('otp_sent', 'YES')
                            ->where('otp_status', 'CONFIRMED')
                            ->where('ghana_card_number', '!=', NULL)
                            ->where('update_status', 'APPROVAL')
                            ->where('account_branch', getBranchDescription(Auth::user()->branch_code))
                            ->get();
        
        return view('workspace.customer.list',['requestData'=>$requestData]);
    }


    public function viewApproved()
    {
        $requestData = Customer::where('otp_sent', 'YES')
                            ->where('otp_status', 'CONFIRMED')
                            ->where('ghana_card_number', '!=', NULL)
                            ->where('update_status', 'UPDATED')
                            ->where('account_branch', getBranchDescription(Auth::user()->branch_code))
                            ->get();
        
        return view('workspace.customer.list',['requestData'=>$requestData]);
    }

    public function viewDeclined()
    {
        $requestData = Customer::where('otp_sent', 'YES')
                            ->where('otp_status', 'CONFIRMED')
                            ->where('ghana_card_number', '!=', NULL)
                            ->where('update_status', 'DECLINED')
                            ->where('account_branch', getBranchDescription(Auth::user()->branch_code))
                            ->get();
        
        return view('workspace.customer.list',['requestData'=>$requestData]);
    }

    // public function viewList($status='0',$)
    // {
    //     //Toastr::success('Profile updated successfully', 'Profile');
    //     $requestData = Customer::where('otp_sent', 'YES')
    //                         ->where('otp_status', 'CONFIRMED')
    //                         ->where('ghana_card_number', '!=', NULL)
    //                         ->where('update_status', 'PROCESSING')
    //                         ->get();
        
    //     return view('workspace.customer.list',['requestData'=>$requestData]);
    // }

    public function viewDetails($id=0)
    {
        $getCustomer = Customer::find($id);

        //Toastr::success('Profile updated successfully', 'Profile');
        
        return view('workspace.customer.details',['customer'=>$getCustomer]);
    }


    public function pendingApproval($id)
    {
        $customerPhoneNumber = '';
        $getCustomer = Customer::find($id);

        // $customerPhoneNumber = preg_replace('/^0/', '233', $getCustomer->acct_phone_num);

        $getCustomer->update_status = 'APPROVAL';
        $getCustomer->processed_by = Auth::user()->id;
        $getCustomer->date_processed = date("Y-m-d H:i:s");
        $getCustomer->save();
        // if($getCustomer->save()){
        //     $this->sms($customerPhoneNumber,"Dear customer, your online update is successful. Thank you.");
        //     Toastr::success('Approved successfully', 'Approval');
        //     return redirect()->to('view-processing');
        // }
        return redirect()->to('view-processing');
        //return view('workspace.customer.list',['customer'=>$getCustomer]);
    }

    public function requestApproval($id)
    {
        $customerPhoneNumber = '';
        $getCustomer = Customer::find($id);

        $customerPhoneNumber = preg_replace('/^0/', '233', $getCustomer->acct_phone_num);

        $getCustomer->update_status = 'UPDATED';
        $getCustomer->updated_by = Auth::user()->id;
        $getCustomer->date_updated = date("Y-m-d H:i:s");
        if($getCustomer->save()){
            $this->sms($customerPhoneNumber,"Dear customer, your online update is successful. Thank you.");
            Toastr::success('Approved successfully', 'Approval');
            return redirect()->to('view-pending-approval');
        }
        return redirect()->to('view-pending-approval');
        //return view('workspace.customer.list',['customer'=>$getCustomer]);
    }

    public function declineRequest($id)
    {
        $customerPhoneNumber = '';
        $getCustomer = Customer::find($id);

        $customerPhoneNumber = preg_replace('/^0/', '233', $getCustomer->acct_phone_num);

        $getCustomer->update_status = 'DECLINED';
        $getCustomer->updated_by = Auth::user()->id;
        $getCustomer->date_updated = date("Y-m-d H:i:s");
        if($getCustomer->save()){
            $this->sms($customerPhoneNumber,"Dear customer, your online update is not successful. Contact any Best Point branch for details. Thank you.");
            Toastr::success('Declined successfully', 'DECLINED');
            return redirect()->to('view-processing');
        }
        return redirect()->to('view-processing');
        //return view('workspace.customer.list',['customer'=>$getCustomer]);
    }

    public function changeBranch(Request $request, $newBranch='0000'){
    //return $request->user();
    $getBranchCode = DB::connection('authdb')->table('branches')->where('branch_code',$newBranch)->select('branch_code')->first();
    $getUser = DB::connection('authdb')->statement('UPDATE users SET branch_code = "'. strval($getBranchCode->branch_code) .'" WHERE id ='. Auth::user()->id);

    //table('users')->where('id',1)->update(['branch_code' => $getBranchCode->branch_code]);

    // $getUser->branch_code = $getBranchCode->branch_code;
    // $getUser->save();

    return redirect()->to('view-processing');
    //return DB::connection('authdb')->table('users')->get();
    }





// HEAD OFFICE
// KEJETIA
// ABOSSEY OKAI
// ACCRA NEWTOWN
// MAKOLA
// MADINA
// ASHAIMAN
// TAKORADI MARKET CIRCLE
// SUAME
// MILE 7
// KASOA NEW MARKET
// TEMA COMMUNITY 1
// ADUM
// TAMALE
// ODORKOR
// EAST LEGON
// SUNYANI
// EJISU
// AGONA SWEDRU BRANCH
}








// 0000    HEAD OFFICE
// 0005    KEJETIA
// 0003    ABOSSEY OKAI
// 0004    ACCRA NEWTOWN
// 0002    MAKOLA
// 0007    MADINA
// 0008    ASHAIMAN
// 0009    TAKORADI MARKET CIRCLE
// 0010    SUAME
// 0001    MILE 7
// 0006    KASOA NEW MARKET
// 0011    TEMA COMMUNITY 1
// 0012    ADUM
// 0013    TAMALE
// 0014    ODORKOR
// 0015    EAST LEGON
// 0016    SUNYANI
// 0018    EJISU
// 0017    AGONA SWEDRU BRANCH
