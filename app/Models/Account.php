<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    public function statement()
    {

        return $this->hasMany(MomoStatement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statementfile()
    {
        return $this->hasMany(StatementFile::class);
    }
}
