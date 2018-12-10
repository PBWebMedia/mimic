<?php

namespace Pbweb\Mimic\Service;

use Pbweb\Mimic\Exception\UnexpectedActionException;
use Pbweb\Mimic\Model\Action;
use PHPUnit\Framework\TestCase;

/**
 * @copyright 2015 PB Web Media B.V.
 */
class MimicActionHandlerFunctionalTest extends TestCase
{
    /** @var SampleMimic */
    private $mimic;

    protected function setUp()
    {
        $this->mimic = new SampleMimic();
    }

    public function testQueueState()
    {
        // Default the queue should be disabled
        // If the queue is disabled then all calls will just return null
        $this->assertFalse($this->mimic->isQueueEnabled());

        $this->mimic->enableQueue();
        $this->assertTrue($this->mimic->isQueueEnabled());

        $this->mimic->disableQueue();
        $this->assertFalse($this->mimic->isQueueEnabled());

        // If we do an action while the queue is disabled it should just return null
        $result = $this->mimic->get(1);
        $this->assertNull($result);

        // Also the queue should automatically get enabled when an action got queued
        $this->mimic->enqueue('get');
        $this->assertTrue($this->mimic->isQueueEnabled());
    }

    public function testQueue()
    {
        $actionOne = new Action(
            'get',
            [1],
            'success'
        );
        $actionTwo = new Action(
            'update',
            [2, 'data'],
            true
        );

        // Check the queue is finished and empty
        $this->assertTrue($this->mimic->isFinished());
        $this->assertEquals([], $this->mimic->getQueueContent());

        // Add first action to the queue
        $this->mimic->enqueue(
            $actionOne->getMethod(),
            $actionOne->getArgumentList(),
            $actionOne->getResponse()
        );
        $this->assertFalse($this->mimic->isFinished());
        $this->assertEquals([$actionOne], $this->mimic->getQueueContent());

        // Add second action to the queue
        $this->mimic->enqueue(
            $actionTwo->getMethod(),
            $actionTwo->getArgumentList(),
            $actionTwo->getResponse()
        );
        $this->assertFalse($this->mimic->isFinished());
        $this->assertEquals([$actionOne, $actionTwo], $this->mimic->getQueueContent());

        // Invoke the first action
        $result = call_user_func_array(
            [$this->mimic, $actionOne->getMethod()],
            $actionOne->getArgumentList()
        );
        $this->assertEquals($actionOne->getResponse(), $result);

        // Now we only have the second action left in the queue
        $this->assertFalse($this->mimic->isFinished());
        $this->assertEquals([$actionTwo], $this->mimic->getQueueContent());

        // Invoke the second action
        $result = call_user_func_array(
            [$this->mimic, $actionTwo->getMethod()],
            $actionTwo->getArgumentList()
        );
        $this->assertEquals($actionTwo->getResponse(), $result);

        // Queue should be empty by now
        $this->assertTrue($this->mimic->isFinished());
        $this->assertEquals([], $this->mimic->getQueueContent());
    }

    public function testInvalidAction()
    {
        $this->expectException(UnexpectedActionException::class);
        $this->expectExceptionMessage('Unexpected call to method "get" with the given argument list.' . PHP_EOL . 'No more calls where expected.');

        $this->mimic->enableQueue();
        $this->mimic->get(1);
    }

    public function testClearTheQueue()
    {
        $actionOne = new Action(
            'update',
            [1, '{document 1}'],
            true
        );

        $this->mimic->enqueue(
            $actionOne->getMethod(),
            $actionOne->getArgumentList(),
            $actionOne->getResponse()
        );
        $this->mimic->clearQueue();

        $this->assertTrue($this->mimic->isFinished());
        $this->assertEquals([], $this->mimic->getQueueContent());
    }

    /**
     * @dataProvider getMethodsData
     */
    public function testMethodGoesToQueue(string $method, array $argumentList = [])
    {
        $expectedResponse = 'response';
        $this->mimic->enqueue($method, $argumentList, $expectedResponse);

        $result = call_user_func_array(
            [$this->mimic, $method],
            $argumentList
        );

        $this->assertEquals($expectedResponse, $result);
    }

    public function getMethodsData()
    {
        return [
            ['get', [100], '{document 100}'],
            ['update', [100, '{document 1}'], true],
        ];
    }

    public function testFulFillException()
    {
        $exception = new \InvalidArgumentException('Invalid argument');
        $this->mimic->enqueue('get', [1], $exception, true);

        $this->setExpectedException(get_class($exception), $exception->getMessage());

        $this->mimic->get(1);
    }
}
