<?php

namespace App\Model\Orders;

use Illuminate\Database\Eloquent\Model;

class Amount extends Model
{
    // 指定資料庫連線名稱
    protected $connection = 'mysql';
    // 資料庫名稱
    protected $table = 't_amounts';
    // 主鍵欄位
    protected $primaryKey = 'id';
    // 主鍵型態
    protected $keyType = 'int';
    // 欄位名稱
    protected $fillable = [
        'name', 'description', 'price', 'point_base', 'point_give',
        'points', 'point_cash', 'vip', 'vip_name', 'vip_day',
        'sort', 'is_default',
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

    // 多對多
    public function payment()
    {
        return $this->belongsToMany('App\Model\Orders\Payment', 't_payment_amount', 'amount_id', 'payment_id');
    }

    // 活耀條件
    public function scopeActive($query)
    {
        return $query->where('status', 1)->orderBy('sort', 'ASC');
    }
}
