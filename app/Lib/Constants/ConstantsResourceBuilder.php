<?php

namespace App\Lib\Constants;

use App\Models\BaseModel;
use Exception;
use Illuminate\Support\Str;
use ReflectionClass;
use Throwable;

class ConstantsResourceBuilder
{
    /**
     * Model namespaces that would be loaded into Constants.
     *
     * @var array
     */
    protected $models = [
        \App\Models\GarmentType::class,
        \App\Models\Status::class,
        \App\Models\Color::class,
    ];

    /**
     * Creates the constants resource structure.
     *
     * @return array
     */
    public function getConstants()
    {
        $constants = [];
        foreach ($this->models as $model) {
            $constants = array_merge($constants, $this->createNodeForModel($model));
        }

        return $constants;
    }

    /**
     * Returns the count the length of the models.
     *
     * @return int
     */
    public function getConstantsModelCount()
    {
        return count($this->models);
    }

    /**
     * Creates a node for every entity of a model inheriting from BaseModel.
     *
     * @param string $model
     *
     * @throws Exception if the string do not belong
     * to a model namespace.
     *
     * @return array
     */
    public function createNodeForModel(string $model)
    {
        $nodeContent = [];

        try {
            $className = (new ReflectionClass($model))->getShortName();
            $nodeName = strtoupper(Str::snake($className));
            $entities = app()->make($model)->all();
        } catch (Throwable $e) {
            throw new Exception('Cannot query the model: ' . $model);
        }
        foreach ($entities as $entity) {
            $nodeContent = array_merge($nodeContent, $this->createNodeForEntity($entity));
        }

        return [$nodeName => $nodeContent];
    }

    /**
     * Creates a node for an entity of a model inheriting from BaseModel.
     *
     * @param \App\Models\BaseModel $entity
     *
     * @throws Exception if the model has not id, key or name
     * attributes.
     *
     * @return array
     */
    public function createNodeForEntity(BaseModel $entity)
    {
        if (is_null($entity->key) || is_null($entity->name) || is_null($entity->id)) {
            throw new Exception('Cannot create constant node for model: ' . get_class($entity));
        }
        $externalId = null;
        if (isset($entity->external_id)) {
            $externalId = $entity->external_id;
        } elseif (isset($entity->code)) {
            $externalId = $entity->code;
        }

        return [
            strtoupper($entity->key) => [
                'id' => $entity->id,
                'name' => $entity->name,
                'external_id' => $externalId,
            ],
        ];
    }
}
