<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Create an account for a user.
 */
final class CreateAccount
{
    private Account $account;

    private User $user;

    public function __construct(
        public string $email,
        public string $password,
        public string $name,
    ) {}

    public function execute(): User
    {
        $this->create();
        $this->addFirstUser();

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
        $this->user = User::create([
            'account_id' => $this->account->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);
    }
}
