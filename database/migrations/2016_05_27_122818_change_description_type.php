<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDescriptionType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('firmwares', function ($table) {
            $table->dropColumn('description');
        });

        Schema::table('firmwares', function ($table) {
            $table->text('description')->after('release_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('firmwares', function ($table) {
            $table->dropColumn('description');
        });

        Schema::table('firmwares', function ($table) {
            $table->string('description')->after('release_date');
        });
    }
}
