<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    // 指定資料庫連線名稱
    protected $connection = 'mysql';
    // 資料庫名稱
    protected $table = 'go_users_new';
    // 主鍵欄位
    protected $primaryKey = 'id';
    // 主鍵型態
    protected $keyType = 'int';
    // 欄位名稱
    protected $fillable = [
        'phone', 'password', 'nick_name', 'sex', 'points',
        'channel_id', 'link_id', 'vip', 'status', 'remarks'
    ];
    // 隱藏不顯示欄位
    protected $hidden = [
        'password', 'remember_token'
    ];
    // 軟刪除
    //use SoftDeletes;
    // 是否自動待時間撮
    public $timestamps = true;
    // 時間撮保存格式
    //protected $dateFormat = 'Y-m-d H:i:s';
    // 自訂時間撮欄位
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
