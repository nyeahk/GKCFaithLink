<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('announcements', function (Blueprint $table) {
            // Add created_by and updated_by columns
            $table->foreignId('created_by')->nullable()->after('posted_at')->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->onDelete('set null');
            
            // Rename image_path to image for consistency
            if (Schema::hasColumn('announcements', 'image_path') && !Schema::hasColumn('announcements', 'image')) {
                $table->renameColumn('image_path', 'image');
            }
            
            // Add image column if neither exists
            if (!Schema::hasColumn('announcements', 'image_path') && !Schema::hasColumn('announcements', 'image')) {
                $table->string('image')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('announcements', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            
            // Drop columns
            $table->dropColumn(['created_by', 'updated_by']);
            
            // Rename image back to image_path if needed
            if (Schema::hasColumn('announcements', 'image') && !Schema::hasColumn('announcements', 'image_path')) {
                $table->renameColumn('image', 'image_path');
            }
        });
    }
};