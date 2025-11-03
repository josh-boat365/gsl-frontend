<?php

namespace App\Models;

use App\Models\User;
use App\Models\LoanGuarantor;
use App\Models\LoanRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanCustomer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
            'request_id',
            'first_name',
            'surname',
            'middle_name',
            'date_of_birth',
            'age',
            'gender',
            'id_type',
            'id_number',
            'date_of_issue',
            'expiry_date',
            'place_of_issue',
            'residential_address',
            'residential_landmark',
            'work_address',
            'work_landmark',
            'mobile',
            'home_phone',
            'work_phone',
            'email',
            'occupation',
            'employer',
            'department',
            'employment_date',
            'employee_number',
            'years_employed',
            'g_full_name',
            'g_relationship',
            'g_mobile',
            'g_home_phone',
            'g_work_phone',
            'g_email',
            'g_residential_address',
            'g_residential_landmark',
            'g_work_address',
            'g_work_landmark',
            'created_by'
    ];

    // public function User()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // public function LoanGuarantor()
    // {
    //     return $this->hasOne(LoanGuarantor::class);
    // }

    public function LoanRequest()
    {
        return $this->belongsTo(LoanRequest::class, 'request_id');
    }
}
