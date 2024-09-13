<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class LoanLimit extends Model
{
    use HasFactory, SoftDeletes;

    //relationships
    public function customer() {
        return $this->belongsTo(Customer::class);
    }
}
