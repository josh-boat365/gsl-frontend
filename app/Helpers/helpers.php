<?php
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use amirsanni\phpewswrapper\PhpEwsWrapper;

function getBranchDescription($branch_code): string
{
    return match ($branch_code) {
        0 =>    'HEAD OFFICE',
        5 =>    'KEJETIA',
        3 =>    'ABOSSEY OKAI',
        4 =>    'ACCRA NEWTOWN',
        2 =>    'MAKOLA',
        7 =>    'MADINA',
        8 =>    'ASHAIMAN',
        9 =>    'TAKORADI MARKET CIRCLE',
        10 =>    'SUAME',
        1 =>    'MILE 7',
        6 =>    'KASOA NEW MARKET',
        11 =>    'TEMA COMMUNITY 1',
        12 =>    'ADUM',
        13 =>    'TAMALE',
        14 =>    'ODORKOR',
        15 =>    'EAST LEGON',
        16 =>    'SUNYANI',
        18 =>    'EJISU',
        17 =>    'AGONA SWEDRU BRANCH',

    };
}

function statusDescription($stage): string
{
    return match ($stage) {
        'stage_0' => 'Requested By',
        'stage_1' => 'BM / HOD / Supervisor',
        'stage_2' => 'HR Confirmation',
        'stage_3' => 'Credit Risk Recommend',
        'stage_4' => 'MCC Approval',
        'stage_5' => 'Business Unit Update',
        'stage_6' => 'Credit Risk Review',
        'stage_7' => 'Disbursement Origination',
        'stage_8' => 'Risk Origination Review',
        'stage_9' => 'CPU Manager\'s Approval',
        'stage_10' => 'CPU Final Disbursement',
        'stage_11' => 'Completed and Disbursed',
    };

}

function approvalStatus($stage): string
{
    return match ($stage) {
        'APPROVED' => '<span class="badge badge-pill badge-success">APPROVED</span>',
        'DECLINED' => '<span class="badge badge-pill badge-danger">DECLINED</span>',
        'DISBURSED' => '<span class="badge badge-pill badge-primary">DISBURSED</span>',
        'PROCESSING' => '<span class="badge badge-pill badge-info">PROCESSING</span>',
    };
}

    // '<span class="badge badge-pill badge-info">
    //     PROCESSING
    // </span>
    // <div class="progress progress-md mb-3">
    //     <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" style="width: 15%"></div>
    // </div>
    // @ Branch Review <br/>
    // <a href="#" class="btn btn-outline-light disabled">
    //     <span>
    //         Pending ' . {{ time_elapsed_string($NewRequest->updated_at) }} . '
    //     </span>
    // </a>'



function statusBadge($stage, $datetime, $oldDateTime = NULL, $full = false): string
{
    $oldDateTime = ($stage == 'stage_11' || $stage == 'stage_0_0') ? $oldDateTime : NULL;

    if ($oldDateTime == NULL) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    }else{
        $now = new DateTime($oldDateTime);
        $ago = new DateTime($datetime);
        $diff = $ago->diff($now);
    }

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = [
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    ];
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    if ($oldDateTime == NULL) {
        $get_time_elapsed = $string ? implode(', ', $string) . ' ago' : 'just now';
    }else{
        $get_time_elapsed =  $string ? implode(', ', $string) . '' : 'just now';
    }

    return match ($stage) {
        'stage_1'=>'<span class="badge badge-pill badge-info">
                        NEW LOAN
                    </span>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" style="width: 20%"></div>
                    </div>
                    @ BDO<br/>
                    <small class="text-muted">
                    <i class="fa fa-clock-o"></i> Pending ' . $get_time_elapsed .'
                    </small>',

        'stage_2'=>'<span class="badge badge-pill badge-info">
                        ACCOUNT OPENING
                    </span>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" style="width: 40%"></div>
                    </div>
                    @ CSO <br/>
                    <small class="text-muted">
                        <i class="fa fa-clock-o"></i> Pending ' . $get_time_elapsed .'
                    </small>',

        'stage_3'=>'<span class="badge badge-pill badge-primary">
                        TPRS APPROVAL
                    </span>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width: 60%"></div>
                    </div>
                    @ BM <br/>
                    <small class="text-muted">
                        <i class="fa fa-clock-o"></i> Pending ' . $get_time_elapsed .'
                    </small>',

        'stage_4'=>'<span class="badge badge-pill badge-primary">
                        DISBURSEMENT
                    </span>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width: 80%"></div>
                    </div>
                    @ BDO<br/>
                    <small class="text-muted">
                        <i class="fa fa-clock-o"></i> Pending ' . $get_time_elapsed .'
                    </small>',

        'stage_5'=>'<span class="badge badge-pill badge-white bg-white">
                       PRE-DISBURSED
                    </span>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar bg-gray" style="width: 100%"></div>
                    </div>
                    @ COMPLETED <i class="fa fa-check-square-o" data-toggle="tooltip" title="" data-original-title="fa fa-check-square-o"></i><br/>
                    <small class="text-muted">
                    Process TAT : ' . $get_time_elapsed .'
                    </small>',

        'stage_6'=>'<span class="badge badge-pill badge-success">
                        APPROVED
                    </span>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 60%"></div>
                    </div>
                    @ Credit Risk Review<br/>
                    <small class="text-muted">
                        <i class="fa fa-clock-o"></i> Pending ' . $get_time_elapsed .'
                    </small>',

        'stage_7'=>'<span class="badge badge-pill badge-teal bg-teal">
                        DISBURSEMENT
                    </span>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-teal" style="width: 70%"></div>
                    </div>
                    @ CPU Origination<br/>
                    <small class="text-muted">
                        <i class="fa fa-clock-o"></i> Pending ' . $get_time_elapsed .'
                    </small>',

        'stage_8'=>'<span class="badge badge-pill badge-teal bg-teal">
                        DISBURSEMENT
                    </span>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-teal" style="width: 75%"></div>
                    </div>
                    @ Risk Origination Review <br/>
                    <small class="text-muted">
                        <i class="fa fa-clock-o"></i> Pending ' . $get_time_elapsed .'
                    </small>',

        'stage_9'=>'<span class="badge badge-pill badge-gray bg-gray" style="color:white;">
                        DISBURSEMENT
                    </span>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-gray" style="width: 80%"></div>
                    </div>
                    @ CPU Manager\'s Approval<br/>
                    <small class="text-muted">
                        <i class="fa fa-clock-o"></i> Pending ' . $get_time_elapsed .'
                    </small>',

        'stage_10'=>'<span class="badge badge-pill badge-gray bg-gray" style="color:white;">
                        DISBURSEMENT
                    </span>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-gray" style="width: 90%"></div>
                    </div>
                    @ CPU Final Disbursement<br/>
                    <small class="text-muted">
                        <i class="fa fa-clock-o"></i> Pending ' . $get_time_elapsed .'
                    </small>',

        'stage_11'=>'<span class="badge badge-pill badge-white bg-white">
                        DISBURSED
                    </span>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar bg-gray" style="width: 100%"></div>
                    </div>
                    @ COMPLETED <i class="fa fa-check-square-o" data-toggle="tooltip" title="" data-original-title="fa fa-check-square-o"></i><br/>
                    <small class="text-muted">
                    Process TAT : ' . $get_time_elapsed .'
                    </small>',

        'stage_0'=>'<span class="badge badge-pill badge-warning">
                        AMENDMENT
                    </span>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar bg-warning" style="width: 50%"></div>
                    </div>
                    @ Requestor <i class="fa fa-pencil" data-toggle="tooltip" title="" data-original-title="fa fa-pencil"></i><br/>
                    <small class="text-muted">
                    Process ' . $get_time_elapsed .'
                    </small>',

        'stage_0_0'=>'<span class="badge badge-pill badge-danger">
                        CANCELLED
                    </span>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar bg-danger" style="width: 50%"></div>
                    </div>
                    @ Requestor <i class="fa fa-remove" data-toggle="tooltip" title="" data-original-title="fa fa-remove"></i><br/>
                    <small class="text-muted">
                    Process TAT : ' . $get_time_elapsed .'
                    </small>',

        'stage_0_1'=>'<span class="badge badge-pill badge-warning">
                        NEW REQUEST
                    </span>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar bg-warning" style="width: 0%"></div>
                    </div>
                    @ Pending Submission <i class="fa fa-pencil" data-toggle="tooltip" title="" data-original-title="fa fa-pencil"></i><br/>
                    <small class="text-muted">
                    Process ' . $get_time_elapsed .'
                    </small>',

    };
}

function user($user_id){
    return DB::table('users')->where('id',$user_id)->select('first_name','last_name','sig','branch_code','position','position_id')->first();
}

function getUsers(){
    return User::orderBy('first_name')->get();
}

function userLeaveDays($user_id){
    $getUserLeaveDays = User::find($user_id);
    return $getUserLeaveDays->LeaveDay;
}

function getLeaveApprovals($requestId){
    $getApprovals = LeaveRequest::find($requestId);
    return $getApprovals->Approval;
}

function getBranchName($branchCode){
    return DB::table('branches')->where('branch_code',$branchCode)->select('branch_name')->first();
}


function getJobTitle($id){
    return DB::table('job_titles')->where('id',$id)->select('title_name')->first();
}

function getDepartment($id){
    return DB::table('departments')->where('id',$id)->select('department_name')->first();
}

function getBranches(){
    return DB::table('branches')->get();
}

function customer($id){
    return DB::table('customers')->where('request_id',$id)->select('first_name','surname','middle_name','id_number','employee_number')->first();
}

function sendMailGroup($subject, $message, $role_id, $branch_code = 0){
    $ews = new PhpEwsWrapper('leaveportal@bestpointgh.com', 'Ab123456.', 'outlook.bestpointgh.com','2013');

    if($branch_code == 0){
        $users = User::where('role_id',$role_id)->get();
    }else{
        $users = User::where('branch_code',$branch_code)->where('role_id',$role_id)->get();
    }


    $ews->mail->sender_name = "LoanPortal";
    $ews->mail->subject = $subject;
    $ews->mail->body = $message;
    $ews->mail->send_as_email = 'LeavePortal@bestpointgh.com';//erer/to send as another user, not the logged in user. Optional

    foreach($users as $user){
        $ews->mail->recipient = $user->email; //['abc@xyz.com', 'abc@example.com']
        $ews->mail->recipient_name = $user->first_name .' '.$user->last_name;
        $ews->mail->send();
    }
    return 1;
}

function sendMail($subject, $message, $to){
    $ews = new PhpEwsWrapper('leaveportal@bestpointgh.com', 'Ab123456.', 'outlook.bestpointgh.com','2013');

    $user = User::find($to);

    $ews->mail->sender_name = "LoanPortal";
    $ews->mail->subject = $subject;
    $ews->mail->body = $message;
    $ews->mail->send_as_email = 'LeavePortal@bestpointgh.com';//erer/to send as another user, not the logged in user. Optional
    $ews->mail->recipient = $user->email; //['abc@xyz.com', 'abc@example.com']
    $ews->mail->recipient_name = $user->first_name .' '.$user->last_name;
    $ews->mail->send();

    return 1;
}

function getRefNum($lenght = 6) {
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



// function dasboardStats($status,$location=null){
//     if($location == null){
//         return DB::table('customers')->where('update_status',$status)->select('id')->count();
//     }
//     return DB::table('customers')->where('update_status',$status)->where('account_branch',$location)->select('id')->count();

// }

// function dasboardTotal($location=null){
//     if($location == null){
//         return DB::table('customers')->select('id')->count();
//     }
//     return DB::table('customers')->where('account_branch',$location)->select('id')->count();
// }

