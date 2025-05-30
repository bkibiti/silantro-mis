<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffAdvance extends Model
{
    protected $table = 'staff_advances';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
