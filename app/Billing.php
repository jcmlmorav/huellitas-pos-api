<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $fillable = ['client_document', 'total', 'products_quantity'];

    public function products()
    {
        return $this->belongsToMany('App\Product', 'product_billing');
    }
}
