<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductBilling extends Model
{
    protected $fillable = ['product_id', 'billing_id', 'quantity', 'price', 'discount', 'total'];
}
