<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessLine extends Model
{
    Use HasFactory;

    protected $fillable = [
        'title',
        'icon',
        'title_business',
        'description',
    ];
}

// tambahkan link
