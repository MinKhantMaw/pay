<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('amount');
            $table->timestamp('disabled_at')->nullable()->after('is_active');
            $table->foreignId('disabled_by')->nullable()->after('disabled_at')->constrained('admin_users')->nullOnDelete();
            $table->text('disabled_reason')->nullable()->after('disabled_by');
        });
    }

    public function down(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropForeign(['disabled_by']);
            $table->dropColumn(['is_active', 'disabled_at', 'disabled_by', 'disabled_reason']);
        });
    }
};
