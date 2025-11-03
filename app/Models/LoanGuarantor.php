<?php

namespace App\Models;

use App\Models\LoanCustomer;
use App\Models\LoanRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanGuarantor extends Model
{
    use HasFactory;


    protected $fillable = [
        'loan_request_id',
        'g_name',
        'g_phone',
        'g_gps_address',
        'g_id_number',
        'g_dob',
        'g_business_type',
        'g_business_direction',
        'g_residence_direction',
        'g_own_business',
        'g_created_by'
    ];

    // public function LoanCustomer()
    // {
    //     return $this->belongsTo(LoanCustomer::class);
    // }

    public function LoanRequest()
    {
        return $this->belongsTo(LoanRequest::class);
    }
}
