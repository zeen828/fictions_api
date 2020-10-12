<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class User extends Migration
{
    /**
     * Run the migrations.
     * 新版會員
     * 建立: php artisan make:migration user --path="database/migrations/20200818_user"
     * 執行: php artisan migrate --path="database/migrations/20200818_user/"
     * 回復: php artisan migrate:rollback --path="database/migrations/20200818_user"
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_users', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            //$table->string('phone', 20)->unique()->comment('行動電話');
            $table->string('account', 20)->comment('帳號');
            $table->string('password')->comment('密碼');
            $table->string('nick_name', 20)->nullable()->comment('暱稱');
            $table->string('phone', 20)->nullable()->comment('行動電話(中國11碼)');
            $table->tinyInteger('sex')->default(0)->comment('性別(0:未知,1:男,2:女)');
            $table->integer('points')->default(0)->comment('點數');
            $table->integer('app')->default(0)->comment('APP(1:WAP,2:APP)');
            $table->integer('channel_id')->default(0)->comment('渠道id');
            $table->integer('link_id')->default(0)->comment('推廣id');
            $table->tinyInteger('vip')->default(0)->comment('VIP(0:不是,1:是)');
            $table->timestamp('vip_at')->nullable()->comment('VIP到期時間');
            $table->text('remarks')->nullable()->comment('備註');
            $table->text('token_jwt')->nullable()->comment('JWT Token');
            $table->rememberToken()->comment('Token');
            $table->tinyInteger('status')->default(1)->comment('狀態(0:停用,1:啟用)');
            $table->timestamps();
            // 唯一
            $table->unique('account', 'un_account');
            // 索引
            $table->index(['account', 'password', 'status'], 'query_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_users');
    }
}
