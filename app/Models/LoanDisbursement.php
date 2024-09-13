<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class LoanDisbursement extends Model
{
    use HasFactory, SoftDeletes;

    // relationships
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
