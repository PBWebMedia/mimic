<?php

namespace Pbweb\Mimic\Tests\Service;

use Pbweb\Mimic\Model\Action;
use Pbweb\Mimic\Service\EnqueuedActionCollection;

/**
 * Class EnqueuedActionCollectionFunctionalTest
 *
 * @copyright 2015 PB Web Media B.V.
 */
class EnqueuedActionCollectionFunctionalTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptyQueue()
    {
        $queue = new EnqueuedActionCollection();

        $expectedAction = $queue->getExpectedAction();
        $this->assertNull($expectedAction);

        $isExpectingThis = $queue->isExpecting('not-expecting-this', []);
        $this->assertFalse($isExpectingThis);
    }

    public function testWithActions()
    {
        $queue = new EnqueuedActionCollection();

        $actionOne = new Action('get', ['argument list one'], 'response one');
        $actionTwo = new Action('put', ['argument list two']); // No response
        $actionThree = new Action('flush', [], 'response three'); // No argument list

        $queue->add($actionOne);
        $queue->add($actionTwo);
        $queue->add($actionThree);

        // Not expecting the second action at this point
        $isExpectingActionTwo = $queue->isExpecting($actionTwo->getMethod(), $actionTwo->getArgumentList());
        $this->assertFalse($isExpectingActionTwo);

        // Expect first action
        $this->assertAction($actionOne, $queue);
        $this->assertFalse($queue->isEmpty());

        // Not expecting the first action anymore
        $isExpectingActionOne = $queue->isExpecting($actionOne->getMethod(), $actionOne->getArgumentList());
        $this->assertFalse($isExpectingActionOne);
        $this->assertEquals(
            [$actionTwo, $actionThree],
            $queue->getRemainingActionList()
        );

        // Expect second action
        $this->assertAction($actionTwo, $queue);
        $this->assertFalse($queue->isEmpty());

        // Expect third action
        $this->assertAction($actionThree, $queue);

        // Now the queue is empty
        $this->assertTrue($queue->isEmpty());

        // We can add stuff again and do it all over
        $queue->add($actionTwo);
        $this->assertFalse($queue->isEmpty());
        $this->assertAction($actionTwo, $queue);
        $this->assertTrue($queue->isEmpty());
    }

    public function testClear()
    {
        $queue = new EnqueuedActionCollection();
        $this->assertTrue($queue->isEmpty());

        $action = new Action('get', ['argument list one'], 'response one');
        $queue->add($action);
        $this->assertFalse($queue->isEmpty());

        $queue->clear();
        $this->assertTrue($queue->isEmpty());

        $queue->add($action);
        $this->assertFalse($queue->isEmpty());

        $queue->add($action);
        $queue->add($action);
        $queue->clear();
        $this->assertTrue($queue->isEmpty());
    }

    public function testThrow()
    {
        $exception = new \InvalidArgumentException('Invalid argument');
        $action = new Action('get', [], $exception, true);

        $queue = new EnqueuedActionCollection();
        $queue->add($action);

        $this->setExpectedException(get_class($exception), $exception->getMessage());

        $queue->fulfill();
    }

    private function assertAction(Action $action, EnqueuedActionCollection $queue)
    {
        $isExpectingAction = $queue->isExpecting($action->getMethod(), $action->getArgumentList());
        $this->assertTrue($isExpectingAction);
        $expectedAction = $queue->getExpectedAction();
        $this->assertSame($action, $expectedAction);

        $response = $queue->fulfill();
        $this->assertEquals($action->getResponse(), $response);
    }
}
