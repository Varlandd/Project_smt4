<?php

use Illuminate\Database\Migrations\Migration;

/**
 * Di MongoDB, field 'role' langsung ditambahkan ke dokumen user
 * saat create/update. Tidak memerlukan alter table/migration khusus
 * karena MongoDB bersifat schemaless.
 */
return new class extends Migration
{
    protected $connection = 'mongodb';

    public function up(): void
    {
        // MongoDB bersifat schemaless, field 'role' otomatis ada
        // ketika di-set di model atau seeder.
    }

    public function down(): void
    {
        // Nothing to drop
    }
};
