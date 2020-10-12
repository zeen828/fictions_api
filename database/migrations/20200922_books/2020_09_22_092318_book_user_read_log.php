<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BookUserReadLog extends Migration
{
    /**
     * Run the migrations.
     * 會員閱讀紀錄
     * 建立: php artisan make:migration book_user_read_log --path="database/migrations/20200922_books"
     * 執行: php artisan migrate --path="database/migrations/20200922_books/"
     * 回復: php artisan migrate:rollback --path="database/migrations/20200922_books"
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_book_user_read', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->integer('user_id')->comment('會員ID');
            $table->integer('book_id')->comment('書籍ID');
            $table->integer('chapter_id')->comment('章節ID');
            $table->tinyInteger('status')->default(1)->comment('狀態(0:停用,1:啟用)');
            $table->timestamps();
            // 唯一
            $table->unique(['user_id', 'chapter_id'], 'un_user_red');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_book_user_read');
    }
}
