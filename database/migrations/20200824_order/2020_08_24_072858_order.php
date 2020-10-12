<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Order extends Migration
{
    /**
     * Run the migrations.
     * 新版訂單
     * 建立: php artisan make:migration order --path="database/migrations/20200824_order"
     * 執行: php artisan migrate --path="database/migrations/20200824_order/"
     * 回復: php artisan migrate:rollback --path="database/migrations/20200824_order"
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_order', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->integer('user_id')->comment('會員ID');
            $table->integer('payment_id')->comment('支付ID');
            $table->string('order_sn', 30)->comment('訂單');
            $table->decimal('price', 10, 2)->default(0)->comment('金額');
            $table->integer('point_old')->default(0)->comment('儲點前');
            $table->integer('points')->default(0)->comment('點數');
            $table->integer('point_new')->default(0)->comment('儲點後');
            $table->tinyInteger('vip')->default(0)->comment('VIP(0:停用,1:啟用)');
            $table->timestamp('vip_at_old')->nullable()->comment('原本VIP到期時間');
            $table->integer('vip_day')->default(0)->comment('VIP天數');
            $table->timestamp('vip_at_new')->nullable()->comment('儲值後VIP到期時間');
            $table->string('transaction_sn', 30)->nullable()->comment('交易訂單');
            $table->timestamp('transaction_at')->nullable()->comment('交易完成時間');
            $table->integer('app')->default(0)->comment('APP(1:WAP,2:APP)');
            $table->integer('channel_id')->default(0)->comment('渠道id');
            $table->integer('link_id')->default(0)->comment('推廣id');
            $table->string('callbackUrl', 255)->nullable()->comment('支付返回');
            $table->string('sdk', 30)->nullable()->comment('SDK');
            $table->text('config')->nullable()->comment('支付商設定');
            $table->tinyInteger('status')->default(0)->comment('狀態(0:未支付,1:支付成功,2:支付失敗)');
            $table->timestamps();
            // 唯一
            $table->unique('order_sn', 'un_order');
            // 索引
            $table->index(['user_id', 'payment_id', 'order_sn', 'status'], 'query');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_order');
    }
}
