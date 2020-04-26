<?php

namespace Tests;

use App\Lib\EntityProcessor\EntityValidator;
use App\Lib\EntityProcessor\ModelErrorCodeAccessor;
use App\Lib\ValidatorHelper;
use App\Models\BaseModel;
use Illuminate\Support\Facades\Validator;
use Mockery;

class EntityValidatorTest extends TestCase
{
    protected $entityValidator;
    protected $validatorHelper;
    protected $modelErrorCodeAccessor;

    public function setUp(): void
    {
        parent::setUp();
        $this->validatorHelper = Mockery::mock(ValidatorHelper::class);
        $this->app->instance(ValidatorHelper::class, $this->validatorHelper);
        $this->modelErrorCodeAccessor = Mockery::mock(ModelErrorCodeAccessor::class);
        $this->app->instance(ModelErrorCodeAccessor::class, $this->modelErrorCodeAccessor);
        $this->baseModel = Mockery::mock(BaseModel::class);
        $this->entityValidator = app()->make(EntityValidator::class);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    /*public function testAPassingValidator()
    {
        $data = ['data'];
        $this->baseModel->shouldReceive('getAttribute')
            ->times(2)
            ->with('id')
            ->andReturn(1);
        $this->baseModel->shouldReceive('getValidationRules')
            ->once()
            ->with(1)
            ->andReturn(['validationRules']);
        $validatorMock = Mockery::mock();
        Validator::shouldReceive('make')
            ->once()
            ->with($data, ['validationRules'])
            ->andReturn($validatorMock);
        $validatorMock->shouldReceive('fails')
            ->once()
            ->with()
            ->andReturn(false);
        $result = $this->entityValidator->validateEntity($data, $this->baseModel);
        $this->assertEquals(null, $result);
    }*/

    /*public function testAFailingValidator()
    {
        $data = ['data'];
        $this->baseModel->shouldReceive('getAttribute')
            ->times(2)
            ->with('id')
            ->andReturn(1);
        $this->baseModel->shouldReceive('getValidationRules')
            ->once()
            ->with(1)
            ->andReturn(['validationRules']);
        $validatorMock = Mockery::mock();
        Validator::shouldReceive('make')
            ->once()
            ->with($data, ['validationRules'])
            ->andReturn($validatorMock);
        $validatorMock->shouldReceive('fails')
            ->once()
            ->with()
            ->andReturn(true);
        $this->modelErrorCodeAccessor->shouldReceive('getModelErrorCode')
            ->once()
            ->with($this->baseModel, 'invalidData')
            ->andReturn('modelInvalidDataErrorCode');
        $validatorMock->shouldReceive('errors')
            ->once()
            ->with()
            ->andReturn($validatorMock);
        $validatorMock->shouldReceive('getMessages')
            ->once()
            ->with()
            ->andReturn('errorMessages');
        $this->validatorHelper->shouldReceive('parseValidationErrors')
            ->once()
            ->with('errorMessages')
            ->andReturn('parsedErrorMessages');
        $result = $this->entityValidator->validateEntity($data, $this->baseModel);
        $this->assertEquals([
            'code' => 'modelInvalidDataErrorCode',
            'data' => 'parsedErrorMessages'
        ], $result);
    }*/
}
