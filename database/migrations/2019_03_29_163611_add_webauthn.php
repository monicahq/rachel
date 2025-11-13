<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\MySqlConnection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('webauthn_keys', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();

            $table->string('name')->default('key');
            $table->mediumText('credentialId');
            $table->string('kind', 8)->nullable();
            $table->string('type', 255);
            $table->text('transports');
            $table->string('attestationType', 255);
            $table->text('trustPath');
            $table->text('aaguid');
            $table->text('credentialPublicKey');
            $table->bigInteger('counter')->unsigned();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            if (app(Resolver::class)->connection($this->getConnection()) instanceof MySqlConnection) {
                $table->index([app(Resolver::class)->raw('credentialId(255)')], 'credential_index');
            } else {
                $table->index('credentialId');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webauthn_keys');
    }
};
