<?php

namespace App\Lib\EntityProcessor;

use Illuminate\Support\Str;

class ChildProcessor
{
    protected $newChildrenCalculator;
    protected $entitySaver;
    protected $entityValidator;

    public function __construct()
    {
        $this->newChildrenCalculator = app()->make(NewChildrenCalculator::class);
        $this->entitySaver = app()->make(EntitySaver::class);
        $this->entityValidator = app()->make(EntityValidator::class);
    }

    /**
     * Inserts the children that still have to be
     * inserted passed as an structure with some
     * predefined keys and its values and then calculates
     * if new children need to be calculated.
     *
     * @param array $newEntities
     *
     * @return array
     */
    public function processPendingCreations(array $newEntities)
    {
        $newChildren = [];
        foreach ($newEntities as $newEntity) {
            $model = app()->make($newEntity['className']);
            $parentId = [
                Str::snake(class_basename($newEntity['parentClassname'])) .
                '_id' => $newEntity['parentId'],
            ];
            $newEntity['data'] = array_merge($newEntity['data'], $parentId);

            //Check for validation
            $validationErrors = $this->entityValidator->validateEntity($newEntity['data'], $model);
            if ($validationErrors !== null) {
                return $validationErrors;
            }

            $entity = $this->entitySaver->storeAndSync($newEntity['data'], $model);
            if (isset($entity['code'])) {
                return $entity;
            }

            $newChildren = array_merge(
                $newChildren,
                $this->newChildrenCalculator->getNewChildren($entity, $newEntity['data'], $newEntity['className'])
            );
        }

        return $newChildren;
    }
}
