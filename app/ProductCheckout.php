<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCheckout extends Model
{
    public function CheckoutDetails()
    {
        return $this->hasMany('App\CheckoutDetail', 'checkoutId', 'id');
    }
}
