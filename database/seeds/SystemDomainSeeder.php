<?php

use Illuminate\Database\Seeder;

class SystemDomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * composer dump-autoload
     * 建立: php artisan make:seeder SystemDomainSeeder
     * 執行: php artisan db:seed --class=SystemDomainSeeder
     * @return void
     */
    public function run()
    {
        //
        $now_date = date('Y-m-d h:i:s');

        DB::table('t_domains')->truncate();
        DB::table('t_domains')->insert([
            [
                'id' => '1',
                'species' => '1',
                'ssl' => '0',
                'power' => '0',
                'domain' => 'www.haoyuewl.com',
                'remarks' => NULL,
                'cdn_del' => '0',
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date
            ],
            [
                'id' => '2',
                'species' => '1',
                'ssl' => '0',
                'power' => '0',
                'domain' => 'www.nalemai.com',
                'remarks' => NULL,
                'cdn_del' => '0',
                'status' => '2',
                'created_at' => $now_date,
                'updated_at' => $now_date
            ]
        ]);
    }
}
