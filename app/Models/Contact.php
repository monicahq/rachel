<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\ResolvesModelInVault;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

final class Contact extends Model
{
    /** @use HasFactory<\Database\Factories\ContactFactory> */
    use HasFactory;

    use HasUuids;
    use ResolvesModelInVault;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'vault_id',
        'name',
        'slug',
    ];

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
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
     * Get the vault record associated with the contact.
     *
     * @return BelongsTo<Vault, $this>
     */
    public function vault(): BelongsTo
    {
        return $this->belongsTo(Vault::class);
    }
}
