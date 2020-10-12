<?php

namespace App\Model\Orders;

use Illuminate\Database\Eloquent\Model;

class PointLog extends Model
{
    // 指定資料庫連線名稱
    protected $connection = 'mysql';
    // 資料庫名稱
    protected $table = 't_point_log';
    // 主鍵欄位
    protected $primaryKey = 'id';
    // 主鍵型態
    protected $keyType = 'int';
    // 欄位名稱
    protected $fillable = [
        'user_id', 'book_id', 'chapter_id', 'event', 'point_old',
        'point', 'point_new', 'remarks',
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

    // 一對一
    public function book()
    {
        return $this->hasOne('App\Model\Books\Bookinfo', 'id', 'book_id');
    }

    // 一對一
    public function chapter()
    {
        return $this->hasOne('App\Model\Books\Bookchapter', 'id', 'chapter_id');
    }

    // 活耀條件
    public function scopeActive($query)
    {
        return $query->where('status', 1)->orderBy('id', 'DESC');
    }
}
