<?php

namespace App\Models;

use Doctrine\Common\Inflector\Inflector;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Builder;
use App\Lib\RequestContext;
use App\Lib\Constants\ErrorCodes;
use Illuminate\Support\Facades\DB;

class BaseModel extends Eloquent
{
	/**
	 * Whether or not this model is
	 * read-only or can be modified.
	 *
	 * @var boolean
	 */
	public static $readOnly = false;

	/**
	 * Guarded attributes
	 *
	 * @var array
	 *
	 */
	protected $guarded = ['id'];

	/**
	 * Get the list of filters for the model.
	 *
	 * @return array
	 */
	public function getFilters()
	{
		return [];
	}

	/**
	 * Get the list of nested resources for the model.
	 *
	 * @return array
	 */
	public function getChildren()
	{
		return [];
	}

	/**
	 * Get the list of sync on save relationships for the model.
	 *
	 * @return array
	 */
	public function getSyncOnSave()
	{
		return [];
	}

	/**
	 * Constructor, optionally set the attributes, set the
	 * readOnly related dates.
	 *
	 * @param array $attributes (optional)
	 * @return void
	 */
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
		if (static::$readOnly === true) {
			$this->timestamps = false;
		}
	}

	/**
	 * List of common error codes for the model.
	 *
	 * @return array
	 */
	public function getErrorCodes()
	{
		return [
			'notFound' => ErrorCodes::MODEL_NOT_FOUND,
			'invalidData' => ErrorCodes::INVALID_MODEL,
			'failedToCreate' => ErrorCodes::FAILED_TO_CREATE_MODEL,
			'failedToUpdate' => ErrorCodes::FAILED_TO_UPDATE_MODEL,
			'failedToDelete' => ErrorCodes::FAILED_TO_DELETE_MODEL
		];
	}

	/**
	 * List of valuation rules for the model.
	 *
	 * @param integer|null $entityId
	 * @return array
	 */
	public function getValidationRules($entityId = null)
	{
		return [];
	}

	/**
	 * Fetchs a single object from the database.
	 *
	 * @param integer $entityId
	 * @param App\Lib\RequestContext $context
	 * @return Illuminate\Database\Eloquent\Model
	 */
	public function getObject($entityId, RequestContext $context = null)
	{
		$with = is_null($context) ? [] : $context->getWith();
		$withCount = is_null($context) ? [] : $context->getWithCount();
		return $this->with($with)->withCount($withCount)->find($entityId);
	}

	/**
	 * Fetchs multiple objects from the database. Wraps the resulting
	 * collection in an object containing also context information
	 *
	 * @param App\Lib\RequestContext $context
	 * @return \stdClass
	 */
	public function getPaginatedObjects(RequestContext $context)
	{
		$query = $this->query();
		$query = $query->with($context->getWith());
		$query = $query->withCount($context->getWithCount());

		$this->applyFilters($query, $context);

		//TODO: Search queries are disabled for now
		/*
		if (isset($context->searchFields) && isset($context->searchData) && !empty($context->searchData)) {
			$querySearchFields = null;
			if (!is_null($context->searchFields)) {
				$querySearchFields = implode(',', $context->searchFields);
			}
			$query->where(
				DB::raw('CONCAT_WS(" ",' . $querySearchFields . ') LIKE "%' . $context->searchData . '%"'),
				1
			);
		}
		*/
		$this->applyOrderBy($query, $context);

		$page = $context->getPage();
		$pageSize = $context->getPageSize();
		if ($page && $pageSize) {
			return $query->paginate($pageSize, ['*'], 'page', $page);
		} else {
			return $query->get();
		}
	}

	/**
	 * This hook is called before data is validated.
	 *
	 * @param	array $data
	 */
	public function beforeValidate(array &$data)
	{
		return;
	}

	/**
	 * This hook is called before data is inserted.
	 *
	 * @param	array $data
	 */
	public function beforeInsert(array &$data)
	{
		return;
	}

	/**
	 * This hook is called after data is inserted.
	 *
	 * @param	array	$data
	 * @param	\App\Models\BaseModel	$entity
	 */
	public function afterInsert(array $data)
	{
		return;
	}

	/**
	 * This hook is called before data is saved in
	 * an update.
	 *
	 * @param	array	$data
	 */
	public function beforeUpdate(array &$data)
	{
		return;
	}

	/**
	 * This hook is called after data is saved in
	 * an update.
	 *
	 * @param	array	$data
	 */
	public function afterUpdate(array $data)
	{
		return;
	}

	/**
	 * This hook is called before data is deleted in
	 * a destroy (DELETE) request, but after it has been
	 * validated.
	 *
	 * @param	\App\Models\BaseModel	$entity
	 */
	public function beforeDestroy($entity)
	{
		return;
	}

	/**
	 * Applies order by to the query if the context
	 * has the right params
	 * @param  \Illuminate\Database\Eloquent\Builder $query
	 * @param  \App\Lib\RequestContext $context
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	private function applyOrderBy(Builder &$query, RequestContext $context)
	{
		$orderBy = $context->getOrderBy();
		$orderDirection = $context->getOrderDirection();
		if ($orderBy && $orderDirection) {
			$query->orderBy($orderBy, $orderDirection);
		}
	}

	/**
	 * Applies the filters if they are defined
	 * @param  \Illuminate\Database\Eloquent\Builder $query
	 * @param  \App\Lib\RequestContext $context
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	private function applyFilters(Builder &$query, RequestContext $context)
	{
		foreach ($context->getFilters() as $function => $values) {
			if (is_array($values[0])) {
				$values = $values[0];
			}
			$query->{$function}($values);
		}
	}
}
