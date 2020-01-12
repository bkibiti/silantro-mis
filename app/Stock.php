<?php

namespace App;

use App\Store as store;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stock';

    public function store()
    {
        return $this->belongsTo(store::class);
    }

    public function category()
    {
        return $this->belongsTo(category::class);
    }
  


}
