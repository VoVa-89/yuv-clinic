<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('price_items', function (Blueprint $table): void {
            $table->string('nomenclature_code', 64)->nullable()->after('procedure_name');
        });
    }

    public function down(): void
    {
        Schema::table('price_items', function (Blueprint $table): void {
            $table->dropColumn('nomenclature_code');
        });
    }
};
