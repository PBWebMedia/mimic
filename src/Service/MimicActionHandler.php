<?php declare(strict_types=1);

namespace Pbweb\Mimic\Service;

use Pbweb\Mimic\Exception\UnexpectedActionException;
use Pbweb\Mimic\Model\Action;

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

    final public function enqueue(string $method, array $argumentList = [], $response = null, bool $throw = false)
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
     * @param string  $method
     * @param mixed[] $argumentList
     * @param mixed   $defaultResponse
     *
     * @return mixed
     * @throws UnexpectedActionException|mixed
     */
    final protected function handleAction(string $method, array $argumentList = [], $defaultResponse = null)
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
