<?php

namespace App\Model\Rankings;

use Illuminate\Database\Eloquent\Model;

class RankingBooks extends Model
{
    // 指定資料庫連線名稱
    protected $connection = 'mysql';
    // 資料庫名稱
    protected $table = 't_ranking_bookinfo';
    // 主鍵欄位
    protected $primaryKey = 'id';
    // 主鍵型態
    protected $keyType = 'int';
    // 欄位名稱
    protected $fillable = [
        'ranking_id', 'bookinfo_id',
        'created_at', 'updated_at'
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

    // 一對一
    public function ranking()
    {
        return $this->hasOne('App\Model\Rankings\Ranking', 'id', 'ranking_id');
    }

    // 一對一
    public function book()
    {
        return $this->hasOne('App\Model\Books\Bookinfo', 'id', 'bookinfo_id');
    }
}
