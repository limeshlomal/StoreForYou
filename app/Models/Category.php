<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    Use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'is_active',
        'created_by'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
}
