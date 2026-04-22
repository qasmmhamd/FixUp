<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Worker;
use Illuminate\Auth\Access\Response;

/**
 * @class WorkerPolicy
 * 
 * Defines authorization policies for worker model operations in the FixUp system.
 * This policy controls which users can perform specific actions on worker profiles
 * based on their roles and permissions.
 */
class WorkerPolicy
{
    /**
     * Determine whether the user can view any worker models.
     * 
     * @param User $user The user attempting to view workers
     * @return bool True if the user can view workers
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view a specific worker model.
     * 
     * @param User $user The user attempting to view the worker
     * @param Worker $worker The worker being viewed
     * @return bool True if the user can view the worker
     */
    public function view(User $user, Worker $worker): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create worker models.
     * 
     * @param User $user The user attempting to create a worker
     * @return bool True if the user can create workers
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the worker model.
     * 
     * @param User $user The user attempting to update the worker
     * @param Worker $worker The worker being updated
     * @return bool True if the user is an admin
     */
    public function update(User $user, Worker $worker): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the worker model.
     * 
     * @param User $user The user attempting to delete the worker
     * @param Worker $worker The worker being deleted
     * @return bool True if the user is an admin
     */
    public function delete(User $user, Worker $worker): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the worker model.
     * 
     * @param User $user The user attempting to restore the worker
     * @param Worker $worker The worker being restored
     * @return bool False - restore operations are not allowed
     */
    public function restore(User $user, Worker $worker): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the worker model.
     * 
     * @param User $user The user attempting to permanently delete the worker
     * @param Worker $worker The worker being permanently deleted
     * @return bool False - permanent deletion is not allowed
     */
    public function forceDelete(User $user, Worker $worker): bool
    {
        return false;
    }
}
