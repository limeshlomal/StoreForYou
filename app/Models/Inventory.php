<?php

namespace App\Models;

use App\Models\Purchasing;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'purchasing_id',
        'product_id',
        'quantity',
        'cost_price',
        'created_by',
        'updated_by',
    ];

    public function purchasing()
    {
        return $this->belongsTo(Purchasing::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
