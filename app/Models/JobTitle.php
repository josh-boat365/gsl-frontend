<?php

namespace App\Models;

use App\Models\LeaveRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model
{
    use HasFactory;


    protected $fillable = [
        'id',
        'title_name'
    ];

    
}
