<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserAssLog extends Migration
{
    /**
     * Run the migrations.
     * 
     * 建立: php artisan make:migration user_ass_log --path="database/migrations/20201007_log"
     * 執行: php artisan migrate --path="database/migrations/20201007_log/"
     * 回復: php artisan migrate:rollback --path="database/migrations/20201007_log"
     * 
     * @return void
     */
    public function up()
    {
        Schema::create('t_logs_users_access', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->tinyInteger('mode')->default(0)->comment('模式(0:日活耀,1:每小時活耀)');
            $table->integer('app')->default(0)->comment('APP(1:WAP,2:APP)');
            $table->integer('channel_id')->default(0)->comment('渠道id');
            $table->integer('link_id')->default(0)->comment('推廣id');
            $table->tinyInteger('status')->default(1)->comment('狀態(0:停用,1:啟用)');
            $table->timestamps();
            // 索引
            $table->index(['mode', 'app', 'link_id', 'status'], 'query');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_logs_users_access');
    }
}
