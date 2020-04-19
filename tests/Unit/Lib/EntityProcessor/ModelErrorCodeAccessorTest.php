<?php
namespace Tests;

use App\Lib\EntityProcessor\ModelErrorCodeAccessor;
use App\Models\BaseModel;
use Mockery;
use Exception;

class ModelErrorCodeAccessorTest extends TestCase
{
	protected $modelErrorCodeAccessor;

	public function setUp(): void
	{
		parent::setUp();
		$this->modelErrorCodeAccessor = app()->make(ModelErrorCodeAccessor::class);
	}

	public function tearDown(): void
	{
		parent::tearDown();
		Mockery::close();
	}

	public function testGettingTheModelErrorCodeForAnEntity()
	{
		$model = Mockery::mock(BaseModel::class);
		$model->shouldReceive('getErrorCodes')
			->once()
			->with()
			->andReturn([
				'key' => 'errorCode'
			]);
		$result = $this->modelErrorCodeAccessor->getModelErrorCode($model, 'key');
		$this->assertEquals('errorCode', $result);
	}

	/**
	 ** @expectedException Exception
	 ** @expectedExceptionMessageRegExp /The model Mockery_[0-9]*_App_Models_BaseModel has no defined error code for the key foobar/
	 **/
	public function testInvalidKeyThrownsAnException()
	{
		$model = Mockery::mock(BaseModel::class);
		$model->shouldReceive('getErrorCodes')
			->once()
			->with()
			->andReturn([
				'key' => 'errorCode'
			]);
		$this->modelErrorCodeAccessor->getModelErrorCode($model, 'foobar');
	}
}
