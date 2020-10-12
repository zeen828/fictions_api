<?php

namespace App\Model\Analysis;

use Illuminate\Database\Eloquent\Model;

class AnalysisChannel extends Model
{
    // 指定資料庫連線名稱
    protected $connection = 'mysql';
    // 資料庫名稱
    protected $table = 't_analysis_channel';
    // 主鍵欄位
    protected $primaryKey = 'id';
    // 主鍵型態
    protected $keyType = 'int';
    // 欄位名稱
    protected $fillable = [
        'y', 'm', 'd', 'h', 'date',
        'channel_id',
        'wap_user_acc', 'app_user_acc',
        'wap_user_acc_hour', 'app_user_acc_hour',
        'wap_user_reg', 'app_user_reg',
        'wap_user_login', 'app_user_login',
        'wap_order_all', 'app_order_all',
        'wap_order_success', 'app_order_success',
        'wap_recharge', 'app_recharge',
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

    // 活耀條件
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
