<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatementFile extends Model
{
    use HasFactory;

    protected $fillable = ['processed'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function momostatements()
    {
        return $this->hasMany(MomoStatement::class);
    }
}
