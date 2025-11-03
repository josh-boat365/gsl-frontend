<?php

namespace App\Models\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Throwable;
use Auth;

class customerImport implements 
    ToModel, 
    WithHeadingRow, 
    SkipsOnError, 
    WithValidation, 
    WithBatchInserts
{
    use Importable, SkipsErrors;

    public $batchNum;
    public $postingDate;

    public function uniqidReal($lenght = 8) {
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
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Customer([
            'fname'=>$row['fname'],
            'sname'=>$row['sname'],
            'staff_id'=>$row['staff_id'],
            'phone'=>$row['phone'],
            'loan_amt'=>$row['loan_amt'],
            'installment'=>$row['installment'],
            'duration'=>$row['duration'],
            'disbursement_date'=>$row['disbursement_date'],
            'date_sms_sent'=>$row['date_sms_sent'],
            'account_no'=>$row['account_no'],
            'agent_name'=>$row['agent_name'],
            'agent_code'=>$row['agent_code'],
            'stage_info'=>'PENDING',
            'status_flag'=>'IN-PROCESS',
            'batch_number'=>$this->batchNum,
            'imported_by'=>Auth::user()->id
        ]);
    }

    // public function onError(Throwable $error)
    // {
    // }

    public function rules(): array
    {
        return [];
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
