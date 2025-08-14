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
        'category_id',
        'company_name',
        'image_portofolio'
    ];

    public function category()
    {
        return $this->belongsTo(PortofolioCategory::class, 'category_id');
    }
}
// tambahkan link project