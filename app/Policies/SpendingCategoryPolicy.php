<?php

namespace App\Policies;

use App\Models\SpendingCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpendingCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the spendingCategory.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SpendingCategory  $spendingCategory
     *
     * @return mixed
     */
    public function view(User $user, SpendingCategory $spendingCategory)
    {
        return true;
    }

    /**
     * Determine whether the user can create spendingCategorys.
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
     * Determine whether the user can update the spendingCategory.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SpendingCategory  $spendingCategory
     *
     * @return mixed
     */
    public function update(User $user, SpendingCategory $spendingCategory)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the spendingCategory.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SpendingCategory  $spendingCategory
     *
     * @return mixed
     */
    public function delete(User $user, SpendingCategory $spendingCategory)
    {
        return true;
    }
}
