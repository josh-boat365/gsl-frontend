<?php

namespace App\Models;

use App\Models\LoanCustomer;
use App\Models\LoanRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name','last_name','email','phone','image', 'email','role_id','branch_code','created_by', 'password','position_id','department_id','gender','date_of_birth','staff_number','status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function LoanCustomer()
    // {
    //     return $this->hasMany(LoanCustomer::class);
    // }

    public function LoanRequests()
    {
        return $this->hasMany(LoanRequest::class);
    }
}
