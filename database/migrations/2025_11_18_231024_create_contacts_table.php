<?php

declare(strict_types=1);

use App\Models\Vault;
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
        Schema::create('contacts', function (Blueprint $table): void {
            $table->uuid('id');
            $table->primary('id');
            $table->foreignIdFor(Vault::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');

            $table->softDeletes();
            $table->timestamps();

            $table->index(['vault_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
