<?php

use Illuminate\Database\Seeder;

class OrderPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * composer dump-autoload
     * 建立: php artisan make:seeder OrderPaymentSeeder
     * 執行: php artisan db:seed --class=OrderPaymentSeeder
     * @return void
     */
    public function run()
    {
        //
        $now_date = date('Y-m-d h:i:s');

        DB::table('t_amounts')->truncate();
        DB::table('t_amounts')->insert([
            [
                'id' => 1,
                'name' => '30元',
                'description' => '',
                'price' => 30,
                'point_base' => 3000,
                'point_give' => 0,
                'points' => 3000,
                'point_cash' => 0,
                'vip' => 0,
                'vip_name' => '',
                'vip_day' => 0,
                'sort' => 1,
                'is_default' => 0,
                'status' => 1,
                'created_at' => $now_date,
                'updated_at' => $now_date
            ],
            [
                'id' => 2,
                'name' => '50元（多送30元）',
                'description' => '赠送3000金币',
                'price' => 50,
                'point_base' => 5000,
                'point_give' => 3000,
                'points' => 8000,
                'point_cash' => 0,
                'vip' => 0,
                'vip_name' => '',
                'vip_day' => 0,
                'sort' => 2,
                'is_default' => 1,
                'status' => 1,
                'created_at' => $now_date,
                'updated_at' => $now_date
            ],
            [
                'id' => 3,
                'name' => '100元（多送80元）',
                'description' => '赠送8000金币',
                'price' => 100,
                'point_base' => 10000,
                'point_give' => 8000,
                'points' => 18000,
                'point_cash' => 0,
                'vip' => 0,
                'vip_name' => '',
                'vip_day' => 0,
                'sort' => 3,
                'is_default' => 0,
                'status' => 1,
                'created_at' => $now_date,
                'updated_at' => $now_date
            ],
            [
                'id' => 4,
                'name' => '200元（多送200元）',
                'description' => '赠送20000金币',
                'price' => 200,
                'point_base' => 20000,
                'point_give' => 20000,
                'points' => 40000,
                'point_cash' => 0,
                'vip' => 0,
                'vip_name' => '',
                'vip_day' => 0,
                'sort' => 4,
                'is_default' => 0,
                'status' => 1,
                'created_at' => $now_date,
                'updated_at' => $now_date
            ],
            [
                'id' => 5,
                'name' => '99元',
                'description' => '',
                'price' => 99,
                'point_base' => 0,
                'point_give' => 0,
                'points' => 0,
                'point_cash' => 0,
                'vip' => 1,
                'vip_name' => '包季会员',
                'vip_day' => 90,
                'sort' => 1,
                'is_default' => 0,
                'status' => 1,
                'created_at' => $now_date,
                'updated_at' => $now_date
            ],
            [
                'id' => 6,
                'name' => '365元',
                'description' => '',
                'price' => 365,
                'point_base' => 0,
                'point_give' => 0,
                'points' => 0,
                'point_cash' => 0,
                'vip' => 1,
                'vip_name' => '包年会员',
                'vip_day' => 365,
                'sort' => 2,
                'is_default' => 0,
                'status' => 1,
                'created_at' => $now_date,
                'updated_at' => $now_date
            ]
        ]);

        DB::table('t_payments')->truncate();
        DB::table('t_payments')->insert([
            [
                'id' => 1,
                'name' => '測試-支付寶',
                'description' => '測試支付',
                'domain' => 'http://puapu1.htmjjj.com',
                'domain_call' => 'http://puapu1.htmjjj.com',
                'sdk' => 'alipay',
                'config' => '{}',
                'status' => 1,
                'created_at' => $now_date,
                'updated_at' => $now_date
            ],
            [
                'id' => 2,
                'name' => '測試-微信',
                'description' => '測試支付2',
                'domain' => 'http://puapu1.htmjjj.com',
                'domain_call' => 'http://puapu1.htmjjj.com',
                'sdk' => 'weixin',
                'config' => '{}',
                'status' => 1,
                'created_at' => $now_date,
                'updated_at' => $now_date
            ]
        ]);

        DB::table('t_payment_amount')->truncate();
        DB::table('t_payment_amount')->insert([
            [
                'id' => 1,
                'payment_id' => 1,
                'amount_id' => 1,
                'created_at' => $now_date,
                'updated_at' => $now_date
            ],
            [
                'id' => 2,
                'payment_id' => 1,
                'amount_id' => 2,
                'created_at' => $now_date,
                'updated_at' => $now_date
            ],
            [
                'id' => 3,
                'payment_id' => 1,
                'amount_id' => 3,
                'created_at' => $now_date,
                'updated_at' => $now_date
            ],
            [
                'id' => 4,
                'payment_id' => 1,
                'amount_id' => 4,
                'created_at' => $now_date,
                'updated_at' => $now_date
            ],
            [
                'id' => 5,
                'payment_id' => 1,
                'amount_id' => 5,
                'created_at' => $now_date,
                'updated_at' => $now_date
            ],
            [
                'id' => 6,
                'payment_id' => 1,
                'amount_id' => 6,
                'created_at' => $now_date,
                'updated_at' => $now_date
            ],
            [
                'id' => 7,
                'payment_id' => 2,
                'amount_id' => 5,
                'created_at' => $now_date,
                'updated_at' => $now_date
            ],
            [
                'id' => 8,
                'payment_id' => 2,
                'amount_id' => 6,
                'created_at' => $now_date,
                'updated_at' => $now_date
            ]
        ]);
    }
}
