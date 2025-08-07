<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceLandingPage extends Model
{
    Use HasFactory;

    protected $fillable = [
        'title',
      'name_service',
       'description',
    ];
}
