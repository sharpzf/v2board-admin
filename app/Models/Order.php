<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'v2_order';
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'surplus_order_ids' => 'array'
    ];


    //关联用户
    public function user()
    {
        return $this->belongsTo('App\Models\V2user','user_id','id');
    }


    //关联订阅
    public function plan()
    {
        return $this->belongsTo('App\Models\Plan','plan_id','id');
    }
}
