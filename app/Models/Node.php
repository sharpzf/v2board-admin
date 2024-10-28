<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $table = 'v2_node';
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'group_id' => 'array',
        'route_id' => 'array',
        'tags' => 'array',
        'obfs_settings' => 'array'
    ];


    public function shadowsocks()
    {
        return $this->belongsTo('App\Models\ServerShadowsocks','id','server_id');
    }
    public function vmess()
    {
        return $this->belongsTo('App\Models\ServerVmess','id','server_id');
    }
    public function trojan()
    {
        return $this->belongsTo('App\Models\ServerTrojan','id','server_id');
    }
    public function hysteria()
    {
        return $this->belongsTo('App\Models\ServerHysteria','id','server_id');
    }
}
