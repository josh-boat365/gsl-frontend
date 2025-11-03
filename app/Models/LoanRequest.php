<?php

namespace App\Models;

use App\Models\User;
use App\Models\LoanApproval;
use App\Models\LoanCustomer;
use App\Models\LoanGuarantor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRequest extends Model
{
    use HasFactory;

    protected $table = 'requests';

    protected $fillable = [
        'ref_num',
    ];



    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function LoanCustomer()
    {
        return $this->hasOne(LoanCustomer::class, 'request_id');
    }

}
