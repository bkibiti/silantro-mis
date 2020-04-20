<?php

use Illuminate\Support\Facades\DB;
use App\Setting;
use App\Stock;


function getRoles(){
    return DB::table('roles')
        ->select('id','name')
        ->orderBy('name')
        ->get();
}

function getSettings($settingName){
    $settings = Setting::get();

    $value = 'NA';
    foreach ($settings as $s) {
        if($s->name == $settingName){
            return $s->value;
        }
    }
    return $value;
}

function outofstock(){
    $outOfStock = Stock::where('quantity', 0)->where('for_sale','Yes')->count();
    return  $outOfStock;
}

function belowMin(){
    $belowMin = Stock::whereRaw('quantity <= min_quantinty and quantity > 0')->where('for_sale','Yes')->count();
    return $belowMin;
}