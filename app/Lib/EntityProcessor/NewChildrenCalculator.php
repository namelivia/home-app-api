<?php

namespace App\Lib\EntityProcessor;

use App\Models\BaseModel;
use Illuminate\Support\Str;

class NewChildrenCalculator
{
	/**
	 * Calculates the new children to be processed
	 * that are nested in the data and sets them
	 * in the newChildren array.
	 * If there already are children this will be
	 * erased in order to be overwritten later.
	 *
	 * @param App\Models\BaseModel $entity
	 * @param array $data
	 * @param String $className
	 * @return array
	 */
	public function getNewChildren(BaseModel $entity, array $data, String $className)
	{
		$newChildren = [];
		$childRelationships = $entity->getChildren();
		foreach ($data as $key => $value) {
			if (in_array($key, array_keys($childRelationships))) {
				foreach ($value as $childData) {
					//Delete old children if exist
					$entity->{Str::camel($key)}()->delete();
					//Then add the child to the structure
					$newChild = [
						'parentId' => $entity->id,
						'parentClassname' => $className,
						'className' => $childRelationships[$key],
						'data' => $childData
					];
					$newChildren[] = $newChild;
				}
			}
		}
		return $newChildren;
	}
}
