<?php

use Illuminate\Support\Facades\DB;
use App\Setting;

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

