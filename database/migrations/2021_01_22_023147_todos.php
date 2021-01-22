<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Todos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();;
            $table->string('todo_name');
            $table->text('todo_description');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // $table->dropForeign('lists_user_id_foreign');
        // $table->dropIndex('lists_user_id_index');
        // $table->dropColumn('user_id');
        Schema::dropIfExists('todos');
    }
}
