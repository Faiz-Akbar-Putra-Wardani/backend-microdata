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

     public function category()
    {
        return $this->belongsTo(PortofolioCategory::class, 'portfolio_category_id');
    }
}
// tambahkan link project