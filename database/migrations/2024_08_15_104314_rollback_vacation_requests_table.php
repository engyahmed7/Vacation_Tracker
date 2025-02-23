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
        Schema::dropIfExists('vacation_requests');

        $migrationNamePattern = 'create_vacation_requests_table';
        $record = DB::table('migrations')
                    ->where('migration', 'LIKE', '%' . $migrationNamePattern . '%')
                    ->first();

        if ($record) {
            DB::table('migrations')->where('migration', $record->migration)->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};