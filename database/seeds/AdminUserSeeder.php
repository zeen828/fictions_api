<?php

use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * composer dump-autoload
     * 建立: php artisan make:seeder AdminUserSeeder
     * 執行: php artisan db:seed --class=AdminUserSeeder
     * @return void
     */
    public function run()
    {
        //
        $now_date = date('Y-m-d h:i:s');
        // 會員
        DB::table('admin_users')->truncate();
        DB::table('admin_users')->insert([
            // '$2y$10$AGoABU.FCVwYYcjmpwWgrOUt.JD6wkUGCit94bt5XWLFo1Qu9lJB6' // admin
            // '$2y$10$6kH7h.rCxsiGYFCs0X1djeJgLBOLvguH6.pEz02hVsmvmd3rrIani' // qaz123
            // '$2y$10$1jVk1vc4mEotR1K7Ffq9HeGT32Vgm1KZ/fX9oADvjkj4APbMfKkLu' // 123456
            // '$2y$10$CGXQPubZPs90pZSuGVslI.zTQqp0DkS.H1enmV/zomqpgGxeQfd6K' // 987654
            // 系統管理員
            ['id' => '1', 'username' => 'websystem', 'password' => '$2y$10$6kH7h.rCxsiGYFCs0X1djeJgLBOLvguH6.pEz02hVsmvmd3rrIani', 'name' => '網站管理員', 'created_at' => $now_date, 'updated_at' => $now_date],// qaz123
            // 產品管理員
            ['id' => '2', 'username' => 'admin', 'password' => '$2y$10$1jVk1vc4mEotR1K7Ffq9HeGT32Vgm1KZ/fX9oADvjkj4APbMfKkLu', 'name' => '產品管理員', 'created_at' => $now_date, 'updated_at' => $now_date],// 123456
            //['id' => '', 'username' => '', 'password' => '$2y$10$1jVk1vc4mEotR1K7Ffq9HeGT32Vgm1KZ/fX9oADvjkj4APbMfKkLu', 'name' => '', 'created_at' => $now_date, 'updated_at' => $now_date],// 123456
            // 技術組
            ['id' => '3', 'username' => 'will', 'password' => '$2y$10$1jVk1vc4mEotR1K7Ffq9HeGT32Vgm1KZ/fX9oADvjkj4APbMfKkLu', 'name' => 'Will', 'created_at' => $now_date, 'updated_at' => $now_date],// 123456
            ['id' => '4', 'username' => 'jason', 'password' => '$2y$10$1jVk1vc4mEotR1K7Ffq9HeGT32Vgm1KZ/fX9oADvjkj4APbMfKkLu', 'name' => 'Jason', 'created_at' => $now_date, 'updated_at' => $now_date],// 123456
            ['id' => '5', 'username' => 'rex', 'password' => '$2y$10$1jVk1vc4mEotR1K7Ffq9HeGT32Vgm1KZ/fX9oADvjkj4APbMfKkLu', 'name' => '君達', 'created_at' => $now_date, 'updated_at' => $now_date],// 123456
            ['id' => '6', 'username' => 'Jethro', 'password' => '$2y$10$1jVk1vc4mEotR1K7Ffq9HeGT32Vgm1KZ/fX9oADvjkj4APbMfKkLu', 'name' => '長青', 'created_at' => $now_date, 'updated_at' => $now_date],// 123456
            ['id' => '7', 'username' => 'ajia', 'password' => '$2y$10$1jVk1vc4mEotR1K7Ffq9HeGT32Vgm1KZ/fX9oADvjkj4APbMfKkLu', 'name' => '阿甲', 'created_at' => $now_date, 'updated_at' => $now_date],// 123456
            ['id' => '8', 'username' => 'howard', 'password' => '$2y$10$1jVk1vc4mEotR1K7Ffq9HeGT32Vgm1KZ/fX9oADvjkj4APbMfKkLu', 'name' => 'Howard', 'created_at' => $now_date, 'updated_at' => $now_date],// 123456
            ['id' => '9', 'username' => 'timo', 'password' => '$2y$10$1jVk1vc4mEotR1K7Ffq9HeGT32Vgm1KZ/fX9oADvjkj4APbMfKkLu', 'name' => 'Timo', 'created_at' => $now_date, 'updated_at' => $now_date],// 123456
            // 中國組
            ['id' => '10', 'username' => 'mrpeng', 'password' => '$2y$10$CGXQPubZPs90pZSuGVslI.zTQqp0DkS.H1enmV/zomqpgGxeQfd6K', 'name' => '彭總', 'created_at' => $now_date, 'updated_at' => $now_date],// 987654
            // 財務組
            ['id' => '11', 'username' => 'finance1', 'password' => '$2y$10$CGXQPubZPs90pZSuGVslI.zTQqp0DkS.H1enmV/zomqpgGxeQfd6K', 'name' => '財務1', 'created_at' => $now_date, 'updated_at' => $now_date],// 123456
            // 支付组
            ['id' => '12', 'username' => 'payadmin', 'password' => '$2y$10$CGXQPubZPs90pZSuGVslI.zTQqp0DkS.H1enmV/zomqpgGxeQfd6K', 'name' => '財務1', 'created_at' => $now_date, 'updated_at' => $now_date],// 123456
            // 書籍組
            ['id' => '13', 'username' => 'book1', 'password' => '$2y$10$1jVk1vc4mEotR1K7Ffq9HeGT32Vgm1KZ/fX9oADvjkj4APbMfKkLu', 'name' => '書籍1', 'created_at' => $now_date, 'updated_at' => $now_date],// 123456
            // 維護組
            ['id' => '14', 'username' => 'wehu0', 'password' => '$2y$10$1jVk1vc4mEotR1K7Ffq9HeGT32Vgm1KZ/fX9oADvjkj4APbMfKkLu', 'name' => '怡年', 'created_at' => $now_date, 'updated_at' => $now_date],// 123456
            ['id' => '15', 'username' => 'wehu1', 'password' => '$2y$10$1jVk1vc4mEotR1K7Ffq9HeGT32Vgm1KZ/fX9oADvjkj4APbMfKkLu', 'name' => '威廷', 'created_at' => $now_date, 'updated_at' => $now_date],// 123456
            ['id' => '16', 'username' => 'wehu2', 'password' => '$2y$10$1jVk1vc4mEotR1K7Ffq9HeGT32Vgm1KZ/fX9oADvjkj4APbMfKkLu', 'name' => '姿沛', 'created_at' => $now_date, 'updated_at' => $now_date],// 123456
            ['id' => '17', 'username' => 'wehu3', 'password' => '$2y$10$1jVk1vc4mEotR1K7Ffq9HeGT32Vgm1KZ/fX9oADvjkj4APbMfKkLu', 'name' => '律東', 'created_at' => $now_date, 'updated_at' => $now_date],// 123456
            ['id' => '18', 'username' => 'wehu4', 'password' => '$2y$10$1jVk1vc4mEotR1K7Ffq9HeGT32Vgm1KZ/fX9oADvjkj4APbMfKkLu', 'name' => '星賜', 'created_at' => $now_date, 'updated_at' => $now_date],// 123456
            ['id' => '19', 'username' => 'wehu5', 'password' => '$2y$10$1jVk1vc4mEotR1K7Ffq9HeGT32Vgm1KZ/fX9oADvjkj4APbMfKkLu', 'name' => '雅君', 'created_at' => $now_date, 'updated_at' => $now_date],// 123456
            ['id' => '20', 'username' => 'wehu6', 'password' => '$2y$10$1jVk1vc4mEotR1K7Ffq9HeGT32Vgm1KZ/fX9oADvjkj4APbMfKkLu', 'name' => '欣如', 'created_at' => $now_date, 'updated_at' => $now_date],// 123456
            ['id' => '21', 'username' => 'wehu7', 'password' => '$2y$10$1jVk1vc4mEotR1K7Ffq9HeGT32Vgm1KZ/fX9oADvjkj4APbMfKkLu', 'name' => '郁婷', 'created_at' => $now_date, 'updated_at' => $now_date],// 123456
        ]);
        // 角色
        DB::table('admin_roles')->truncate();
        DB::table('admin_roles')->insert([
            ['id' => '1', 'name' => '系統管理員', 'slug' => 'systemadmin', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['id' => '2', 'name' => '產品管理員', 'slug' => 'productadmin', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['id' => '3', 'name' => '技術組', 'slug' => 'technologyGroup', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['id' => '4', 'name' => '中國組', 'slug' => 'chinaGroup', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['id' => '5', 'name' => '財務組', 'slug' => 'financeGroup', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['id' => '6', 'name' => '支付组	', 'slug' => 'setpayGroup', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['id' => '7', 'name' => '書籍組', 'slug' => 'bookGroup', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['id' => '8', 'name' => '維護組', 'slug' => 'maintainGroup', 'created_at' => $now_date, 'updated_at' => $now_date],
            
        ]);
        // 角色&會員關係
        DB::table('admin_role_users')->truncate();
        DB::table('admin_role_users')->insert([
            // 系統管理員
            ['role_id' => '1', 'user_id' => '1', 'created_at' => $now_date, 'updated_at' => $now_date],
            // 產品管理員
            ['role_id' => '2', 'user_id' => '2', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '2', 'user_id' => '3', 'created_at' => $now_date, 'updated_at' => $now_date],//will
            ['role_id' => '2', 'user_id' => '4', 'created_at' => $now_date, 'updated_at' => $now_date],//jason
            ['role_id' => '2', 'user_id' => '5', 'created_at' => $now_date, 'updated_at' => $now_date],//君達
            //['role_id' => '', 'user_id' => '', 'created_at' => $now_date, 'updated_at' => $now_date],
            // 技術組
            ['role_id' => '3', 'user_id' => '3', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '3', 'user_id' => '4', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '3', 'user_id' => '5', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '3', 'user_id' => '6', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '3', 'user_id' => '7', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '3', 'user_id' => '8', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '3', 'user_id' => '9', 'created_at' => $now_date, 'updated_at' => $now_date],
            // 中國組
            ['role_id' => '4', 'user_id' => '10', 'created_at' => $now_date, 'updated_at' => $now_date],
            // 財務組
            ['role_id' => '5', 'user_id' => '11', 'created_at' => $now_date, 'updated_at' => $now_date],
            // 支付组
            ['role_id' => '6', 'user_id' => '12', 'created_at' => $now_date, 'updated_at' => $now_date],
            // 書籍組
            ['role_id' => '7', 'user_id' => '13', 'created_at' => $now_date, 'updated_at' => $now_date],
            // 維護組
            ['role_id' => '8', 'user_id' => '14', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '8', 'user_id' => '15', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '8', 'user_id' => '16', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '8', 'user_id' => '17', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '8', 'user_id' => '18', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '8', 'user_id' => '19', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '8', 'user_id' => '20', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['role_id' => '8', 'user_id' => '21', 'created_at' => $now_date, 'updated_at' => $now_date],
        ]);
    }
}
