<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpensePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the expense.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Expense  $expense
     *
     * @return mixed
     */
    public function view(User $user, Expense $expense)
    {
        return true;
    }

    /**
     * Determine whether the user can create expenses.
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
     * Determine whether the user can update the expense.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Expense  $expense
     *
     * @return mixed
     */
    public function update(User $user, Expense $expense)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the expense.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Expense  $expense
     *
     * @return mixed
     */
    public function delete(User $user, Expense $expense)
    {
        return true;
    }
}
