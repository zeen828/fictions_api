<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UesPoints extends Migration
{
    /**
     * Run the migrations.
     * 新版會員點數使用紀錄(也是閱讀紀錄)
     * 建立: php artisan make:migration uesPoints --path="database/migrations/20200824_order"
     * 執行: php artisan migrate --path="database/migrations/20200824_order/"
     * 回復: php artisan migrate:rollback --path="database/migrations/20200824_order"
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_point_log', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->integer('user_id')->comment('會員ID');
            $table->integer('book_id')->default(0)->comment('書籍ID');
            $table->integer('chapter_id')->default(0)->comment('章節ID');
            $table->tinyInteger('event')->default(0)->comment('事件(0:扣點,1:加點)');
            $table->integer('point_old')->default(0)->comment('扣點前');
            $table->integer('point')->default(0)->comment('扣點');
            $table->integer('point_new')->default(0)->comment('扣點後');
            $table->text('remarks')->nullable()->comment('備註');
            $table->tinyInteger('status')->default(1)->comment('狀態(0:停用,1:啟用)');
            $table->timestamps();
            // 索引
            $table->index(['user_id', 'book_id', 'chapter_id', 'event', 'status'], 'query');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_point_log');
    }
}
