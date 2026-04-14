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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('bg_image')->nullable()->after('slug');
        });

        Schema::table('subcategories', function (Blueprint $table) {
            $table->string('bg_image')->nullable()->after('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropColumn('bg_image');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('bg_image');
        });
    }
};
