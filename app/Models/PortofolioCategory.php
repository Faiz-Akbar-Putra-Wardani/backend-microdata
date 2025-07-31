<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortofolioCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
    ];

    public function portofolios()
    {
        return $this->hasMany(Portofolio::class);
    }
}
