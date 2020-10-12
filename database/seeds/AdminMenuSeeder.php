<?php

use Illuminate\Database\Seeder;

class AdminMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * composer dump-autoload
     * 建立: php artisan make:seeder AdminMenuSeeder
     * 執行: php artisan db:seed --class=AdminMenuSeeder
     * @return void
     */
    public function run()
    {
        //
        $now_date = date('Y-m-d h:i:s');
        // 目錄
        DB::table('admin_menu')->truncate();
        DB::table('admin_menu')->insert([
            //首頁
            ['id' => '1', 'parent_id' => '0', 'order' => '1', 'title' => 'Index', 'icon' => 'fa-dashboard', 'uri' => '/', 'created_at' => $now_date, 'updated_at' => $now_date],
            //system
            ['id' => '2', 'parent_id' => '0', 'order' => '901', 'title' => '系統', 'icon' => 'fa-users', 'uri' => NULL, 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '3', 'parent_id' => '2', 'order' => '902', 'title' => '用戶管理', 'icon' => 'fa-users', 'uri' => 'auth/users', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '4', 'parent_id' => '2', 'order' => '903', 'title' => '角色管理', 'icon' => 'fa-user', 'uri' => 'auth/roles', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '5', 'parent_id' => '2', 'order' => '904', 'title' => '權限管理', 'icon' => 'fa-ban', 'uri' => 'auth/permissions', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '6', 'parent_id' => '2', 'order' => '905', 'title' => '選單管理', 'icon' => 'fa-bars', 'uri' => 'auth/menu', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '7', 'parent_id' => '2', 'order' => '906', 'title' => '系統日誌', 'icon' => 'fa-history', 'uri' => 'auth/logs', 'created_at' => $now_date, 'updated_at' => $now_date],
            //小說11~20
            ['id' => '11', 'parent_id' => '0', 'order' => '11', 'title' => '會員', 'icon' => 'fa-user', 'uri' => 'fictions/users', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['id' => '12', 'parent_id' => '0', 'order' => '12', 'title' => '訂單', 'icon' => 'fa-diamond', 'uri' => 'fictions/orders', 'created_at' => $now_date, 'updated_at' => $now_date],
            //小說書籍21-30
            ['id' => '21', 'parent_id' => '0', 'order' => '21', 'title' => '書籍', 'icon' => 'fa-book', 'uri' => NULL, 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '22', 'parent_id' => '21', 'order' => '22', 'title' => '書籍管理', 'icon' => 'fa-book', 'uri' => 'fictions/books/book', 'created_at' => $now_date, 'updated_at' => $now_date],
                // ['id' => '23', 'parent_id' => '21', 'order' => '23', 'title' => '章節管理', 'icon' => 'fa-book', 'uri' => 'fictions/books/chapter', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '24', 'parent_id' => '21', 'order' => '24', 'title' => '分類管理', 'icon' => 'fa-bookmark-o', 'uri' => 'fictions/books/type', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '25', 'parent_id' => '21', 'order' => '25', 'title' => '排行管理', 'icon' => 'fa-bookmark', 'uri' => 'fictions/books/ranking', 'created_at' => $now_date, 'updated_at' => $now_date],
            //小說支付31-40
            ['id' => '31', 'parent_id' => '0', 'order' => '31', 'title' => '支付', 'icon' => 'fa-cc-mastercard', 'uri' => NULL, 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '32', 'parent_id' => '31', 'order' => '32', 'title' => '支付管理', 'icon' => 'fa-cc-mastercard', 'uri' => 'fictions/payments/payment', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '33', 'parent_id' => '31', 'order' => '33', 'title' => '金額管理', 'icon' => 'fa-cc-mastercard', 'uri' => 'fictions/payments/amount', 'created_at' => $now_date, 'updated_at' => $now_date],
            //小說域名41-50
            ['id' => '41', 'parent_id' => '0', 'order' => '41', 'title' => '域名管理', 'icon' => 'fa-chain', 'uri' => 'fictions/domains', 'created_at' => $now_date, 'updated_at' => $now_date],
            //小說推廣51-60
            ['id' => '51', 'parent_id' => '0', 'order' => '51', 'title' => '推廣', 'icon' => 'fa-feed', 'uri' => NULL, 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '52', 'parent_id' => '51', 'order' => '52', 'title' => '渠道管理', 'icon' => 'fa-code-fork', 'uri' => 'fictions/promotes/channel', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '53', 'parent_id' => '51', 'order' => '53', 'title' => '客戶端管理', 'icon' => 'fa-android', 'uri' => 'fictions/promotes/apk', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '54', 'parent_id' => '51', 'order' => '54', 'title' => '渠道包日誌', 'icon' => 'fa-android', 'uri' => 'fictions/promotes/sysapk', 'created_at' => $now_date, 'updated_at' => $now_date],
            //小說統計61-70
            ['id' => '61', 'parent_id' => '0', 'order' => '61', 'title' => '統計圖表', 'icon' => 'fa-area-chart', 'uri' => NULL, 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '62', 'parent_id' => '61', 'order' => '62', 'title' => '會員統計', 'icon' => 'fa-user', 'uri' => 'fictions/chart/user', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '63', 'parent_id' => '61', 'order' => '63', 'title' => '書籍統計', 'icon' => 'fa-book', 'uri' => 'fictions/chart/book', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '64', 'parent_id' => '61', 'order' => '64', 'title' => '渠道統計', 'icon' => 'fa-code-fork', 'uri' => 'fictions/chart/channel', 'created_at' => $now_date, 'updated_at' => $now_date],
        ]);
        // 角色目錄關係
        DB::table('admin_role_menu')->truncate();
        //DB::table('admin_role_menu')->whereIn('role_id', [1, 2])->delete();
        DB::table('admin_role_menu')->insert([
            // 1.系統管理員
            ['role_id' => '1', 'menu_id' => '1', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '1', 'menu_id' => '2', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '1', 'menu_id' => '11', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '1', 'menu_id' => '12', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '1', 'menu_id' => '21', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '1', 'menu_id' => '31', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '1', 'menu_id' => '41', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '1', 'menu_id' => '51', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '1', 'menu_id' => '61', 'created_at' => $now_date, 'updated_at' => $now_date],
            // 2.產品管理員
            ['role_id' => '2', 'menu_id' => '1', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '2', 'menu_id' => '2', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '2', 'menu_id' => '11', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '2', 'menu_id' => '12', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '2', 'menu_id' => '21', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '2', 'menu_id' => '31', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '2', 'menu_id' => '41', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '2', 'menu_id' => '51', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '2', 'menu_id' => '61', 'created_at' => $now_date, 'updated_at' => $now_date],
            // 3.技術組
            ['role_id' => '3', 'menu_id' => '1', 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => '3', 'menu_id' => '2', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '3', 'menu_id' => '11', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '3', 'menu_id' => '12', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '3', 'menu_id' => '21', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '3', 'menu_id' => '31', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '3', 'menu_id' => '41', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '3', 'menu_id' => '51', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '3', 'menu_id' => '61', 'created_at' => $now_date, 'updated_at' => $now_date],
            // 4.中國組
            ['role_id' => '4', 'menu_id' => '1', 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => '4', 'menu_id' => '2', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '4', 'menu_id' => '11', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '4', 'menu_id' => '12', 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => '4', 'menu_id' => '21', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '4', 'menu_id' => '31', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '4', 'menu_id' => '41', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '4', 'menu_id' => '51', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '4', 'menu_id' => '61', 'created_at' => $now_date, 'updated_at' => $now_date],
            // 5.財務組
            ['role_id' => '5', 'menu_id' => '1', 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => '5', 'menu_id' => '2', 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => '5', 'menu_id' => '11', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '5', 'menu_id' => '12', 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => '5', 'menu_id' => '21', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '5', 'menu_id' => '31', 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => '5', 'menu_id' => '41', 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => '5', 'menu_id' => '51', 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => '5', 'menu_id' => '61', 'created_at' => $now_date, 'updated_at' => $now_date],
            // 6.支付组
            ['role_id' => '6', 'menu_id' => '1', 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => '6', 'menu_id' => '2', 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => '6', 'menu_id' => '11', 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => '6', 'menu_id' => '12', 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => '6', 'menu_id' => '21', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '6', 'menu_id' => '31', 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => '6', 'menu_id' => '41', 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => '6', 'menu_id' => '51', 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => '6', 'menu_id' => '61', 'created_at' => $now_date, 'updated_at' => $now_date],
            // 7.書籍組
            ['role_id' => '7', 'menu_id' => '1', 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => '7', 'menu_id' => '2', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '7', 'menu_id' => '11', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '7', 'menu_id' => '12', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '7', 'menu_id' => '21', 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => '7', 'menu_id' => '31', 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => '7', 'menu_id' => '41', 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => '7', 'menu_id' => '51', 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => '7', 'menu_id' => '61', 'created_at' => $now_date, 'updated_at' => $now_date],
            // 8.維護組
            ['role_id' => '8', 'menu_id' => '1', 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => '8', 'menu_id' => '2', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '8', 'menu_id' => '11', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '8', 'menu_id' => '12', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '8', 'menu_id' => '21', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '8', 'menu_id' => '31', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '8', 'menu_id' => '41', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '8', 'menu_id' => '51', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '8', 'menu_id' => '61', 'created_at' => $now_date, 'updated_at' => $now_date],
        ]);
        // 權限
        DB::table('admin_permissions')->truncate();
        DB::table('admin_permissions')->insert([
            //system
            //可用號碼1~6(使用到:6)
            ['id' => '1', 'name' => '全部權限', 'slug' => '*', 'http_method' => '', 'http_path' => "*\r\n", 'created_at' => $now_date, 'updated_at' => $now_date],
            ['id' => '2', 'name' => '儀表板', 'slug' => 'dashboard', 'http_method' => 'GET', 'http_path' => "/dashboard\r\n", 'created_at' => $now_date, 'updated_at' => $now_date],
            ['id' => '3', 'name' => '登入登出', 'slug' => 'auth.login', 'http_method' => '', 'http_path' => "/auth/login\r\n/auth/logout\r\n", 'created_at' => $now_date, 'updated_at' => $now_date],
            ['id' => '4', 'name' => '會員設定', 'slug' => 'auth.setting', 'http_method' => 'GET,PUT', 'http_path' => "/auth/setting\r\n", 'created_at' => $now_date, 'updated_at' => $now_date],
            ['id' => '5', 'name' => '系統管理', 'slug' => 'auth.management', 'http_method' => '', 'http_path' => "/auth/users\r\n/auth/users/*\r\n/auth/roles\r\n/auth/roles/*\r\n/auth/permissions\r\n/auth/permissions/*\r\n/auth/menu\r\n/auth/menu/*\r\n/auth/logs\r\n", 'created_at' => $now_date, 'updated_at' => $now_date],
            //小說
            ['id' => '6', 'name' => '小說管理', 'slug' => 'fictions.set', 'http_method' => '', 'http_path' => "/fictions/users\r\n/fictions/users/*\r\n/fictions/orders\r\n/fictions/domains\r\n/fictions/domains/*\r\n", 'created_at' => $now_date, 'updated_at' => $now_date],
            //小說-書籍
            ['id' => '7', 'name' => '小說書籍管理', 'slug' => 'fictions.books', 'http_method' => '', 'http_path' => "/fictions/books/book\r\n/fictions/books/book/*\r\n/fictions/books/chapter\r\n/fictions/books/chapter/*\r\n/fictions/books/type\r\n/fictions/books/type/*\r\n/fictions/books/ranking\r\n/fictions/books/ranking/*\r\n", 'created_at' => $now_date, 'updated_at' => $now_date],
            //小說-支付
            ['id' => '8', 'name' => '小說支付管理', 'slug' => 'fictions.payments', 'http_method' => '', 'http_path' => "/fictions/payments/payment\r\n/fictions/payments/payment/*\r\n/fictions/payments/amount\r\n/fictions/payments/amount/*\r\n", 'created_at' => $now_date, 'updated_at' => $now_date],
            //小說-推廣
            ['id' => '9', 'name' => '小說推廣管理', 'slug' => 'fictions.promotes', 'http_method' => '', 'http_path' => "/fictions/promotes/channel\r\n/fictions/promotes/channel/*\r\n/fictions/promotes/apk\r\n/fictions/promotes/apk/*\r\n/fictions/promotes/sysapk\r\n/fictions/promotes/sysapk/*\r\n", 'created_at' => $now_date, 'updated_at' => $now_date],
            //小說-統計
            ['id' => '10', 'name' => '小說統計管理', 'slug' => 'fictions.analysis', 'http_method' => '', 'http_path' => "/fictions/chart/user\r\n/fictions/chart/user/*\r\n/fictions/chart/book\r\n/fictions/chart/book/*\r\n/fictions/chart/channel\r\n/fictions/chart/channel/*\r\n", 'created_at' => $now_date, 'updated_at' => $now_date],
        ]);
        //重設基礎角色的權限
        DB::table('admin_role_permissions')->truncate();
        //DB::table('admin_role_permissions')->whereIn('role_id', [1, 2])->delete();
        DB::table('admin_role_permissions')->insert([
            // 1:系統管理員
            ['role_id' => 1, 'permission_id' => 1, 'created_at' => $now_date, 'updated_at' => $now_date],
            // 2:產品管理員
            ['role_id' => 2, 'permission_id' => 2, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 2, 'permission_id' => 3, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 2, 'permission_id' => 4, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 2, 'permission_id' => 5, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 2, 'permission_id' => 6, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 2, 'permission_id' => 7, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 2, 'permission_id' => 8, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 2, 'permission_id' => 9, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 2, 'permission_id' => 10, 'created_at' => $now_date, 'updated_at' => $now_date],
            // 3.技術組
            ['role_id' => 3, 'permission_id' => 2, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 3, 'permission_id' => 3, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 3, 'permission_id' => 4, 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => 3, 'permission_id' => 5, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 3, 'permission_id' => 6, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 3, 'permission_id' => 7, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 3, 'permission_id' => 8, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 3, 'permission_id' => 9, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 3, 'permission_id' => 10, 'created_at' => $now_date, 'updated_at' => $now_date],
            // 4.中國組
            ['role_id' => 4, 'permission_id' => 2, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 4, 'permission_id' => 3, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 4, 'permission_id' => 4, 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => 4, 'permission_id' => 5, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 4, 'permission_id' => 6, 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => 4, 'permission_id' => 7, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 4, 'permission_id' => 8, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 4, 'permission_id' => 9, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 4, 'permission_id' => 10, 'created_at' => $now_date, 'updated_at' => $now_date],
            // 5.財務組
            ['role_id' => 5, 'permission_id' => 2, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 5, 'permission_id' => 3, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 5, 'permission_id' => 4, 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => 5, 'permission_id' => 5, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 5, 'permission_id' => 6, 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => 5, 'permission_id' => 7, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 5, 'permission_id' => 8, 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => 5, 'permission_id' => 9, 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => 5, 'permission_id' => 10, 'created_at' => $now_date, 'updated_at' => $now_date],
            // 6.支付组
            ['role_id' => 6, 'permission_id' => 2, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 6, 'permission_id' => 3, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 6, 'permission_id' => 4, 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => 6, 'permission_id' => 5, 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => 6, 'permission_id' => 6, 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => 6, 'permission_id' => 7, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 6, 'permission_id' => 8, 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => 6, 'permission_id' => 9, 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => 6, 'permission_id' => 10, 'created_at' => $now_date, 'updated_at' => $now_date],
            // 7.書籍組
            ['role_id' => 7, 'permission_id' => 2, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 7, 'permission_id' => 3, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 7, 'permission_id' => 4, 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => 7, 'permission_id' => 5, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 7, 'permission_id' => 6, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 7, 'permission_id' => 7, 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => 7, 'permission_id' => 8, 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => 7, 'permission_id' => 9, 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => 7, 'permission_id' => 10, 'created_at' => $now_date, 'updated_at' => $now_date],
            // 8.維護組
            ['role_id' => 8, 'permission_id' => 2, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 8, 'permission_id' => 3, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 8, 'permission_id' => 4, 'created_at' => $now_date, 'updated_at' => $now_date],
            //['role_id' => 8, 'permission_id' => 5, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 8, 'permission_id' => 6, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 8, 'permission_id' => 7, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 8, 'permission_id' => 8, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 8, 'permission_id' => 9, 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => 8, 'permission_id' => 10, 'created_at' => $now_date, 'updated_at' => $now_date],
        ]);
    }
}
