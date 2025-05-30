<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchases';


    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }

    public function product()
    {
        return $this->belongsTo('App\Stock', 'product_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'created_by'); 
    }
}
