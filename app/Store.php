<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CurrentStock as currentStock;

class Store extends Model
{
    protected $table = 'inv_stores';
    public $timestamps = false;

    public function currentStock(){
        return $this->hasMany(currentStock::class,'store_id');
    }

}
