<?php

namespace Tests;

use App\Lib\EntityProcessor\NewChildrenCalculator;
use App\Models\BaseModel;
use Mockery;

class NewChildrenCalculatorTest extends TestCase
{
    protected $newChildrenCalculator;

    public function setUp(): void
    {
        parent::setUp();
        $this->newChildrenCalculator = app()->make(NewChildrenCalculator::class);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testGeneratingTheNewChildrenToBeProcessed()
    {
        $entity = Mockery::mock(BaseModel::class);
        $data = [
            'key' => 'value',
            'children1' => [
                'child11key' => 'child11value',
                'child12key' => 'child12value',
            ],
            'children2' => [
                'child21key' => 'child21value',
            ],
        ];
        $className = 'className';
        $entity->shouldReceive('getChildren')
            ->once()
            ->with()
            ->andReturn([
                'children1' => 'Child1Class',
                'children2' => 'Child2Class',
            ]);
        $entity->shouldReceive('children1')
            ->times(2)
            ->with()
            ->andReturn($entity);
        $entity->shouldReceive('children2')
            ->once()
            ->with()
            ->andReturn($entity);
        $entity->shouldReceive('delete')
            ->times(3)
            ->with()
            ->andReturn($entity);
        $entity->shouldReceive('getAttribute')
            ->times(3)
            ->with('id')
            ->andReturn('entityId');
        $result = $this->newChildrenCalculator->getNewChildren($entity, $data, $className);
        $expectedResult = [
            [
                'parentId' => 'entityId',
                'parentClassname' => 'className',
                'className' => 'Child1Class',
                'data' => 'child11value',
            ], [
                'parentId' => 'entityId',
                'parentClassname' => 'className',
                'className' => 'Child1Class',
                'data' => 'child12value',
            ], [
                'parentId' => 'entityId',
                'parentClassname' => 'className',
                'className' => 'Child2Class',
                'data' => 'child21value',
            ],
        ];
        $this->assertEquals($expectedResult, $result);
    }
}
