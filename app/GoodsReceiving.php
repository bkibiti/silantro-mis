<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoodsReceiving extends Model
{
    protected $table = 'inv_incoming_stock';


    public function supplier()
    {

        return $this->belongsTo('App\Supplier');
    }

    public function product()
    {

        return $this->belongsTo(Product::class, 'product_id');
    }

}

