<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LaravelWebauthn\Models\WebauthnKey as BaseWebauthnKey;

final class WebauthnKey extends BaseWebauthnKey
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    /**
     * Create a new WebauthnKey instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->mergeFillable(['used_at']);
        $this->setVisible(array_merge($this->getVisible(), ['used_at']));
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
