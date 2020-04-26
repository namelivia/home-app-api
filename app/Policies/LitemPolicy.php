<?php

namespace App\Policies;

use App\Models\Litem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LitemPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the litem.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Litem  $litem
     *
     * @return mixed
     */
    public function view(User $user, Litem $litem)
    {
        return true;
    }

    /**
     * Determine whether the user can create litems.
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
     * Determine whether the user can update the litem.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Litem  $litem
     *
     * @return mixed
     */
    public function update(User $user, Litem $litem)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the litem.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Litem  $litem
     *
     * @return mixed
     */
    public function delete(User $user, Litem $litem)
    {
        return true;
    }
}
