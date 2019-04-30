<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'tbl_products';


    protected $fillable = [
        'pName','size','quantity','brand','category','price'
    ];
    
	public function productCheckout(){
		return $this->hasMany('App\ProductCheckout','productID','id')->select(array('*'));
    }
    






}
