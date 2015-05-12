<?php

namespace Pbweb\Mimic\Service;

use Pbweb\Mimic\Model\Action;

/**
 * Class EnqueuedActionCollection
 *
 * @copyright 2015 PB Web Media B.V.
 */
class EnqueuedActionCollection
{
    /** @var \SplQueue */
    private $queue;

    public function __construct()
    {
        $this->clear();
    }

    /**
     * @param Action $action
     */
    public function add(Action $action)
    {
        $this->queue->enqueue($action);
    }

    /**
     * @param string $method
     * @param array  $argumentList
     *
     * @return bool
     */
    public function isExpecting($method, array $argumentList = [])
    {
        $this->queue->rewind();
        $expectingAction = $this->queue->current();
        if ( ! $expectingAction) {
            return false;
        }

        if ($expectingAction->getMethod() != $method || $expectingAction->getArgumentList() != $argumentList) {
            return false;
        }

        return true;
    }

    /**
     * @return mixed
     * @throws mixed
     */
    public function fulfill()
    {
        /** @var Action $action */
        $action = $this->queue->dequeue();
        $response = $action->getResponse();

        if ($action->isThrow()) {
            throw $response;
        }

        return $response;
    }

    /**
     * @return Action|null
     */
    public function getExpectedAction()
    {
        return $this->queue->current();
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->queue->isEmpty();
    }

    /**
     * @return Action[]
     */
    public function getRemainingActionList()
    {
        $actionList = [];
        foreach ($this->queue as $action) {
            $actionList[] = $action;
        }
        $this->queue->rewind();

        return $actionList;
    }

    public function clear()
    {
        $this->queue = new \SplQueue();
    }
}
