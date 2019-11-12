<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $fillable = ['client_document', 'total', 'products_quantity', 'coupon', 'coupon_discount', 'money', 'change'];

    public function products()
    {
        return $this->belongsToMany('App\Product', 'product_billing')->withPivot('quantity', 'price', 'discount');
    }
}
