<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class AdminUsers extends Model
{
    // 指定資料庫連線名稱
    protected $connection = 'mysql';
    // 資料庫名稱
    protected $table = 'admin_users';
    // 主鍵欄位
    protected $primaryKey = 'id';
    // 主鍵型態
    protected $keyType = 'int';
    // 欄位名稱
    protected $fillable = [
        'username', 'name', 'avatar', 'remember_token',
        'created_at', 'updated_at'
    ];
    // 隱藏不顯示欄位
    protected $hidden = [
        'password'
    ];
    // 軟刪除
    //use SoftDeletes;
    // 是否自動待時間撮
    public $timestamps = true;
    // 時間撮保存格式
    //protected $dateFormat = 'U';
    // 自訂時間撮欄位
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // 多對多
    public function roles()
    {
        return $this->belongsToMany('App\Model\Admin\AdminRoles', 'admin_role_users', 'user_id', 'role_id');
    }
}
