<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portofolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'name_project',
        'description',
        'image',
        'image_portofolio',
        'company_name',
        'category_id',
    ];
}
// tambahkan link project