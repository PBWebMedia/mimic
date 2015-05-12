<?php

namespace Pbweb\Mimic\Traits;

use Pbweb\Mimic\Exception\UnexpectedActionException;
use Pbweb\Mimic\Model\Action;
use Pbweb\Mimic\Service\EnqueuedActionCollection;

/**
 * Traits MockActionTrait
 *
 * @copyright 2015 PB Web Media B.V.
 */
trait MockActionTrait
{
    /** @var EnqueuedActionCollection|null */
    private $queue;

    /**
     * Do we make use of the queue or ignore the calls?
     *
     * @var bool
     */
    private $useQueue = false;

    /**
     * @param string $method
     * @param array  $argumentList
     * @param mixed  $response
     * @param bool   $throw
     */
    public function enqueue($method, array $argumentList = [], $response = null, $throw = false)
    {
        $this->prepareQueueIfNull();
        $this->enableQueue();

        $this->queue->add(
            new Action($method, $argumentList, $response, $throw)
        );
    }

    /**
     * @return Action[]
     */
    public function getQueueContent()
    {
        $this->prepareQueueIfNull();
        return $this->queue->getRemainingActionList();
    }

    /**
     * @return bool
     */
    public function isFinished()
    {
        $this->prepareQueueIfNull();
        return $this->queue->isEmpty();
    }

    public function clearQueue()
    {
        $this->queue->clear();
    }

    public function enableQueue()
    {
        $this->useQueue = true;
    }

    public function disableQueue()
    {
        $this->useQueue = false;
    }

    /**
     * @return bool
     */
    public function isQueueEnabled()
    {
        return $this->useQueue;
    }

    /**
     * Should be called by the class using this trait
     * But should not be overwritten
     *
     * @param string $method
     * @param array  $argumentList
     *
     * @return mixed
     * @throws UnexpectedActionException
     */
    final protected function handleAction($method, array $argumentList = [])
    {
        if ( ! $this->useQueue) {
            return null;
        }

        $this->prepareQueueIfNull();
        if ($this->queue->isExpecting($method, $argumentList)) {
            return $this->queue->fulfill();
        }

        throw new UnexpectedActionException(
            $method,
            $argumentList,
            $this->queue->getExpectedAction()
        );
    }

    private function prepareQueueIfNull()
    {
        if ( ! $this->queue) {
            $this->queue = new EnqueuedActionCollection();
        }
    }
}
