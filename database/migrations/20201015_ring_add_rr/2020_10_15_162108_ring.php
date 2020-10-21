<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Ring extends Migration
{
    /**
     * Run the migrations.
     * 新版支付金額設定
     * 建立: php artisan make:migration ring --path="database/migrations/20201015_ring_add_rr"
     * 執行: php artisan migrate --path="database/migrations/20201015_ring_add_rr/"
     * 回復: php artisan migrate:rollback --path="database/migrations/20201015_ring_add_rr"
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_ranking_bookinfo', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->integer('ranking_id')->comment('排行ID');
            $table->integer('bookinfo_id')->comment('書籍ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_ranking_bookinfo');
    }
}
