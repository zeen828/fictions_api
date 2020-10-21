<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Channel extends Migration
{
    /**
     * Run the migrations.
     * 每小時分析
     * 建立: php artisan make:migration channel --path="database/migrations/20200924_channel"
     * 執行: php artisan migrate --path="database/migrations/20200924_channel/"
     * 回復: php artisan migrate:rollback --path="database/migrations/20200924_channel"
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_channels', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->string('name')->comment('名稱');
            $table->text('description')->nullable()->comment('描述');
            $table->tinyInteger('mode')->default(0)->comment('推廣模式(0:首頁,1:書籍,2:章節,3:自訂)');
            $table->integer('book_id')->default(0)->comment('書籍ID');
            $table->integer('chapter_id')->default(0)->comment('章節ID');
            $table->string('url')->nullable()->comment('自訂網址');
            $table->string('prefix')->nullable()->comment('APK前墜');
            $table->integer('wap_user_reg')->default(0)->comment('網頁成效紀錄(註冊數)');
            $table->integer('app_user_reg')->default(0)->comment('APP成效紀錄(註冊數)');
            $table->integer('wap_recharge')->default(0)->comment('網頁儲值總額紀錄(充值金額)');
            $table->integer('app_recharge')->default(0)->comment('APP儲值總額紀錄(充值金額)');
            $table->integer('wap_recharge_m')->default(0)->comment('網頁儲值月額紀錄(充值金額)');
            $table->integer('app_recharge_m')->default(0)->comment('APP儲值月額紀錄(充值金額)');
            $table->integer('wap_recharge_d')->default(0)->comment('網頁儲值日額紀錄(充值金額)');
            $table->integer('app_recharge_d')->default(0)->comment('APP儲值日額紀錄(充值金額)');
            $table->integer('download')->default(0)->comment('下載數紀錄');
            $table->tinyInteger('for')->default(0)->comment('迴圈(0:停用,1:啟用)');
            $table->tinyInteger('default')->default(0)->comment('預設渠道(0:停用,1:啟用)');
            $table->tinyInteger('status')->default(1)->comment('狀態(0:停用,1:啟用)');
            $table->timestamps();
            // 索引
            $table->index(['mode', 'default', 'status'], 'query');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_channels');
    }
}
