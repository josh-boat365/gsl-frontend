<?php

namespace App\Models;

use App\Models\LoanRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApproval extends Model
{
    use HasFactory;


    protected $fillable = [
        'loan_request_id',
        'user_id',
        'approver_name',
        'a_position',
        'approval_date',
        'a_remark',
        'a_sig',
        'a_stage_info',
        'approval_status',
        'created_at',
        'updated_at'
    ];

    public function LeaveRequest()
    {
        return $this->belongsTo(LeaveRequest::class);
    }
}
