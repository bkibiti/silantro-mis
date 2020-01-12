<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product as product;

class Category extends Model
{
    protected $table = 'product_categories';
    public $timestamps = false;

    public function category(){
        return $this->hasMany(product::class, 'category_id', 'id');

    }


}

