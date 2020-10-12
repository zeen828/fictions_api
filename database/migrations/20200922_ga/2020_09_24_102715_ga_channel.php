<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GaChannel extends Migration
{
    /**
     * Run the migrations.
     * 每小時分析
     * 建立: php artisan make:migration ga_channel --path="database/migrations/20200922_ga"
     * 執行: php artisan migrate --path="database/migrations/20200922_ga/"
     * 回復: php artisan migrate:rollback --path="database/migrations/20200922_ga"
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_analysis_channel', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->integer('y')->default(0)->comment('年2020');
            $table->tinyInteger('m')->default(0)->comment('月1~12');
            $table->tinyInteger('d')->default(0)->comment('日1-31');
            $table->tinyInteger('h')->default(0)->comment('時1-24');
            $table->date('date')->nullable()->comment('日期');
            $table->integer('channel_id')->default(0)->comment('渠道ID');
            $table->integer('wap_user_acc')->default(0)->comment('每日訪問數');
            $table->integer('app_user_acc')->default(0)->comment('每日訪問數');
            $table->integer('wap_user_acc_hour')->default(0)->comment('每小時訪問數');
            $table->integer('app_user_acc_hour')->default(0)->comment('每小時訪問數');
            $table->integer('wap_user_reg')->default(0)->comment('註冊數');
            $table->integer('app_user_reg')->default(0)->comment('註冊數');
            $table->integer('wap_user_login')->default(0)->comment('登入數');
            $table->integer('app_user_login')->default(0)->comment('登入數');
            $table->integer('wap_order_all')->default(0)->comment('訂單數');
            $table->integer('app_order_all')->default(0)->comment('訂單數');
            $table->integer('wap_order_success')->default(0)->comment('成功訂單數');
            $table->integer('app_order_success')->default(0)->comment('成功訂單數');
            $table->decimal('wap_recharge', 10, 2)->default(0)->comment('充值金額');
            $table->decimal('app_recharge', 10, 2)->default(0)->comment('充值金額');
            $table->tinyInteger('status')->default(1)->comment('狀態(0:停用,1:啟用)');
            $table->timestamps();
            // 唯一
            $table->unique(['y', 'm', 'd', 'h', 'channel_id'], 'hour');
            // 索引
            $table->index(['y', 'm', 'd', 'h', 'date', 'channel_id', 'status'], 'query');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_analysis_channel');
    }
}
