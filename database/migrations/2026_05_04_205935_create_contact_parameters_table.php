<?php

declare(strict_types=1);

use App\Models\Contact;
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
        Schema::create('contact_parameters', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Contact::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Vault::class)->constrained()->cascadeOnDelete();
            $table->string('key', 32);
            $table->string('label', 255)->nullable();
            $table->string('type', 255)->default('string');
            $table->longText('data')->nullable();
            $table->foreignIdFor(Contact::class, 'contact_ref_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();

            $table->index(['contact_id', 'vault_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_parameters');
    }
};
