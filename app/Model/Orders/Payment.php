<?php

namespace App\Model\Orders;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    // 指定資料庫連線名稱
    protected $connection = 'mysql';
    // 資料庫名稱
    protected $table = 't_payments';
    // 主鍵欄位
    protected $primaryKey = 'id';
    // 主鍵型態
    protected $keyType = 'int';
    // 欄位名稱
    protected $fillable = [
        'name', 'description', 'domain', 'domain_call', 'sdk',
        'sdk2', 'limit', 'ratio', 'client', 'float',
        'min', 'max', 'config',
        'status', 'created_at', 'updated_at',
    ];
    // 隱藏不顯示欄位
    //protected $hidden = [];
    // 軟刪除
    //use SoftDeletes;
    // 是否自動待時間撮
    public $timestamps = true;
    // 時間撮保存格式
    //protected $dateFormat = 'Y-m-d H:i:s';
    // 自訂時間撮欄位
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // 配合laravel-admin表般JASON(例子:支付設定)
    protected $casts = [
        'config' => 'json',
    ];

    // 多對多
    public function amount()
    {
        return $this->belongsToMany('App\Model\Orders\Amount', 't_payment_amount', 'payment_id', 'amount_id');
    }

    // 活耀條件
    public function scopeActive($query)
    {
        return $query->where('status', 1)->orderBy('id', 'ASC');
    }
}
