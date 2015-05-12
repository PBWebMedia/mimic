<?php

namespace Pbweb\Mimic\Service;

use Pbweb\Mimic\Exception\UnexpectedActionException;
use Pbweb\Mimic\Model\Action;

/**
 * Class MimicActionHandler
 *
 * @copyright 2015 PB Web Media B.V.
 */
abstract class MimicActionHandler
{
    /** @var EnqueuedActionCollection */
    private $queue;

    /**
     * Do we make use of the queue or ignore the calls?
     *
     * @var bool
     */
    private $useQueue = false;

    public function __construct()
    {
        $this->queue = new EnqueuedActionCollection();
    }

    /**
     * @param string $method
     * @param array  $argumentList
     * @param mixed  $response
     * @param bool   $throw
     */
    final public function enqueue($method, array $argumentList = [], $response = null, $throw = false)
    {
        $this->enableQueue();

        $this->queue->add(
            new Action($method, $argumentList, $response, $throw)
        );
    }

    /**
     * @return Action[]
     */
    final public function getQueueContent()
    {
        return $this->queue->getRemainingActionList();
    }

    /**
     * @return bool
     */
    final public function isFinished()
    {
        return $this->queue->isEmpty();
    }

    final public function clearQueue()
    {
        $this->queue->clear();
    }

    final public function enableQueue()
    {
        $this->useQueue = true;
    }

    final public function disableQueue()
    {
        $this->useQueue = false;
    }

    /**
     * @return bool
     */
    final public function isQueueEnabled()
    {
        return $this->useQueue;
    }

    /**
     * Should be called by the class using this mimic
     * But should not be overwritten
     *
     * @param string  $method
     * @param mixed[] $argumentList
     *
     * @return mixed
     * @throws UnexpectedActionException|mixed
     */
    final protected function handleAction($method, array $argumentList = [])
    {
        if ( ! $this->useQueue) {
            return null;
        }

        if ($this->queue->isExpecting($method, $argumentList)) {
            return $this->queue->fulfill();
        }

        throw new UnexpectedActionException(
            $method,
            $argumentList,
            $this->queue->getExpectedAction()
        );
    }
}
