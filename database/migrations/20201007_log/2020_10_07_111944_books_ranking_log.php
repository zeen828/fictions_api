<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BooksRankingLog extends Migration
{
    /**
     * Run the migrations.
     * 
     * 建立: php artisan make:migration books_ranking_log --path="database/migrations/20201007_log"
     * 執行: php artisan migrate --path="database/migrations/20201007_log/"
     * 回復: php artisan migrate:rollback --path="database/migrations/20201007_log"
     * 
     * @return void
     */
    public function up()
    {
        Schema::create('t_logs_books_ranking', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->tinyInteger('mode')->default(0)->comment('類型(0:周排行,1:月排行)');
            $table->text('ranking_data')->nullable()->comment('排行資料');
            $table->tinyInteger('status')->default(1)->comment('狀態(0:停用,1:啟用)');
            $table->timestamps();
            // 索引
            $table->index(['mode', 'status'], 'query');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_logs_books_ranking');
    }
}
