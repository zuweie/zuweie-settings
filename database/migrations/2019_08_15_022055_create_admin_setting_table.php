<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_ext_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->comment('key');
            $table->string('tags')->nullable()->comment('tags');
            $table->string('alias')->nullable()->comment(' alias for key');
            $table->text('value')->nullable()->comment('value');
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
        Schema::dropIfExists('admin_ext_settings');
    }
}
