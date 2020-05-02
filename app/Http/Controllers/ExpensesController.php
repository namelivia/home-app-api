<?php

namespace App\Http\Controllers;

use App\Models\Expense;

class ExpensesController extends BaseController
{
    /**
     * Corresponding model name.
     *
     * @var App\Models\Expense
     */
    protected $modelName = Expense::class;

    public function getTotals()
    {
        $user1Total = $this->model->user1Total();
        $user2Total = $this->model->user2Total();

        return response()->json([
            1 => $user1Total,
            2 => $user2Total,
            'diff' => $user1Total - $user2Total,
        ]);
    }
}
