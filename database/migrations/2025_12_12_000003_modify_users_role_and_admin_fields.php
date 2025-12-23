<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_admin_id')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->string('phone')->nullable();
        });

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreign('created_by_admin_id')->references('id')->on('users')->nullOnDelete();
            });
        }

        DB::table('users')
            ->whereNotIn('role', ['admin', 'distributor'])
            ->update(['role' => 'distributor']);

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','distributor') NOT NULL DEFAULT 'distributor'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','distributor') NOT NULL DEFAULT 'distributor'");
        }

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['created_by_admin_id']);
            });
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'phone']);
            $table->dropColumn('created_by_admin_id');
        });
    }
};
