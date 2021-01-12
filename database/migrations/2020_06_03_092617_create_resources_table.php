<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_resources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('alt')->nullable();
            $table->string('legend')->nullable();
            $table->string('md5');
            $table->mediumText('thumb')->nullable();

            $table->unsignedBigInteger('type_id')->nullable();
            $table->foreign('type_id')
                ->references('id')->on('media_types')
                ->onDelete('cascade');

            $table->unsignedBigInteger('media_id')->nullable();
            $table->foreign('media_id')
                ->references('id')->on('media')
                ->onDelete('cascade');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->unsignedBigInteger('account_id')->nullable();
            $table->foreign('account_id')
                ->references('id')->on('admin_accounts')
                ->onDelete('cascade');

            $table->softDeletes();
            $table->nullableTimestamps();

        });
    }
}
