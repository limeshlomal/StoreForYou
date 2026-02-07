<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_RETURNED = 'returned';
    const STATUS_PARTIAL = 'partial';
    const STATUS_PARTIAL_RETURNED = 'partial_returned';
    const STATUS_HOLD = 'hold';

    use SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'subtotal',
        'discount',
        'total',
        'payment_amount',
        'change_amount',
        'status',
        'user_id',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(InvoicePayment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
