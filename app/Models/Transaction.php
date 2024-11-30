<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;


    protected $fillable = [
        'caisse_id', 
        'service_id', 
        'service_entry_id', 
        'amount', 
        'type', 
        'balance_before', 
        'balance_after'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function serviceEntry()
    {
        return $this->belongsTo(ServiceEntry::class);
    }

    public function caisse()
    {
        return $this->belongsTo(Caisse::class);
    }
}
