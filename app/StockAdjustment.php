<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class StockAdjustment extends Model
{
    protected $table = 'stock_adjustments';


    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function product()
    {
        return $this->belongsTo('App\Stock', 'stock_id');
    }



}
