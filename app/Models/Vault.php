<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\ResolvesModelInAccount;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

final class Vault extends Model
{
    /** @use HasFactory<\Database\Factories\VaultFactory> */
    use HasFactory;

    use HasUuids;
    use ResolvesModelInAccount;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'account_id',
        'name',
        'slug',
        'description',
    ];

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get the route key for the model.
     */
    #[Override]
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the account that owns the vault.
     *
     * @return BelongsTo<Account, $this>
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get all contacts in the vault.
     *
     * @return HasMany<Contact, $this>
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }
}
