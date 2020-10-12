<?php

namespace App\Model\Users;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // 指定資料庫連線名稱
    protected $connection = 'mysql';
    // 資料庫名稱
    protected $table = 't_users';
    // 主鍵欄位
    protected $primaryKey = 'id';
    // 主鍵型態
    protected $keyType = 'int';
    // 欄位名稱
    protected $fillable = [
        'account', 'password', 'nick_name', 'phone', 'sex',
        'points', 'app', 'channel_id', 'link_id', 'vip',
        'remarks', 'token_jwt', 'remember_token',
        'status', 'created_at', 'updated_at'
    ];
    // 隱藏不顯示欄位
    protected $hidden = [
        //'password'
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

    // 輸出改寫
    // public function getSexAttribute($sex)
    // {
    //     if ($sex == 1) return '男';
    //     if ($sex == 2) return '女';
    //     return '未知';
    // }

    // 活耀條件
    public function scopeActive($query)
    {
        return $query->where('status', 1)->orderBy('id', 'ASC');
    }
}
