<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LaravelWebauthn\Models\WebauthnKey as BaseWebauthnKey;

final class WebauthnKey extends BaseWebauthnKey
{
    /** @use HasFactory<\Database\Factories\WebauthnKeyFactory> */
    use HasFactory;

    /**
     * Create a new WebauthnKey instance.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->mergeFillable(['kind', 'used_at']);
        $this->setVisible(array_merge($this->getVisible(), ['kind', 'used_at']));
        $this->mergeCasts(['used_at' => 'datetime']);
    }

    /**
     * Get the user record associated with the key.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
