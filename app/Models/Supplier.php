<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;

class Supplier extends Model
{
    protected $table = 'supplier';
    
    protected $fillable = [
        'code',
        'name',
        'mobile',
        'address',
        'created_by',
        'updated_by',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function products(){
        return $this->hasMany(Product::class);
    }
}
