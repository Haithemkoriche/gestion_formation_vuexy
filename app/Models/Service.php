<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'status', 'price', 'required_documents', 'required_fields'
    ];

    protected $casts = [
        'status' => 'boolean',
        'required_documents' => 'array', // Convertit JSON en tableau
        'required_fields' => 'array',
    ];
}