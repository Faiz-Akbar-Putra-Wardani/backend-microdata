<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerOppurtinities extends Model
{
    Use HasFactory;

    protected $fillable = [
         'title',
        'description',
        'requirements',
    ];
}
