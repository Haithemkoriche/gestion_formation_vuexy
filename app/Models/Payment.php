<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_entry_id',
        'client_mail',
        'amount',
        'versed',
        'reste',
    ];

    public function serviceEntry()
    {
        return $this->belongsTo(ServiceEntry::class);
    }
}
