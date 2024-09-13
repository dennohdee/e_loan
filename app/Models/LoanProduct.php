<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class LoanProduct extends Model
{
    //added soft deletes for archiving/safety
    use HasFactory, SoftDeletes;

    //relationships
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
