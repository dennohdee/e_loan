<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    //added soft deletes for archiving/safety
    use HasFactory, SoftDeletes;

    //relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function loanProduct()
    {
        return $this->belongsTo(LoanProduct::class);
    }
}
