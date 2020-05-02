<?php

namespace App\Policies;

use App\Models\Place;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlacePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the place.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Place  $place
     *
     * @return mixed
     */
    public function view(User $user, Place $place)
    {
        return true;
    }

    /**
     * Determine whether the user can create places.
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
     * Determine whether the user can update the place.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Place  $place
     *
     * @return mixed
     */
    public function update(User $user, Place $place)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the place.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Place  $place
     *
     * @return mixed
     */
    public function delete(User $user, Place $place)
    {
        return true;
    }
}
