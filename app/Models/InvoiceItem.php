<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'product_id',
        'quantity',
        'price',
        'amount',
        'gst_rate',
        'gst_amount',
    ];

    /**
     * Define the relationship with the Invoice model.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Define the relationship with the Product model.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Optionally, you can add a method to calculate the total amount for an item.
     */
    public function calculateAmount()
    {
        return $this->quantity * $this->price;
    }

    /**
     * Optionally, add a method to calculate the GST amount for an item.
     */
    public function calculateGstAmount()
    {
        return ($this->gst_rate / 100) * $this->amount;
    }
}
