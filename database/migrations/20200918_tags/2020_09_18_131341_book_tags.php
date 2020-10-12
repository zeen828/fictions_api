<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BookTags extends Migration
{
    /**
     * Run the migrations.
     * 新版書籍分類關係表
     * 建立: php artisan make:migration book_tags --path="database/migrations/20200918_tags"
     * 執行: php artisan migrate --path="database/migrations/20200918_tags/"
     * 回復: php artisan migrate:rollback --path="database/migrations/20200918_tags"
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_book_booktype', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->integer('book_id')->comment('書籍ID');
            $table->integer('booktype_id')->comment('書籍分類ID');
            $table->timestamps();
        });
        //TRUNCATE TABLE `cps_fiction`.`t_book_booktype`;

        //INSERT INTO `cps_fiction`.`t_book_booktype` (`book_id`, `booktype_id`, `created_at`, `updated_at`)
        //SELECT `id`, `tid`, NOW(), NOW() FROM `cps_fiction`.`t_bookinfo`
        //LIMIT 20;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_book_booktype');
    }
}
