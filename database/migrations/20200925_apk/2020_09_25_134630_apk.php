<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Apk extends Migration
{
    /**
     * Run the migrations.
     * 原生安卓包
     * 建立: php artisan make:migration apk --path="database/migrations/20200925_apk"
     * 執行: php artisan migrate --path="database/migrations/20200925_apk/"
     * 回復: php artisan migrate:rollback --path="database/migrations/20200925_apk"
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_apk', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->string('version')->comment('版號');
            $table->string('app_version')->comment('APP版號');
            $table->text('description')->nullable()->comment('描述');
            $table->string('apk')->nullable()->comment('檔案');
            $table->tinyInteger('status')->default(1)->comment('狀態(0:停用,1:啟用)');
            $table->timestamps();
            // 索引
            $table->index(['version', 'app_version', 'status'], 'query');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_apk');
    }
}
