<?php

namespace App\Lib\EntityProcessor;

use Illuminate\Support\Facades\Log;
use App\Models\BaseModel;
use Throwable;

class EntitySaver
{

	protected $modelErrorCodeAccessor;

	public function __construct()
	{
		$this->modelErrorCodeAccessor = app()->make(ModelErrorCodeAccessor::class);
	}

	/**
	 * This function creates a new entity with a given
	 * data for a given model, performs the syncOnSave
	 * and then returns the new created entity.
	 * If there is an error returns an array.
	 *
	 * @param array $data
	 * @param App\Models\BaseModel $model
	 * @return mixed
	 */
	public function storeAndSync(array $data, BaseModel $model)
	{
		try {
			$entity = $model->create($data);
			$entity = $this->syncOnSave($entity, $data);
			return $entity;
		} catch (Throwable $e) {
			Log::error("Error when trying to store an entity. Message: " . $e->getMessage());
			return [
				'code' => $this->modelErrorCodeAccessor->getModelErrorCode($model, 'failedToCreate'),
				'data' => null
			];
		}
	}

	/**
	 * This function updates an existing entity with a given
	 * data, and then performs the syncOnSave.
	 * Finally returns the updated entity.
	 * If there is an error returns an array.
	 *
	 * @param array $data
	 * @param App\Models\BaseModel $entity
	 * @return mixed
	 */
	public function updateAndSync(array $data, BaseModel $entity)
	{
		try {
			$entity->fill($data)->save();
			$entity = $entity->getObject($entity->id);
			$entity = $this->syncOnSave($entity, $data);
			return $entity;
		} catch (Throwable $e) {
			Log::error("Error when trying to update an entity. Message: " . $e->getMessage());
			return [
				'code' => $this->modelErrorCodeAccessor->getModelErrorCode($entity, 'failedToUpdate'),
				'data' => null
			];
		}
	}

	/**
	 * This function tells the model to update the values
	 * for the n:m relationships if these are set in
	 * the request object.
	 *
	 * @param App\Models\BaseModel $entity
	 * @param array $data
	 * @return mixed
	 */
	private function syncOnSave(BaseModel $entity, array $data)
	{
		$syncRelationships = $entity->getSyncOnSave();

		foreach ($syncRelationships as $key => $value) {
			if (isset($data[$key])) {
				$entity->{$value}()->sync($data[$key]);
			}
		}
		return $entity;
	}
}
