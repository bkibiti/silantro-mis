<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class StockAdjustment extends Model
{
    protected $table = 'inv_stock_adjustments';


    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

}
