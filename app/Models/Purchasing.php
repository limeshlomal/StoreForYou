<?php

namespace App\Models;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Purchasing extends Model
{
    protected $fillable = [
        'purchase_date',
        'supplier_id',
        'created_by',
        'updated_by',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
