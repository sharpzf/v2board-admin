<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'v2_plan';
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];


    //订阅所属权限组
    public function group()
    {
        return $this->belongsTo('App\Models\ServerGroup','group_id','id');
    }
}
