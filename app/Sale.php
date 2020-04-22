<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'sales';
  

    public function user()
    {
        return $this->belongsTo('App\User', 'created_by'); 
    }

    public function updater()
    {
        return $this->belongsTo('App\User', 'updated_by'); 
    }

    public function product()
    {
        return $this->belongsTo('App\Stock', 'stock_id');
    }

}
