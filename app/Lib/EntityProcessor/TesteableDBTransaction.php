<?php

namespace App\Lib\EntityProcessor;

use Illuminate\Support\Facades\DB;

class TesteableDBTransaction
{
	/**
	 * Creates a new transaction.
	 */
	public function beginTransaction()
	{
		DB::beginTransaction();
	}

	/**
	 * Performs a rollback on the transaction.
	 */
	public function rollBack()
	{
		DB::rollBack();
	}

	/**
	 * Performs a commit on the transaction.
	 */
	public function commit()
	{
		DB::commit();
	}
}
