<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Toastr;


class CustomerController extends Controller
{

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


    public function accountVerification(Request $request){
       $checkAccount = $this->accountExists($request->account_number);
        if ($checkAccount) {
            return response()->json([
                'responseCode' => '404',
                'message' => 'You have already submitted your ghaana card for update.'
            ], 200);
        }else{
            $validator = Validator::make($request->all(), [
                'account_number'=>'required|max:13|min:13',
                'phone_number'=>'required|max:10|min:10',
                'dob'=>'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $accountNumber = $request->account_number;
            $getOTP = strtoupper($this->getBatchNum());
            //$phoneNumber = preg_replace('/^0/', '233', $request->phone_number);

            $response = Http::withHeaders([
                'x-api-key' => 'GHC_PORTAL',
                'x-api-secret' => 'K5X02MUP9'
            ])->get("http://192.168.1.23:8184/core/api/v1.0/account/id/$accountNumber");

            

            if ($response->ok()) {
                $data =  json_decode($response->body());
                if ($data->responseCode == '000') {

                    $newUpdate = Customer::firstOrNew(['account_number' => $request->account_number]);
                    $primaryAccountPhoneNumber = preg_replace('/^0/', '233', $data->data[0]->primaryAccountPhoneNumber);

                    $newUpdate->account_number  = $request->account_number;
                    $newUpdate->phone_number = $request->phone_number;
                    $newUpdate->dob = $request->dob;
                    $newUpdate->otp = $getOTP;
                    $newUpdate->otp_sent = 'YES';
                    $newUpdate->otp_status = 'PENDING';
                    $newUpdate->account_name = $data->data[0]->acctName;
                    $newUpdate->acct_phone_num = $data->data[0]->primaryAccountPhoneNumber;
                    $newUpdate->account_branch = $data->data[0]->balance->brCode;
                    $newUpdate->customer_number = $data->data[0]->customerNumber;

                    if($newUpdate->save()){
                        $this->sms($primaryAccountPhoneNumber,"Your OTP is $getOTP");
                        return response()->json([
                            'responseCode' => $data->responseCode,
                            'message' => $data->message
                        ], 200);
                    }
                } else {
                    return response()->json([
                        'responseCode' => $data->responseCode,
                        'message' => $data->message
                    ], 200);
                }
            }else{
                return response()->json([
                        'responseCode' => '403',
                        'message' => 'something went wrong'
                    ], 403);
            }

            // Toastr::success('Excel file imported Successfully', 'File Import');
            return redirect()->back();
        }
    }

    public function otpConfirmation(Request $request){

        $validator = Validator::make($request->all(), [
            'otp'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $getId = $this->gitID($request->acctNum, $request->otp);
        $updateInfo = Customer::find($getId);
        $updateInfo->otp_status = 'CONFIRMED';
        if($updateInfo->save()){
            return response()->json([
                    'responseCode' => '000',
                    'message' => 'OTP confirmed Successfully'
                ], 200);
        }else{
            return response()->json([
                    'responseCode' => '403',
                    'message' => 'something went wrong'
                ], 403);
        }

        
    }

    public function cardUpload(Request $request){
        $validator = Validator::make($request->all(), [
            'gc_number'=>'required',
            'file_f'=>'required|mimes:jpg,png',
            'file_b'=>'required|mimes:jpg,png'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $fileF = $request->file('file_f')->store('attachment');
        $fileB = $request->file('file_b')->store('attachment');

        $getId = $this->gitID($request->acctNum2, $request->otpCode);
        $updateInfo = Customer::find($getId);
        $updateInfo->ghana_card_number = $request->gc_number;
        $updateInfo->card_image_front = $fileF;
        $updateInfo->card_image_back = $fileB;
        $updateInfo->update_status = 'PROCESSING';
        if($updateInfo->save()){
            return response()->json([
                    'responseCode' => '000',
                    'message' => 'Card uploaded Successfully'
                ], 200);
        }else{
            return response()->json([
                    'responseCode' => '403',
                    'message' => 'something went wrong'
                ], 403);
        }
    }


    private function sms($to,$otp){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://2kp9nw.api.infobip.com/sms/2/text/advanced',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"messages":[{"destinations":[{"to":"'.$to.'"}],"from":"BestPoint","text":"'.$otp.'"}]}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic YnBvaW50OkJwb2ludHgxMDA/',
                'Content-Type: application/json',
                'Accept: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return 1;
    }

    private function gitID($acct, $otp) {
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
}











// {
//     "responseCode": "000",
//     "message": "Success",
//     "data": [
//         {
//             "acctLink": "0008466203100",
//             "acctName": "CHRISTIAN ASHUN",
//             "currency": "GHS",
//             "product": "STAFF CURRENT ACCOUNT",
//             "customerNumber": "00084662",
//             "customerName": "CHRISTIAN ASHUN",
//             "primaryAccountPhoneNumber": "0207955915",
//             "primaryAccountEmail": null,
//             "balance": {
//                 "product": "STAFF CURRENT ACCOUNT",
//                 "accountName": "",
//                 "noOfHolder": "",
//                 "brCode": "HEAD OFFICE",
//                 "riskCode": "",
//                 "blkAmt": "",
//                 "odAmount": "",
//                 "lienAmt": "",
//                 "avBalance": "99.86",
//                 "avBalanceSsh": null,
//                 "bkBalance": "99.86",
//                 "bkBalSsh": "",
//                 "lastDBTransDate": "",
//                 "lastCDTransDate": "",
//                 "statusDesc": "",
//                 "statusCode": "",
//                 "clearedBalance": "",
//                 "unClearedBalance": "",
//                 "currency": ""
//             }
//         },
//         {
//             "acctLink": "0008466246100",
//             "acctName": "CHRISTIAN ASHUN",
//             "currency": "GHS",
//             "product": "SUSU SAVINGS ACCOUNT",
//             "customerNumber": "00084662",
//             "customerName": "CHRISTIAN ASHUN",
//             "primaryAccountPhoneNumber": "0207955915",
//             "primaryAccountEmail": null,
//             "balance": {
//                 "product": "SUSU SAVINGS ACCOUNT",
//                 "accountName": "",
//                 "noOfHolder": "",
//                 "brCode": "HEAD OFFICE",
//                 "riskCode": "",
//                 "blkAmt": "",
//                 "odAmount": "",
//                 "lienAmt": "",
//                 "avBalance": "20",
//                 "avBalanceSsh": null,
//                 "bkBalance": "20",
//                 "bkBalSsh": "",
//                 "lastDBTransDate": "",
//                 "lastCDTransDate": "",
//                 "statusDesc": "",
//                 "statusCode": "",
//                 "clearedBalance": "",
//                 "unClearedBalance": "",
//                 "currency": ""
//             }
//         },
//         {
//             "acctLink": "0101271211100",
//             "acctName": "CHRISTIAN ASHUN ITF DELALI EMMANUEL KOFI ASHUN",
//             "currency": "GHS",
//             "product": "KIDS ACCOUNT",
//             "customerNumber": "01012712",
//             "customerName": "CHRISTIAN ASHUN ITF DELALI EMMANUEL KOFI ASHUN",
//             "primaryAccountPhoneNumber": "0207955915",
//             "primaryAccountEmail": null,
//             "balance": {
//                 "product": "KIDS ACCOUNT",
//                 "accountName": "",
//                 "noOfHolder": "",
//                 "brCode": "MILE 7",
//                 "riskCode": "",
//                 "blkAmt": "",
//                 "odAmount": "",
//                 "lienAmt": "",
//                 "avBalance": "57.39",
//                 "avBalanceSsh": null,
//                 "bkBalance": "87.39",
//                 "bkBalSsh": "",
//                 "lastDBTransDate": "",
//                 "lastCDTransDate": "",
//                 "statusDesc": "",
//                 "statusCode": "",
//                 "clearedBalance": "",
//                 "unClearedBalance": "",
//                 "currency": ""
//             }
//         }
//     ]
// }