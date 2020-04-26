<?php

namespace App\Lib\EntityProcessor;

use App\Lib\Constants\ErrorCodes;
use App\Lib\RequestContext;
use App\Models\BaseModel;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Throwable;

class EntityProcessor
{
    protected $childProcessor;
    protected $newChildrenCalculator;
    protected $entitySaver;
    protected $modelErrorCodeAccessor;
    protected $testeableDBTransaction;
    protected $entityValidator;

    protected $maxIterations = 100;

    public function __construct()
    {
        $this->childProcessor = app()->make(ChildProcessor::class);
        $this->newChildrenCalculator = app()->make(NewChildrenCalculator::class);
        $this->entitySaver = app()->make(EntitySaver::class);
        $this->modelErrorCodeAccessor = app()->make(ModelErrorCodeAccessor::class);
        $this->testeableDBTransaction = app()->make(TesteableDBTransaction::class);
        $this->entityValidator = app()->make(EntityValidator::class);
    }

    /**
     * Checks the create permissions for a model.
     *
     * @param App\Models\BaseModel $model
     *
     * @return mixed
     */
    private function checkCreatePermissions(BaseModel $model)
    {
        return $this->checkPermissions('create', $model);
    }

    /**
     * Checks the update permissions for a model.
     *
     * @param App\Models\BaseModel $model
     *
     * @return mixed
     */
    private function checkUpdatePermissions(BaseModel $model)
    {
        return $this->checkPermissions('update', $model);
    }

    /**
     * Checks the permissions for a model.
     * Returns an error array if Gate denies it.
     *
     * @param string $method
     * @param App\Models\BaseModel $model
     *
     * @return mixed
     */
    private function checkPermissions(string $method, BaseModel $model)
    {
        if (Gate::denies($method, $model)) {
            return [
                'code' => ErrorCodes::USER_NOT_AUTHORIZED,
                'data' => null,
            ];
        }
    }

    /**
     * Given an array of data, a model and a context
     * stores the data on the model and its nested children.
     * Then returns the response as requested by the
     * given context.
     *
     * @param array $data
     * @param string $modelName
     * @param App\Lib\RequestContext $context
     *
     * @return mixed
     */
    public function store(array $data, string $modelName, RequestContext $context)
    {
        $model = app()->make($modelName);

        //Check for permissions
        $permissionErrors = $this->checkCreatePermissions($model);
        if ($permissionErrors !== null) {
            return $permissionErrors;
        }

        //Check for validation
        $model->beforeValidate($data);
        $validationErrors = $this->entityValidator->validateEntity($data, $model);
        if ($validationErrors !== null) {
            return $validationErrors;
        }

        //Cast the data
        $data = $this->castModelData($data, $model);

        $this->testeableDBTransaction->beginTransaction();
        //Execute before hook
        if ($filterResponse = $model->beforeInsert($data)) {
            $this->testeableDBTransaction->rollBack();

            return $filterResponse;
        }

        //Insert the entity
        $insertedEntity = $this->entitySaver->storeAndSync($data, $model);
        if (is_array($insertedEntity)) {
            $this->testeableDBTransaction->rollBack();

            return $insertedEntity;
        }

        //Get the children
        $newChildren = $this->newChildrenCalculator->getNewChildren(
            $insertedEntity,
            $data,
            $modelName
        );

        //Process the structure
        $iterationsCount = 0;

        try {
            while (count($newChildren) > 0) {
                if ($iterationsCount > $this->maxIterations) {
                    throw new Exception('Processing nested resources reached the max iteration number');
                }
                $iterationsCount += 1;
                $newChildren = $this->childProcessor->processPendingCreations($newChildren);
                if (isset($newChildren['code'])) {
                    $this->testeableDBTransaction->rollBack();

                    return $newChildren;
                }
            }
        } catch (Throwable $e) {
            Log::error('Processing nested resources reached the max iteration number');
            $this->testeableDBTransaction->rollBack();

            return [
                'code' => $this->modelErrorCodeAccessor->getModelErrorCode($model, 'invalidData'),
                'data' => null,
            ];
        }

        //Execute after hook
        $insertedEntity->afterInsert($data);

        //Commit and return
        $this->testeableDBTransaction->commit();

        return [
            'code' => null,
            'data' => $insertedEntity->getObject($insertedEntity->id, $context),
        ];
    }

    /**
     * Given an id, an array of data, a model and a context
     * updates the data on the model and updates its nested children.
     * Then returns the response as requested by the
     * given context.
     *
     * @param int $entityId
     * @param array $data
     * @param string $modelName
     * @param App\Lib\RequestContext $context
     *
     * @return mixed
     */
    public function update(int $entityId, array $data, string $modelName, RequestContext $context)
    {
        $model = app()->make($modelName);

        //Find the object
        $entity = $model->getObject($entityId, $context);
        if (!$entity) {
            return [
                'code' => $this->modelErrorCodeAccessor->getModelErrorCode($model, 'notFound'),
                'data' => null,
            ];
        }

        //Check for permissions
        $permissionErrors = $this->checkUpdatePermissions($entity);
        if ($permissionErrors !== null) {
            return $permissionErrors;
        }

        //Check for validation
        $model->beforeValidate($data);
        $validationErrors = $this->entityValidator->validateEntity($data, $entity);
        if ($validationErrors !== null) {
            return $validationErrors;
        }

        //Cast the data
        $data = $this->castModelData($data, $model);

        $this->testeableDBTransaction->beginTransaction();
        //Execute before hook
        if ($filterResponse = $entity->beforeUpdate($data)) {
            $this->testeableDBTransaction->rollBack();

            return $filterResponse;
        }

        //Update the entity
        $updatedEntity = $this->entitySaver->updateAndSync($data, $entity);
        if (is_array($updatedEntity)) {
            $this->testeableDBTransaction->rollBack();

            return $updatedEntity;
        }

        //Get the children
        $newChildren = $this->newChildrenCalculator->getNewChildren(
            $updatedEntity,
            $data,
            $modelName
        );

        //Process the structure
        $iterationsCount = 0;

        try {
            while (count($newChildren) > 0) {
                if ($iterationsCount > $this->maxIterations) {
                    throw new Exception('Processing nested resources reached the max iteration number');
                }
                $iterationsCount += 1;
                $newChildren = $this->childProcessor->processPendingCreations($newChildren);
                if (isset($newChildren['code'])) {
                    $this->testeableDBTransaction->rollBack();

                    return $newChildren;
                }
            }
        } catch (Throwable $e) {
            Log::error('Processing nested resources reached the max iteration number');
            $this->testeableDBTransaction->rollBack();

            return [
                'code' => $this->modelErrorCodeAccessor->getModelErrorCode($model, 'invalidData'),
                'data' => null,
            ];
        }

        //Execute after hook
        $entity->afterUpdate($data);

        //Commit and return
        $this->testeableDBTransaction->commit();

        return [
            'code' => null,
            'data' => $model->getObject($entityId, $context),
        ];
    }

    /**
     * This funcion uses the model to process the
     * data coming from the request and cast it to
     * the defined data types on the model.
     *
     * @param array $data
     * @param App\Models\BaseModel $model
     *
     * @return array
     */
    private function castModelData(array $data, BaseModel $model)
    {
        $modelInstance = $model->newInstance($data);
        $modelKeys = array_keys($modelInstance->getAttributes());
        foreach ($modelKeys as $modelKey) {
            $data[$modelKey] = $modelInstance->{$modelKey};
        }

        return $data;
    }
}
