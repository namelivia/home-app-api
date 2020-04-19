<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Owner;

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
		$owner1Total = $this->model->owner1Total();
		$owner2Total = $this->model->owner2Total();
		return response()->json([
			Owner::OWNER1 => $owner1Total,
			Owner::OWNER2 => $owner2Total,
			'diff' => $owner1Total - $owner2Total
		]);
	}
}
