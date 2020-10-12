<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChannelApk extends Migration
{
    /**
     * Run the migrations.
     * 渠道安卓包(apk包含linkid)
     * 建立: php artisan make:migration channel_apk --path="database/migrations/20200925_apk"
     * 執行: php artisan migrate --path="database/migrations/20200925_apk/"
     * 回復: php artisan migrate:rollback --path="database/migrations/20200925_apk"
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_channels_apk', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->integer('channel_id')->default(0)->comment('渠道ID');
            $table->integer('apk_id')->default(0)->comment('ApkID');
            $table->string('uri')->nullable()->comment('下載網址');
            $table->integer('download')->default(0)->comment('下載數紀錄');
            $table->tinyInteger('status')->default(0)->comment('狀態(0:停用,1:啟用)');
            $table->timestamps();
            // 索引
            $table->index(['channel_id', 'apk_id', 'status'], 'query');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_channels_apk');
    }
}
