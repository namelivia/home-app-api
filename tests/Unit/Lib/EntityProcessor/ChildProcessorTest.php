<?php

namespace Tests;

use App\Lib\EntityProcessor\ChildProcessor;
use App\Lib\EntityProcessor\EntitySaver;
use App\Lib\EntityProcessor\EntityValidator;
use App\Lib\EntityProcessor\NewChildrenCalculator;
use App\Models\BaseModel;
use Mockery;

class ChildProcessorTest extends TestCase
{
    protected $childProcessor;
    protected $newChildrenCalculator;
    protected $entitySaver;
    protected $entityValidator;
    protected $baseModel;
    protected $newEntity;
    protected $newEntities;

    public function setUp(): void
    {
        parent::setUp();
        $this->newChildrenCalculator = Mockery::mock(NewChildrenCalculator::class);
        $this->app->instance(NewChildrenCalculator::class, $this->newChildrenCalculator);
        $this->entitySaver = Mockery::mock(EntitySaver::class);
        $this->app->instance(EntitySaver::class, $this->entitySaver);
        $this->entityValidator = Mockery::mock(EntityValidator::class);
        $this->app->instance(EntityValidator::class, $this->entityValidator);
        $this->baseModel = Mockery::mock(BaseModel::class);
        $this->app->instance('className', $this->baseModel);
        $this->childProcessor = app()->make(ChildProcessor::class);

        $this->newEntity = [
            'className' => 'className',
            'data' => ['key' => 'value'],
            'parentClassname' => 'parentClassname',
            'parentId' => 'parentId',
        ];
        $this->newEntities = [$this->newEntity, $this->newEntity];
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testProcessingPendingCreations()
    {
        $this->entityValidator->shouldReceive('validateEntity')
            ->times(2)
            ->with([
                'key' => 'value',
                'parent_classname_id' => 'parentId',
            ], $this->baseModel)
            ->andReturn(null);
        $this->entitySaver->shouldReceive('storeAndSync')
            ->times(2)
            ->with([
                'key' => 'value',
                'parent_classname_id' => 'parentId',
            ], $this->baseModel)
            ->andReturn($this->baseModel);
        $this->baseModel->shouldReceive('offsetExists')
            ->times(2)
            ->with('code')
            ->andReturn(false);
        $this->newChildrenCalculator->shouldReceive('getNewChildren')
            ->times(2)
            ->with(
                $this->baseModel,
                [
                    'key' => 'value',
                    'parent_classname_id' => 'parentId',
                ],
                'className'
            )
            ->andReturn(['newChildren']);
        $result = $this->childProcessor->processPendingCreations($this->newEntities);
        $this->assertEquals(['newChildren', 'newChildren'], $result);
    }

    public function testIfTheEntitySaverFailsTheErrorWillBeReturned()
    {
        $this->entityValidator->shouldReceive('validateEntity')
            ->once()
            ->with([
                'key' => 'value',
                'parent_classname_id' => 'parentId',
            ], $this->baseModel)
            ->andReturn(null);
        $this->entitySaver->shouldReceive('storeAndSync')
            ->once()
            ->with([
                'key' => 'value',
                'parent_classname_id' => 'parentId',
            ], $this->baseModel)
            ->andReturn([
                'code' => 'entitySaverError',
                'data' => 'entitySaverErrorData',
            ]);
        $result = $this->childProcessor->processPendingCreations($this->newEntities);
        $this->assertEquals([
            'code' => 'entitySaverError',
            'data' => 'entitySaverErrorData',
        ], $result);
    }

    public function testIfTheEntityValidatorFailsTheErrorWillBeReturned()
    {
        $this->entityValidator->shouldReceive('validateEntity')
            ->once()
            ->with([
                'key' => 'value',
                'parent_classname_id' => 'parentId',
            ], $this->baseModel)
            ->andReturn([
                'code' => 'validatorErrorCode',
                'data' => 'validatorErrorData',
            ]);
        $result = $this->childProcessor->processPendingCreations($this->newEntities);
        $this->assertEquals([
            'code' => 'validatorErrorCode',
            'data' => 'validatorErrorData',
        ], $result);
    }
}
