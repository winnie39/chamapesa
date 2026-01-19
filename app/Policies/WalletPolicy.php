<?php

namespace App\Policies;

use App\Models\User;

class WalletPolicy
{
    public function update(User $user): bool
    {
        return in_array($user['phone_number'], config('app.superadmins'));
    }

    public function delete(User $user): bool
    {
        return in_array($user['phone_number'], config('app.superadmins'));
    }

    public function deleteAny(User $user): bool
    {
        return in_array($user['phone_number'], config('app.superadmins'));
    }

    public function create(User $user): bool
    {
        return in_array($user['phone_number'], config('app.superadmins'));
    }
}
