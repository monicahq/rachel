<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'contact_id',
    'vault_id',
    'key',
    'label',
    'type',
    'data',
    'contact_ref_id',
])]
final class ContactParameter extends Model
{
    /** @use HasFactory<\Database\Factories\ContactParameterFactory> */
    use HasFactory;

    public static function rules(): array
    {
        return [
            'key' => ['required', 'string', 'max:32'],
            'label' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'data' => ['nullable', 'string'],
            'contact_ref_id' => ['nullable', 'exists:contacts,id'],
            'vault_id' => ['required', 'exists:vaults,id'],
        ];
    }

    /**
     * Get the contact record associated with the parameter.
     *
     * @return BelongsTo<Contact, $this>
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Get the contact reference record associated with the parameter.
     *
     * @return BelongsTo<Contact, $this>
     */
    public function contactRef(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_ref_id');
    }

    /**
     * Get the vault record associated with the parameter.
     *
     * @return BelongsTo<Vault, $this>
     */
    public function vault(): BelongsTo
    {
        return $this->belongsTo(Vault::class);
    }
}
