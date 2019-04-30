<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CheckoutDetail extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public function ProductCheckout()
    {
        return $this->belongsTo('App\ProductCheckout', 'checkoutId', 'id');
    }

    public function Product()
    {
        return $this->hasOne('App\Product', 'ID', 'productId')->select(array('ID', 'pName','category','size','brand','price'));
    }
}
