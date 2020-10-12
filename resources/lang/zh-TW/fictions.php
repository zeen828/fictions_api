<?php

return [
    'id' => '主鍵ID',
    // 會員
    'user'                  => [
        'page_title' => '會員',
        'account' => '帳號',
        'password' => '密碼',
        'nick_name' => '暱稱',
        'phone' => '行動電話',
        'sex' => '性別',
        'points' => '點數',
        'channel_id' => '渠道',
        'link_id' => '推廣id',
        'vip' => 'VIP',
        'vip_at' => 'VIP到期時間',
        'remarks' => '備註',
        'token_jwt' => 'JWT Token',
        'remember_token' => 'Token',
    ],
    // 書籍
    'bookinfo' => [
        'page_title' => '書籍',
        'mode' => '模式(0:OSS,1:文字,2:圖片,3:音頻)',
        'name' => '名稱',
        'description' => '描述',
        'author' => '作者',
        'tags' => '標籤',
        'tid' => '分類',
        'cover' => '封面',
        'size' => '字數',
        'nature' => '性質(0:男頻,1:女頻,2:中性)',
        'new_at' => '新書日期',
        'end' => '完結(0:連載,1:完結)',
        'open' => '完全上架(0:還有章節未開放,1:完全開放)',
        'free' => '免費(0:停用,1:啟用)',
        'recom' => '推薦(0:停用,1:啟用)',
        'recom_chapter_id' => '推薦章節',
        'vip' => 'VIP專屬(0:普通,1:VIP專屬)',
        'search' => '搜尋(0:全戰搜,1:前台不可,2:後台不可,3:全站不可)',
        'click_w' => '周點擊',
        'click_m' => '月點擊',
        'click_s' => '總點擊',
        'gid' => '所属规则id?',
        'index' => '派單指數?',
        'status' => '狀態(0:停用,1:啟用,2:待審)',
    ],
    // 章節
    'bookchapter' => [
        'page_title' => '章節',
        'book_id' => '書籍ID',
        'name' => '名稱',
        'content' => '內容',
        'description' => '描述',
        'next_description' => '下章節描述',
        'oss_route' => 'OSS路徑',
        'free' => '免費(0:停用,1:啟用)',
        'money' => '點數',
        'sort' => '排序',
    ],
    // 書籍分類
    'booktype' => [
        'page_title' => '書籍分類',
        'name' => '名稱',
        'description' => '描述',
        'sex' => '性別',
        'color' => '顏色',
        'sort' => '排序',
        'status' => '狀態',
    ],
    // 排行
    'ranking' => [
        'page_title' => '排行',
        'name' => '名稱',
        'book_id' => '書擊ID',
        'random_title' => '隨機標題',
        'random_tag' => '隨機標籤',
    ],
    // 支付
    'payment' => [
        'page_title' => '支付',
        'name' => '名稱',
        'description' => '描述',
        'domain' => '支付域名',
        'domain_call' => '回調域名',
        'sdk' => 'sdk',
        'sdk2' => 'sdk2',
        'limit' => '支付限額',
        'ratio' => '贈送比',
        'client' => '客戶端',
        'float' => '浮動',
        'min' => '最小金額',
        'max' => '最大金額',
        'config' => '額外設定',
    ],
    // 金額
    'amount' => [
        'page_title' => '金額',
        'name' => '名稱',
        'description' => '描述',
        'price' => '金額',
        'point_base' => '基本點',
        'point_give' => '贈送點',
        'points' => '總點數',
        'point_cash' => '反利',
        'vip' => 'VIP',
        'vip_name' => 'VIP名稱',
        'vip_day' => 'VIP天數',
        'sort' => '排序',
        'is_default' => '預設',
    ],
    // 域名
    'domain' => [
        'page_title' => '域名',
        'species' => '種類',
        'ssl' => 'ssl',
        'power' => '高權',
        'domain' => '域名',
        'remarks' => '備註',
        'cdn_del' => 'CDN',
    ],
    // 訂單
    'order' => [
        'page_title' => '頂單',
        'user_id' => '會員ID',
        'payment_id' => '支付ID',
        'order_sn' => '訂單',
        'price' => '金額',
        'point_old' => '儲點前',
        'points' => '點數',
        'point_new' => '儲點後',
        'vip' => 'VIP',
        'vip_at_old' => '原本VIP到期時間',
        'vip_day' => 'VIP天數',
        'vip_at_new' => '儲值後VIP到期時間',
        'transaction_sn' => '交易訂單',
        'transaction_at' => '交易完成時間',
        'app' => 'APP',
        'linkid' => '推廣',
        'callbackUrl' => '支付返回',
        'sdk' => 'SDK',
        'config' => '支付商設定',
    ],
    // 渠道
    'channel' => [
        'mode' => '推廣模式',
        'name' => '名稱',
        'description' => '描述',
        'book_id' => '書籍ID',
        'chapter_id' => '章節ID',
        'prefix' => 'APK前墜',
        'url' => '自訂網址',
        'wap_user_reg' => '網頁成效紀錄(註冊數)',
        'app_user_reg' => 'APP成效紀錄(註冊數)',
        'wap_recharge' => '網頁儲值總額紀錄(充值金額)',
        'app_recharge' => 'APP儲值總額紀錄(充值金額)',
        'wap_recharge_m' => '網頁儲值月額紀錄(充值金額)',
        'app_recharge_m' => 'APP儲值月額紀錄(充值金額)',
        'wap_recharge_d' => '網頁儲值日額紀錄(充值金額)',
        'app_recharge_d' => 'APP儲值日額紀錄(充值金額)',
        'download' => '下載數紀錄',
        'default' => '預設渠道',
    ],
    // 原生包
    'apk' => [
        'version' => '版號',
        'app_version' => 'APP版號',
        'description' => '描述',
        'apk' => '檔案',
    ],
    // 渠道包
    'channelsapk' => [
        'channel_id' => '渠道ID',
        'apk_id' => '原生包ID',
        'uri' => '下載網址',
        'download' => '下載數紀錄',
    ],
    'status'                => '狀態',
    'created_at'            => '創建時間',
    'updated_at'            => '更新時間',
];