<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('page__pages', function (Blueprint $table) {
            $table->unsignedInteger('parent_id')
                ->nullable()
                ->after('id');
            $table->foreign('parent_id')
                ->references('id')
                ->on('page__pages')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->unsignedInteger('order')
                ->after('parent_id')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('page__pages', function (Blueprint $table) {
            $table->dropColumn('order');
            $table->dropForeign('page__pages_parent_id_foreign');
            $table->dropColumn('parent_id');
        });
    }
};
