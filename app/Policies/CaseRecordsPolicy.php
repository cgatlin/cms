<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CaseRecords;
use App\Models\User;

class CaseRecordsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CaseRecords $caseRecords): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $caseRecords->assigned_to === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CaseRecords $caseRecords): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $caseRecords->assigned_to === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CaseRecords $caseRecords): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CaseRecords $caseRecords): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CaseRecords $caseRecords): bool
    {
        return $user->isAdmin();
    }
}
