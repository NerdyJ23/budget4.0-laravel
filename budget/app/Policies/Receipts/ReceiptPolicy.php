<?php

namespace App\Policies\Receipts;

use App\Models\Receipts\Receipt;
use App\Models\Users\User;

class ReceiptPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function list(User $user): bool
    {
		return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Receipt $receipt): bool
    {
		return $user->id === $receipt->User;
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
		return true;
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Receipt $receipt): bool
    {
		return $user->id === $receipt->User;
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Receipt $receipt): bool
    {
		return $user->id === $receipt->User;
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Receipt $receipt): bool
    {
		return $user->id === $receipt->User;
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Receipt $receipt): bool
    {
		return $user->id === $receipt->User;
        //
    }
}
