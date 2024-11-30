<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caisse extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'total_entry', 'total_exit', 'balance'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
