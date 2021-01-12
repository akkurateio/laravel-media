<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelResourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_model_resource', function (Blueprint $table) {
            $table->bigInteger('resource_id');
            $table->morphs('resourceable');
            $table->text('legend')->nullable();
            $table->unique(['resource_id','resourceable_type','resourceable_id'], 'media_model_resource_unique');
        });
    }
}
