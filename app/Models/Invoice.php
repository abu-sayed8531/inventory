<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'total',
        'discount',
        'vat',
        'payable',
        'customer_id',
        'user_id'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
