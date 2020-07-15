<?php

namespace App\Policies;

use App\Models\Destination;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DestinationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the destination.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Destination  $destination
     *
     * @return mixed
     */
    public function view(User $user, Destination $destination)
    {
        return true;
    }

    /**
     * Determine whether the user can create destinations.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the destination.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Destination  $destination
     *
     * @return mixed
     */
    public function update(User $user, Destination $destination)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the destination.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Destination  $destination
     *
     * @return mixed
     */
    public function delete(User $user, Destination $destination)
    {
        return true;
    }
}
