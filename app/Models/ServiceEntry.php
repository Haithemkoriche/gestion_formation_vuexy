<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceEntry extends Model
{
    use HasFactory;

    protected $fillable = ['service_id', 'entry_data', 'status'];

    protected $casts = [
        'entry_data' => 'array', // Indique que la colonne `entry_data` est de type array
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
