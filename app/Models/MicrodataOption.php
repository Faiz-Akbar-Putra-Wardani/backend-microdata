<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MicrodataOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'name_title',
        'description'
    ];
}
