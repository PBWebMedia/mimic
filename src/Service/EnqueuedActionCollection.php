<?php

namespace Pbweb\Mimic\Service;

use Pbweb\Mimic\Matchers\ArgumentMatcherInterface;
use Pbweb\Mimic\Model\Action;
use SplQueue;

/**
 * Class EnqueuedActionCollection
 *
 * @copyright 2015 PB Web Media B.V.
 */
class EnqueuedActionCollection
{
    /** @var SplQueue */
    private $queue;

    public function __construct()
    {
        $this->clear();
    }

    public function add(Action $action): void
    {
        $this->queue->enqueue($action);
    }

    public function isExpecting(string $method, array $argumentList = []): bool
    {
        $this->queue->rewind();

        /** @var Action $expectingAction */
        $expectingAction = $this->queue->current();
        if ( ! $expectingAction) {
            return false;
        }

        if ($expectingAction->getMethod() != $method) {
            return false;
        }

        if ( ! $this->isArgumentListMatching($expectingAction->getArgumentList(), $argumentList)) {
            return false;
        }

        return true;
    }

    private function isArgumentListMatching(array $expectedArgumentList, array $actualArgumentList): bool
    {
        if (sizeof($expectedArgumentList) != sizeof($actualArgumentList)) {
            return false;
        }

        foreach ($expectedArgumentList as $index => $expectedArgument) {
            $actualArgument = $actualArgumentList[$index];
            if ($expectedArgument instanceof ArgumentMatcherInterface) {
                $argumentMatches = $expectedArgument->isMatching($actualArgument);
            } else {
                $argumentMatches = $expectedArgument == $actualArgument;
            }

            if (! $argumentMatches) {
                return false;
            }
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

    public function getExpectedAction(): ?Action
    {
        return $this->queue->current();
    }

    public function isEmpty(): bool
    {
        return $this->queue->isEmpty();
    }

    /**
     * @return Action[]
     */
    public function getRemainingActionList(): array
    {
        $actionList = [];
        foreach ($this->queue as $action) {
            $actionList[] = $action;
        }
        $this->queue->rewind();

        return $actionList;
    }

    public function clear(): void
    {
        $this->queue = new SplQueue();
    }
}
