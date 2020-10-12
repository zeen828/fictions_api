<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Domain extends Migration
{
    /**
     * Run the migrations.
     * 新版訂單
     * 建立: php artisan make:migration order --path="database/migrations/20200902_domains"
     * 執行: php artisan migrate --path="database/migrations/20200902_domains/"
     * 回復: php artisan migrate:rollback --path="database/migrations/20200902_domains"
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_domains', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->tinyInteger('species')->default(1)->comment('種類(0:未知,1:動態主體,2APK下載');
            $table->tinyInteger('ssl')->default(0)->comment('ssl(0:無,1:有)');
            $table->tinyInteger('power')->default(0)->comment('高權(0:無,1:有)');
            $table->string('domain')->comment('域名');
            $table->text('remarks')->nullable()->comment('備註');
            $table->tinyInteger('cdn_del')->default(1)->comment('CDN(0:刪除,1:啟用)');
            $table->tinyInteger('status')->default(1)->comment('狀態(0:停用,1:啟用,2:備用)');
            $table->timestamps();
            // 索引
            $table->index(['domain', 'status'], 'query');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_domains');
    }
}
