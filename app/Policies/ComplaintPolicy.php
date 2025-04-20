<?php
namespace App\Policies;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ComplaintPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user = null) // Allow a null entity for public access
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Complaint $complaint)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->role === 'user';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Complaint $complaint)
    {
        return $user->id === $complaint->user_id || $user->role == "admin" ;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Complaint $complaint)
    {
        return $user->id === $complaint->user_id || $user->role == "admin" ;
    }
}
