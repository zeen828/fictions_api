<?php

namespace App\Model\Analysis;

use Illuminate\Database\Eloquent\Model;

class LogsUsersAccess extends Model
{
    // 指定資料庫連線名稱
    protected $connection = 'mysql';
    // 資料庫名稱
    protected $table = 't_logs_users_access';
    // 主鍵欄位
    protected $primaryKey = 'id';
    // 主鍵型態
    protected $keyType = 'int';
    // 欄位名稱
    protected $fillable = [
        'mode', 'app', 'channel_id', 'link_id',
        'status', 'created_at', 'updated_at'
    ];
    // 隱藏不顯示欄位
    //protected $hidden = [];
    // 軟刪除
    //use SoftDeletes;
    // 是否自動待時間撮
    public $timestamps = true;
    // 時間撮保存格式
    //protected $dateFormat = 'U';
    // 自訂時間撮欄位
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
