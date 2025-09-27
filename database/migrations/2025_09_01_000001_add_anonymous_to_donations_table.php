<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->boolean('anonymous')->default(false);
        });

        // Update existing donations that should be anonymous (example: donor_name is 'Anonymous')
        DB::table('donations')
            ->where('donor_name', 'Anonymous')
            ->update(['anonymous' => true]);
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('anonymous');
        });
    }
};
