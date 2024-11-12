<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'invoice_date',
        'sub_total',
        'gst_amount',
        'total_amount',
        'status',
        'notes',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function getFormattedTotalAmountAttribute()
    {
        return number_format($this->total_amount, 2);
    }

    public function getFormattedGstAmountAttribute()
    {
        return number_format($this->gst_amount, 2);
    }

    public function getFormattedSubTotalAttribute()
    {
        return number_format($this->sub_total, 2);
    }
}
