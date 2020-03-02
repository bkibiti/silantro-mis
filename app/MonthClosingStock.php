<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonthClosingStock extends Model
{
    protected $table = 'monthly_closing_stock';
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo('App\Stock', 'stock_id');
    }
    
}
