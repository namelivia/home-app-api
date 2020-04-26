<?php

namespace Tests;

use App\Lib\EntityProcessor\EntitySaver;
use App\Lib\EntityProcessor\ModelErrorCodeAccessor;
use App\Models\BaseModel;
use Exception;
use Illuminate\Support\Facades\Log;
use Mockery;

class EntitySaverTest extends TestCase
{
    protected $entitySaver;
    protected $modelErrorCodeAccessor;
    protected $baseModelMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->modelErrorCodeAccessor = Mockery::mock(ModelErrorCodeAccessor::class);
        $this->app->instance(ModelErrorCodeAccessor::class, $this->modelErrorCodeAccessor);
        $this->baseModelMock = Mockery::mock(BaseModel::class);
        $this->entitySaver = app()->make(EntitySaver::class);
    }

    public function testStoringAnEntity()
    {
        $data = ['newData'];
        $this->baseModelMock->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($this->baseModelMock);
        $this->baseModelMock->shouldReceive('getSyncOnSave')
            ->once()
            ->with()
            ->andReturn(['foo' => 'bar']);
        $result = $this->entitySaver->storeAndSync($data, $this->baseModelMock);
        $this->assertEquals($this->baseModelMock, $result);
    }

    public function testFailingToStoreAnEntityRetunsAnErrorArray()
    {
        $data = ['newData'];
        $this->baseModelMock->shouldReceive('create')
            ->once()
            ->with($data)
            ->andThrow(new Exception('foobar'));
        Log::shouldReceive('error')
            ->once()
            ->with('Error when trying to store an entity. Message: foobar');
        $this->modelErrorCodeAccessor->shouldReceive('getModelErrorCode')
            ->once()
            ->with($this->baseModelMock, 'failedToCreate')
            ->andReturn('errorCode');
        $result = $this->entitySaver->storeAndSync($data, $this->baseModelMock);
        $this->assertEquals(['code' => 'errorCode', 'data' => null], $result);
    }

    public function testUpdatingAnEntity()
    {
        $data = ['newData'];
        $this->baseModelMock->shouldReceive('fill')
            ->once()
            ->with($data)
            ->andReturn($this->baseModelMock);
        $this->baseModelMock->shouldReceive('save')
            ->once()
            ->with()
            ->andReturn($this->baseModelMock);
        $this->baseModelMock->shouldReceive('getAttribute')
            ->once()
            ->with('id')
            ->andReturn('objectId');
        $this->baseModelMock->shouldReceive('getObject')
            ->once()
            ->with('objectId')
            ->andReturn($this->baseModelMock);
        $this->baseModelMock->shouldReceive('getSyncOnSave')
            ->once()
            ->with()
            ->andReturn(['foo' => 'bar']);
        $result = $this->entitySaver->updateAndSync($data, $this->baseModelMock);
        $this->assertEquals($this->baseModelMock, $result);
    }

    public function testFailingToUpdateAnEntityRetunsAnErrorArray()
    {
        $data = ['newData'];
        $this->baseModelMock->shouldReceive('fill')
            ->once()
            ->with($data)
            ->andThrow(new Exception('foobar'));
        Log::shouldReceive('error')
            ->once()
            ->with('Error when trying to update an entity. Message: foobar');
        $this->modelErrorCodeAccessor->shouldReceive('getModelErrorCode')
            ->once()
            ->with($this->baseModelMock, 'failedToUpdate')
            ->andReturn('errorCode');
        $result = $this->entitySaver->updateAndSync($data, $this->baseModelMock);
        $this->assertEquals(['code' => 'errorCode', 'data' => null], $result);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
