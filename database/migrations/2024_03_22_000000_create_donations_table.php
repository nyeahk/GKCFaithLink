<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Donor
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->comment('Accepted methods: Cash, GCash');
            $table->enum('status', ['pending', 'approved', 'declined', 'verified'])->default('pending');
            $table->text('admin_response')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null'); // Admin
            $table->foreignId('treasurer_id')->nullable()->constrained('users')->onDelete('set null'); // Treasurer

            $table->boolean('admin_approved')->default(false); // Admin approval
            $table->boolean('treasurer_approved')->default(false); // Treasurer approval

            $table->timestamp('transaction_date')->nullable();
            $table->string('receipt_number')->nullable();
            $table->string('verified_by')->nullable();
            $table->timestamp('verification_date')->nullable();
            $table->text('verification_notes')->nullable();
            $table->timestamps();
        $table->timestamp('admin_approved_at')->nullable();
        $table->timestamp('treasurer_approved_at')->nullable();
        $table->enum('final_status', ['pending', 'accepted', 'declined'])->default('pending');

        });
    }

    public function down()
    {
        Schema::dropIfExists('donations');
    }
};