<?php

namespace App;

use App\Category as category;
use App\CurrentStock as currentStock;
use App\OrderDetail as orderDetail;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'inv_products';

    public function category()
    {
        return $this->belongsTo(category::class);
    }


    public function currentStock()
    {
        return $this->hasMany(currentStock::class, 'product_id');
    }


 

}
