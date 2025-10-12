<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\ResolvesModelInAccount;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Override;

final class Vault extends Model
{
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

    /**
     * Get the route key for the model.
     */
    #[Override]
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
