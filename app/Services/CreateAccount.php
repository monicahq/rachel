<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Create an account for a user.
 */
final class CreateAccount
{
    private Account $account;

    private User $user;

    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly string $name,
    ) {}

    public function execute(): User
    {
        DB::transaction(function (): void {
            $this->create();
            $this->addFirstUser();
        });

        return $this->user;
    }

    private function create(): void
    {
        $this->account = Account::create([
            'trial_ends_at' => now()->addDays(30),
        ]);
    }

    private function addFirstUser(): void
    {
        $this->user = $this->account->users()->create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'locale' => App::getLocale(),
        ]);
    }
}
