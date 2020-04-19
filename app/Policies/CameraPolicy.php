<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Camera;
use Illuminate\Auth\Access\HandlesAuthorization;

class CameraPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the camera.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Camera  $camera
     * @return mixed
     */
    public function view(User $user, Camera $camera)
    {
        return true;
    }

    /**
     * Determine whether the user can create cameras.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the camera.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Camera  $camera
     * @return mixed
     */
    public function update(User $user, Camera $camera)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the camera.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Camera  $camera
     * @return mixed
     */
    public function delete(User $user, Camera $camera)
    {
        return true;
    }
}
