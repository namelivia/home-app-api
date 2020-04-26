<?php

namespace App\Policies;

use App\Models\Garment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GarmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the garment.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Garment  $garment
     *
     * @return mixed
     */
    public function view(User $user, Garment $garment)
    {
        return true;
    }

    /**
     * Determine whether the user can create garments.
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
     * Determine whether the user can update the garment.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Garment  $garment
     *
     * @return mixed
     */
    public function update(User $user, Garment $garment)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the garment.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Garment  $garment
     *
     * @return mixed
     */
    public function delete(User $user, Garment $garment)
    {
        return true;
    }
}
