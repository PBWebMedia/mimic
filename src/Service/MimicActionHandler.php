<?php declare(strict_types=1);

namespace Pbweb\Mimic\Service;

use Pbweb\Mimic\Exception\UnexpectedActionException;
use Pbweb\Mimic\Model\Action;
use Throwable;

/**
 * @copyright 2015 PB Web Media B.V.
 */
abstract class MimicActionHandler
{
    private EnqueuedActionCollection $queue;

    /**
     * Do we make use of the queue or ignore the calls?
     */
    private bool $useQueue = false;

    public function __construct()
    {
        $this->queue = new EnqueuedActionCollection();
    }

    final public function enqueue(string $method, array $argumentList = [], mixed $response = null, bool $throw = false): void
    {
        $this->enableQueue();

        $this->queue->add(
            new Action($method, $argumentList, $response, $throw)
        );
    }

    /**
     * @return Action[]
     */
    final public function getQueueContent(): array
    {
        return $this->queue->getRemainingActionList();
    }

    final public function isFinished(): bool
    {
        return $this->queue->isEmpty();
    }

    final public function clearQueue(): void
    {
        $this->queue->clear();
    }

    final public function enableQueue(): void
    {
        $this->useQueue = true;
    }

    final public function disableQueue(): void
    {
        $this->useQueue = false;
    }

    final public function isQueueEnabled(): bool
    {
        return $this->useQueue;
    }

    /**
     * Should be called by the class using this mimic
     * But should not be overwritten
     *
     * @throws UnexpectedActionException|Throwable
     */
    final protected function handleAction(string $method, array $argumentList = [], mixed $defaultResponse = null): mixed
    {
        if ( ! $this->useQueue) {
            return $defaultResponse;
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
