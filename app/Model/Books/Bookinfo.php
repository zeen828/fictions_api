<?php

namespace App\Model\Books;

use Illuminate\Database\Eloquent\Model;

class Bookinfo extends Model
{
    // 指定資料庫連線名稱
    protected $connection = 'mysql';
    // 資料庫名稱
    protected $table = 't_bookinfo';
    // 主鍵欄位
    protected $primaryKey = 'id';
    // 主鍵型態
    protected $keyType = 'int';
    // 欄位名稱
    protected $fillable = [
        'mode', 'name', 'description', 'author', 'tags',
        'tid', 'cover', 'cover_h', 'size', 'nature',
        'new_at', 'end', 'open', 'free', 'recom',
        'recom_chapter_id', 'vip', 'search', 'click_w', 'click_m',
        'click_s', 'click_o', 'gid', 'index',
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

    // 多對多
    public function types()
    {
        return $this->belongsToMany('App\Model\Books\Booktype', 't_book_booktype', 'book_id', 'booktype_id');
    }

    // 一對多
    public function chapter()
    {
        return $this->hasMany('App\Model\Books\Bookchapter', 'book_id', 'id');
    }

    // 活耀條件
    public function scopeActive($query)
    {
        return $query->where('status', 1);
        //return $query->where('status', 1)->orderBy('id', 'ASC');
    }
}
