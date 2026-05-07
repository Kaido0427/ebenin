<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE annonces MODIFY COLUMN category VARCHAR(50) NOT NULL");
        }
        // SQLite stores enums as text already — no change needed
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE annonces MODIFY COLUMN category ENUM('emploi','immobilier','vente_services','evenements') NOT NULL");
        }
    }
};
