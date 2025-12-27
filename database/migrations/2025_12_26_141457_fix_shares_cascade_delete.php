<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('shares', function (Blueprint $table) {
            $table->dropForeign(['file_id']);
            $table->dropForeign(['folder_id']);

            $table->foreign('file_id')
                ->references('id')->on('files')
                ->onDelete('cascade');

            $table->foreign('folder_id')
                ->references('id')->on('folders')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
