<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MomoStatement extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_date',
        'from_acct',
        'from_name',
        'from_no',
        'transaction_type',
        'amount',
        'fees',
        'e-levy',
        'bal_before',
        'bal_after',
        'to_no',
        'to_name',
        'to_acct',
        'f_id',
        'ref',
        'ova',
        'account_id',
        'user_id',
        'statement_file_id',
    ];

    public function account()
    {

        return $this->belongsTo(Account::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statementfile()
    {
        return $this->belongsTo(StatementFile::class, 'statement_file_id');
    }
}
