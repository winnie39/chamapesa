<?php

namespace App\Policies;

use App\Models\Kyc;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class KycPolicy
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

    public function view(User $user): bool
    {
        return in_array($user['phone_number'], config('app.superadmins'));
    }

    public function viewAny(User $user): bool
    {
        return in_array($user['phone_number'], config('app.superadmins'));
    }

    public function restore(User $user): bool
    {
        return in_array($user['phone_number'], config('app.superadmins'));
    }


    public function forceDelete(User $user): bool
    {
        return in_array($user['phone_number'], config('app.superadmins'));
    }
}
