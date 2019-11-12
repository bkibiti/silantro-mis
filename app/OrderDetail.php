<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class OrderDetail extends Model
{
    //
    protected $table = 'order_details';
    public $timestamps = false;

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');

    }
    public function order(){
        return $this->hasMany(Order::class, 'order_id');
    }
}
