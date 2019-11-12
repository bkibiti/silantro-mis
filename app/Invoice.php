<?php

namespace App;
use App\Supplier;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    //
    protected $table = 'inv_invoices';
    
    public function supplier(){
    	
    	return $this->belongsTo('App\Supplier');
    }




}
    
