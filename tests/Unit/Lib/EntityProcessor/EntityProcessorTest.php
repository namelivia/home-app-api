<?php
namespace Tests;

use App\Lib\EntityProcessor\EntityProcessor;
use App\Lib\EntityProcessor\EntityValidator;
use App\Lib\EntityProcessor\ChildProcessor;
use App\Lib\EntityProcessor\NewChildrenCalculator;
use App\Lib\EntityProcessor\EntitySaver;
use App\Lib\EntityProcessor\ModelErrorCodeAccessor;
use App\Lib\EntityProcessor\TesteableDBTransaction;
use App\Lib\Constants\ErrorCodes;
use App\Models\BaseModel;
use App\Lib\RequestContext;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Mockery;

class EntityProcessorTest extends TestCase
{
	protected $entityProcessor;
	protected $childProcessor;
	protected $newChildrenCalculator;
	protected $entitySaver;
	protected $modelErrorCodeAccessor;
	protected $entityValidator;
	protected $testeableDBTransaction;
	protected $baseModel;

	public function setUp(): void
	{
		parent::setUp();
		$this->entityValidator = Mockery::mock(EntityValidator::class);
		$this->app->instance(EntityValidator::class, $this->entityValidator);
		$this->childProcessor = Mockery::mock(ChildProcessor::class);
		$this->app->instance(ChildProcessor::class, $this->childProcessor);
		$this->newChildrenCalculator = Mockery::mock(NewChildrenCalculator::class);
		$this->app->instance(NewChildrenCalculator::class, $this->newChildrenCalculator);
		$this->entitySaver = Mockery::mock(EntitySaver::class);
		$this->app->instance(EntitySaver::class, $this->entitySaver);
		$this->modelErrorCodeAccessor = Mockery::mock(ModelErrorCodeAccessor::class);
		$this->app->instance(ModelErrorCodeAccessor::class, $this->modelErrorCodeAccessor);
		$this->baseModel = Mockery::mock(BaseModel::class);
		$this->app->instance('modelName', $this->baseModel);
		$this->testeableDBTransaction = Mockery::mock(TesteableDBTransaction::class);
		$this->app->instance(TesteableDBTransaction::class, $this->testeableDBTransaction);
		$this->entityProcessor = app()->make(EntityProcessor::class);
	}

	public function tearDown(): void
	{
		parent::tearDown();
		Mockery::close();
	}

	//STORING
	public function testStoringChecksForGatePermissions()
	{
		$data = ['data'];
		$modelName = 'modelName';
		$context = Mockery::mock(RequestContext::class);
		Gate::shouldReceive('denies')
			->once()
			->with('create', $this->baseModel)
			->andReturn(true);
		$result = $this->entityProcessor->store($data, $modelName, $context);
		$this->assertEquals([
			'code' => ErrorCodes::USER_NOT_AUTHORIZED,
			'data' => null
		], $result);
	}

	public function testStoringChecksForValidation()
	{
		$data = ['data'];
		$modelName = 'modelName';
		$context = Mockery::mock(RequestContext::class);
		Gate::shouldReceive('denies')
			->once()
			->with('create', $this->baseModel)
			->andReturn(false);
		$this->baseModel->shouldReceive('beforeValidate')
			->once()
			->andReturn(null);
		$this->entityValidator->shouldReceive('validateEntity')
			->once()
			->with($data, $this->baseModel)
			->andReturn('entityValidatorError');
		$result = $this->entityProcessor->store($data, $modelName, $context);
		$this->assertEquals('entityValidatorError', $result);
	}

	public function testIfTheBeforeInsertReturnsTheFunctionReturnsIt()
	{
		$data = ['data'];
		$modelName = 'modelName';
		$context = Mockery::mock(RequestContext::class);
		$this->testeableDBTransaction->shouldReceive('beginTransaction')
			->once()
			->with();
		Gate::shouldReceive('denies')
			->once()
			->with('create', $this->baseModel)
			->andReturn(false);
		$this->baseModel->shouldReceive('beforeValidate')
			->once()
			->andReturn(null);
		$this->entityValidator->shouldReceive('validateEntity')
			->once()
			->with($data, $this->baseModel)
			->andReturn(null);
		$this->baseModel->shouldReceive('newInstance')
			->once()
			->andReturn($this->baseModel);
		$this->baseModel->shouldReceive('getAttributes')
			->once()
			->andReturn([]);
		$this->baseModel->shouldReceive('beforeInsert')
			->once()
			->andReturn('beforeInsertResponse');
		$this->testeableDBTransaction->shouldReceive('rollBack')
			->once()
			->with();
		$result = $this->entityProcessor->store($data, $modelName, $context);
		$this->assertEquals('beforeInsertResponse', $result);
	}

	public function testIfTheEntitySaverFailsRollbackAndReturn()
	{
		$data = ['data'];
		$modelName = 'modelName';
		$context = Mockery::mock(RequestContext::class);
		$this->testeableDBTransaction->shouldReceive('beginTransaction')
			->once()
			->with();
		Gate::shouldReceive('denies')
			->once()
			->with('create', $this->baseModel)
			->andReturn(false);
		$this->baseModel->shouldReceive('beforeValidate')
			->once()
			->andReturn(null);
		$this->entityValidator->shouldReceive('validateEntity')
			->once()
			->with($data, $this->baseModel)
			->andReturn(null);
		$this->baseModel->shouldReceive('newInstance')
			->once()
			->andReturn($this->baseModel);
		$this->baseModel->shouldReceive('getAttributes')
			->once()
			->andReturn([]);
		$this->baseModel->shouldReceive('beforeInsert')
			->once()
			->andReturn(null);
		$this->entitySaver->shouldReceive('storeAndSync')
			->once()
			->with($data, $this->baseModel)
			->andReturn(['entitySaverError']);
		$this->testeableDBTransaction->shouldReceive('rollBack')
			->once()
			->with();
		$result = $this->entityProcessor->store($data, $modelName, $context);
		$this->assertEquals(['entitySaverError'], $result);
	}

	public function testStoringStoresAnEntity()
	{
		$data = ['data'];
		$modelName = 'modelName';
		$context = Mockery::mock(RequestContext::class);
		$this->testeableDBTransaction->shouldReceive('beginTransaction')
			->once()
			->with();
		Gate::shouldReceive('denies')
			->once()
			->with('create', $this->baseModel)
			->andReturn(false);
		$this->baseModel->shouldReceive('beforeValidate')
			->once()
			->andReturn(null);
		$this->entityValidator->shouldReceive('validateEntity')
			->once()
			->with($data, $this->baseModel)
			->andReturn(null);
		$this->baseModel->shouldReceive('newInstance')
			->once()
			->andReturn($this->baseModel);
		$this->baseModel->shouldReceive('getAttributes')
			->once()
			->andReturn([]);
		$this->baseModel->shouldReceive('beforeInsert')
			->once()
			->andReturn(null);
		$this->entitySaver->shouldReceive('storeAndSync')
			->once()
			->with($data, $this->baseModel)
			->andReturn($this->baseModel);
		$this->newChildrenCalculator->shouldReceive('getNewChildren')
			->once()
			->with($this->baseModel, $data, $modelName)
			->andReturn(['children']);
		$this->childProcessor->shouldReceive('processPendingCreations')
			->once()
			->with(['children'])
			->andReturn(['children']);
		$this->childProcessor->shouldReceive('processPendingCreations')
			->once()
			->with(['children'])
			->andReturn([]);
		$this->baseModel->shouldReceive('afterInsert')
			->once()
			->andReturn(null);
		$this->testeableDBTransaction->shouldReceive('commit')
			->once()
			->with();
		$this->baseModel->shouldReceive('getAttribute')
			->once()
			->with('id')
			->andReturn('entityId');
		$this->baseModel->shouldReceive('getObject')
			->once()
			->with('entityId', $context)
			->andReturn('insertedEntity');
		$result = $this->entityProcessor->store($data, $modelName, $context);
		$this->assertEquals([
			'code' => null,
			'data' => 'insertedEntity'
		], $result);
	}

	public function testStoringAvoidsAnInfiniteLoop()
	{
		$data = ['data'];
		$modelName = 'modelName';
		$context = Mockery::mock(RequestContext::class);
		$this->testeableDBTransaction->shouldReceive('beginTransaction')
			->once()
			->with();
		Gate::shouldReceive('denies')
			->once()
			->with('create', $this->baseModel)
			->andReturn(false);
		$this->baseModel->shouldReceive('beforeValidate')
			->once()
			->andReturn(null);
		$this->entityValidator->shouldReceive('validateEntity')
			->once()
			->with($data, $this->baseModel)
			->andReturn(null);
		$this->baseModel->shouldReceive('newInstance')
			->once()
			->andReturn($this->baseModel);
		$this->baseModel->shouldReceive('getAttributes')
			->once()
			->andReturn([]);
		$this->baseModel->shouldReceive('beforeInsert')
			->once()
			->andReturn(null);
		$this->entitySaver->shouldReceive('storeAndSync')
			->once()
			->with($data, $this->baseModel)
			->andReturn($this->baseModel);
		$this->newChildrenCalculator->shouldReceive('getNewChildren')
			->once()
			->with($this->baseModel, $data, $modelName)
			->andReturn(['children']);
		$this->childProcessor->shouldReceive('processPendingCreations')
			->with(['children'])
			->andReturn(['children']);
		Log::shouldReceive('error')
			->once()
			->with('Processing nested resources reached the max iteration number');
		$this->testeableDBTransaction->shouldReceive('rollBack')
			->once()
			->with();
		$this->modelErrorCodeAccessor->shouldReceive('getModelErrorCode')
			->once()
			->with($this->baseModel, 'invalidData')
			->andReturn('invalidDataErrorCode');
		$result = $this->entityProcessor->store($data, $modelName, $context);
		$this->assertEquals([
			'code' => 'invalidDataErrorCode',
			'data' => null
		], $result);
	}

	public function testIfProcessingTheChildrenWhenStoringReturnsTheError()
	{
		$data = ['data'];
		$modelName = 'modelName';
		$context = Mockery::mock(RequestContext::class);
		$this->testeableDBTransaction->shouldReceive('beginTransaction')
			->once()
			->with();
		Gate::shouldReceive('denies')
			->once()
			->with('create', $this->baseModel)
			->andReturn(false);
		$this->baseModel->shouldReceive('beforeValidate')
			->once()
			->andReturn(null);
		$this->entityValidator->shouldReceive('validateEntity')
			->once()
			->with($data, $this->baseModel)
			->andReturn(null);
		$this->baseModel->shouldReceive('newInstance')
			->once()
			->andReturn($this->baseModel);
		$this->baseModel->shouldReceive('getAttributes')
			->once()
			->andReturn([]);
		$this->baseModel->shouldReceive('beforeInsert')
			->once()
			->andReturn(null);
		$this->entitySaver->shouldReceive('storeAndSync')
			->once()
			->with($data, $this->baseModel)
			->andReturn($this->baseModel);
		$this->newChildrenCalculator->shouldReceive('getNewChildren')
			->once()
			->with($this->baseModel, $data, $modelName)
			->andReturn(['children']);
		$this->childProcessor->shouldReceive('processPendingCreations')
			->once()
			->with(['children'])
			->andReturn(['code' => 'errorCode', 'data' => 'errorData']);
		$this->testeableDBTransaction->shouldReceive('rollBack')
			->once()
			->with();
		$result = $this->entityProcessor->store($data, $modelName, $context);
		$this->assertEquals([
			'code' => 'errorCode',
			'data' => 'errorData'
		], $result);
	}

	//UPDATING
	public function testUpdatingANonExistingObjectReturnsAnError()
	{
		$data = ['data'];
		$entityId = 1;
		$modelName = 'modelName';
		$context = Mockery::mock(RequestContext::class);
		$this->baseModel->shouldReceive('getObject')
			->once()
			->with($entityId, $context)
			->andReturn(null);
		$this->modelErrorCodeAccessor->shouldReceive('getModelErrorCode')
			->once()
			->with($this->baseModel, 'notFound')
			->andReturn('notFoundErrorCode');
		$result = $this->entityProcessor->update($entityId, $data, $modelName, $context);
		$this->assertEquals([
			'code' => 'notFoundErrorCode',
			'data' => null
		], $result);
	}

	public function testUpdatingChecksForGatePermissions()
	{
		$data = ['data'];
		$entityId = 1;
		$modelName = 'modelName';
		$context = Mockery::mock(RequestContext::class);
		$this->baseModel->shouldReceive('getObject')
			->once()
			->with($entityId, $context)
			->andReturn($this->baseModel);
		Gate::shouldReceive('denies')
			->once()
			->with('update', $this->baseModel)
			->andReturn(true);
		$result = $this->entityProcessor->update($entityId, $data, $modelName, $context);
		$this->assertEquals([
			'code' => ErrorCodes::USER_NOT_AUTHORIZED,
			'data' => null
		], $result);
	}

	public function testUpdatingChecksForValidation()
	{
		$data = ['data'];
		$entityId = 1;
		$modelName = 'modelName';
		$context = Mockery::mock(RequestContext::class);
		$this->baseModel->shouldReceive('getObject')
			->once()
			->with($entityId, $context)
			->andReturn($this->baseModel);
		Gate::shouldReceive('denies')
			->once()
			->with('update', $this->baseModel)
			->andReturn(false);
		$this->baseModel->shouldReceive('beforeValidate')
			->once()
			->andReturn(null);
		$this->entityValidator->shouldReceive('validateEntity')
			->once()
			->with($data, $this->baseModel)
			->andReturn('validationErrors');
		$result = $this->entityProcessor->update($entityId, $data, $modelName, $context);
		$this->assertEquals('validationErrors', $result);
	}

	public function testIfTheBeforeUpdateReturnsTheFunctionReturnsIt()
	{
		$data = ['data'];
		$entityId = 1;
		$modelName = 'modelName';
		$context = Mockery::mock(RequestContext::class);
		$this->testeableDBTransaction->shouldReceive('beginTransaction')
			->once()
			->with();
		$this->baseModel->shouldReceive('getObject')
			->once()
			->with($entityId, $context)
			->andReturn($this->baseModel);
		Gate::shouldReceive('denies')
			->once()
			->with('update', $this->baseModel)
			->andReturn(false);
		$this->baseModel->shouldReceive('beforeValidate')
			->once()
			->andReturn(null);
		$this->entityValidator->shouldReceive('validateEntity')
			->once()
			->with($data, $this->baseModel)
			->andReturn(null);
		$this->baseModel->shouldReceive('newInstance')
			->once()
			->andReturn($this->baseModel);
		$this->baseModel->shouldReceive('getAttributes')
			->once()
			->andReturn([]);
		$this->baseModel->shouldReceive('beforeUpdate')
			->once()
			->andReturn('beforeUpdateResponse');
		$this->testeableDBTransaction->shouldReceive('rollBack')
			->once()
			->with();
		$result = $this->entityProcessor->update($entityId, $data, $modelName, $context);
		$this->assertEquals('beforeUpdateResponse', $result);
	}

	public function testIfTheEntitySaverFailsRollbackAndReturnWhenUpdating()
	{
		$data = ['data'];
		$modelName = 'modelName';
		$entityId = 1;
		$context = Mockery::mock(RequestContext::class);
		$this->testeableDBTransaction->shouldReceive('beginTransaction')
			->once()
			->with();
		$this->baseModel->shouldReceive('getObject')
			->once()
			->with($entityId, $context)
			->andReturn($this->baseModel);
		Gate::shouldReceive('denies')
			->once()
			->with('update', $this->baseModel)
			->andReturn(false);
		$this->baseModel->shouldReceive('beforeValidate')
			->once()
			->andReturn(null);
		$this->entityValidator->shouldReceive('validateEntity')
			->once()
			->with($data, $this->baseModel)
			->andReturn(null);
		$this->baseModel->shouldReceive('newInstance')
			->once()
			->andReturn($this->baseModel);
		$this->baseModel->shouldReceive('getAttributes')
			->once()
			->andReturn([]);
		$this->baseModel->shouldReceive('beforeUpdate')
			->once()
			->andReturn(null);
		$this->entitySaver->shouldReceive('updateAndSync')
			->once()
			->with($data, $this->baseModel)
			->andReturn(['entitySaverError']);
		$this->testeableDBTransaction->shouldReceive('rollBack')
			->once()
			->with();
		$result = $this->entityProcessor->update($entityId, $data, $modelName, $context);
		$this->assertEquals(['entitySaverError'], $result);
	}

	public function testUpdatingUpdatesAnEntity()
	{
		$data = ['data'];
		$entityId = 1;
		$modelName = 'modelName';
		$context = Mockery::mock(RequestContext::class);
		$this->testeableDBTransaction->shouldReceive('beginTransaction')
			->once()
			->with();
		$this->baseModel->shouldReceive('getObject')
			->once()
			->with($entityId, $context)
			->andReturn($this->baseModel);
		Gate::shouldReceive('denies')
			->once()
			->with('update', $this->baseModel)
			->andReturn(false);
		$this->baseModel->shouldReceive('beforeValidate')
			->once()
			->andReturn(null);
		$this->entityValidator->shouldReceive('validateEntity')
			->once()
			->with($data, $this->baseModel)
			->andReturn(null);
		$this->baseModel->shouldReceive('newInstance')
			->once()
			->andReturn($this->baseModel);
		$this->baseModel->shouldReceive('getAttributes')
			->once()
			->andReturn([]);
		$this->baseModel->shouldReceive('beforeUpdate')
			->once()
			->andReturn(null);
		$this->entitySaver->shouldReceive('updateAndSync')
			->once()
			->with($data, $this->baseModel)
			->andReturn($this->baseModel);
		$this->newChildrenCalculator->shouldReceive('getNewChildren')
			->once()
			->with($this->baseModel, $data, $modelName)
			->andReturn(['children']);
		$this->childProcessor->shouldReceive('processPendingCreations')
			->once()
			->with(['children'])
			->andReturn(['children']);
		$this->childProcessor->shouldReceive('processPendingCreations')
			->once()
			->with(['children'])
			->andReturn([]);
		$this->baseModel->shouldReceive('afterUpdate')
			->once()
			->andReturn(null);
		$this->testeableDBTransaction->shouldReceive('commit')
			->once()
			->with();
		$this->baseModel->shouldReceive('getObject')
			->once()
			->with(1, $context)
			->andReturn('updatedEntity');
		$result = $this->entityProcessor->update($entityId, $data, $modelName, $context);
		$this->assertEquals([
			'code' => null,
			'data' => 'updatedEntity'
		], $result);
	}

	public function testUpdatingAvoidsAnInfiniteLoop()
	{
		$data = ['data'];
		$entityId = 1;
		$modelName = 'modelName';
		$context = Mockery::mock(RequestContext::class);
		$this->testeableDBTransaction->shouldReceive('beginTransaction')
			->once()
			->with();
		$this->baseModel->shouldReceive('getObject')
			->once()
			->with($entityId, $context)
			->andReturn($this->baseModel);
		Gate::shouldReceive('denies')
			->once()
			->with('update', $this->baseModel)
			->andReturn(false);
		$this->baseModel->shouldReceive('beforeValidate')
			->once()
			->andReturn(null);
		$this->entityValidator->shouldReceive('validateEntity')
			->once()
			->with($data, $this->baseModel)
			->andReturn(null);
		$this->baseModel->shouldReceive('newInstance')
			->once()
			->andReturn($this->baseModel);
		$this->baseModel->shouldReceive('getAttributes')
			->once()
			->andReturn([]);
		$this->baseModel->shouldReceive('beforeUpdate')
			->once()
			->andReturn(null);
		$this->entitySaver->shouldReceive('updateAndSync')
			->once()
			->with($data, $this->baseModel)
			->andReturn($this->baseModel);
		$this->newChildrenCalculator->shouldReceive('getNewChildren')
			->once()
			->with($this->baseModel, $data, $modelName)
			->andReturn(['children']);
		$this->childProcessor->shouldReceive('processPendingCreations')
			->with(['children'])
			->andReturn(['children']);
		Log::shouldReceive('error')
			->once()
			->with('Processing nested resources reached the max iteration number');
		$this->testeableDBTransaction->shouldReceive('rollBack')
			->once()
			->with();
		$this->modelErrorCodeAccessor->shouldReceive('getModelErrorCode')
			->once()
			->with($this->baseModel, 'invalidData')
			->andReturn('invalidDataErrorCode');
		$result = $this->entityProcessor->update($entityId, $data, $modelName, $context);
		$this->assertEquals([
			'code' => 'invalidDataErrorCode',
			'data' => null
		], $result);
	}

	public function testIfProcessingTheChildrenWhenUpdatingReturnsTheError()
	{
		$data = ['data'];
		$entityId = 1;
		$modelName = 'modelName';
		$context = Mockery::mock(RequestContext::class);
		$this->testeableDBTransaction->shouldReceive('beginTransaction')
			->once()
			->with();
		$this->baseModel->shouldReceive('getObject')
			->once()
			->with($entityId, $context)
			->andReturn($this->baseModel);
		Gate::shouldReceive('denies')
			->once()
			->with('update', $this->baseModel)
			->andReturn(false);
		$this->baseModel->shouldReceive('beforeValidate')
			->once()
			->andReturn(null);
		$this->entityValidator->shouldReceive('validateEntity')
			->once()
			->with($data, $this->baseModel)
			->andReturn(null);
		$this->baseModel->shouldReceive('newInstance')
			->once()
			->andReturn($this->baseModel);
		$this->baseModel->shouldReceive('getAttributes')
			->once()
			->andReturn([]);
		$this->baseModel->shouldReceive('beforeUpdate')
			->once()
			->andReturn(null);
		$this->entitySaver->shouldReceive('updateAndSync')
			->once()
			->with($data, $this->baseModel)
			->andReturn($this->baseModel);
		$this->newChildrenCalculator->shouldReceive('getNewChildren')
			->once()
			->with($this->baseModel, $data, $modelName)
			->andReturn(['children']);
		$this->childProcessor->shouldReceive('processPendingCreations')
			->once()
			->with(['children'])
			->andReturn(['code' => 'errorCode', 'data' => 'errorData']);
		$this->testeableDBTransaction->shouldReceive('rollBack')
			->once()
			->with();
		$result = $this->entityProcessor->update($entityId, $data, $modelName, $context);
		$this->assertEquals([
			'code' => 'errorCode',
			'data' => 'errorData'
		], $result);
	}
}
