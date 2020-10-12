<?php

namespace App\Model\Books;

use Illuminate\Database\Eloquent\Model;

class BookUserRead extends Model
{
    // 指定資料庫連線名稱
    protected $connection = 'mysql';
    // 資料庫名稱
    protected $table = 't_book_user_read';
    // 主鍵欄位
    protected $primaryKey = 'id';
    // 主鍵型態
    protected $keyType = 'int';
    // 欄位名稱
    protected $fillable = [
        'user_id', 'book_id', 'chapter_id',
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
        return $query->where('status', 1)->orderBy('id', 'ASC');
    }
}
