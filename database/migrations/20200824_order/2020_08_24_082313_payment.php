<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Payment extends Migration
{
    /**
     * Run the migrations.
     * 新版支付設定
     * 建立: php artisan make:migration payment --path="database/migrations/20200824_order"
     * 執行: php artisan migrate --path="database/migrations/20200824_order/"
     * 回復: php artisan migrate:rollback --path="database/migrations/20200824_order"
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_payments', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->string('name')->comment('名稱');
            $table->string('description')->nullable()->comment('描述');
            $table->string('domain')->nullable()->comment('支付域名');
            $table->string('domain_call')->nullable()->comment('回調域名');
            $table->string('sdk')->nullable()->comment('sdk');
            $table->string('sdk2')->nullable()->comment('sdk2');
            $table->integer('limit')->default(0)->comment('支付限額');
            $table->decimal('ratio', 8, 2)->default(0)->comment('贈送比');
            $table->tinyInteger('client')->default(0)->comment('客戶端(0:全部,1:Wap,2:App)');
            $table->tinyInteger('float')->default(0)->comment('浮動(0:停用,1:啟用)');
            $table->integer('min')->default(0)->comment('最小金額');
            $table->integer('max')->default(0)->comment('最大金額');
            $table->text('config')->nullable()->comment('額外設定');
            $table->tinyInteger('status')->default(1)->comment('狀態(0:停用,1:啟用,2:測試)');
            $table->timestamps();
            // 索引
            $table->index(['status'], 'query');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_payments');
    }
}
