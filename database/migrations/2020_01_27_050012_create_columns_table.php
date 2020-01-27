<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColumnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('columns', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name');
            $table->unsignedBigInteger('board_id');
            $table->unsignedBigInteger('created_by');

            $table->timestamps();
            $table->softDeletes();
            /**
             * Foreign Key binds
             */
            $table->foreign('board_id')
                    ->references('id')
                    ->on('boards')
                    ->onDelete('cascade');

            $table->foreign('created_by')
                    ->references('id')
                    ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('columns');
    }
}
