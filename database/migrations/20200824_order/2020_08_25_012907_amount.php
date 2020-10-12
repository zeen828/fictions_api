<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Amount extends Migration
{
    /**
     * Run the migrations.
     * 新版支付金額設定
     * 建立: php artisan make:migration payment --path="database/migrations/20200824_order"
     * 執行: php artisan migrate --path="database/migrations/20200824_order/"
     * 回復: php artisan migrate:rollback --path="database/migrations/20200824_order"
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_amounts', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->string('name')->comment('名稱');
            $table->string('description')->nullable()->comment('描述');
            $table->integer('price')->default(0)->comment('金額');
            $table->integer('point_base')->default(0)->comment('基本點');
            $table->integer('point_give')->default(0)->comment('贈送點');
            $table->integer('points')->default(0)->comment('總點數');
            $table->integer('point_cash')->default(0)->comment('反利');
            $table->tinyInteger('vip')->default(0)->comment('VIP(0:停用,1:啟用)');
            $table->string('vip_name')->nullable()->comment('VIP名稱');
            $table->integer('vip_day')->default(0)->comment('VIP天數');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('is_default')->default(0)->comment('預設(0:停用,1:啟用)');
            $table->tinyInteger('status')->default(1)->comment('狀態(0:停用,1:啟用)');
            $table->timestamps();
            // 索引
            $table->index(['vip', 'status'], 'query');
        });

        Schema::create('t_payment_amount', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->integer('payment_id')->comment('支付ID');
            $table->integer('amount_id')->comment('金額ID');
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
        Schema::dropIfExists('t_amounts');
        Schema::dropIfExists('t_payment_amount');
    }
}
