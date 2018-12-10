<?php

namespace Pbweb\Mimic\Exception;

use Pbweb\Mimic\Model\Action;

/**
 * @copyright 2015 PB Web Media B.V.
 */
class UnexpectedActionException extends MimicException
{
    /** @var string */
    private $receivedMethod;
    /** @var array */
    private $receivedArgumentList;
    /** @var Action|null */
    private $expectedAction;

    public function __construct(string $receivedMethod, array $receivedArgumentList, Action $expectedAction = null)
    {
        $this->receivedMethod = $receivedMethod;
        $this->receivedArgumentList = $receivedArgumentList;
        $this->expectedAction = $expectedAction;

        $this->prepareMessage();
    }

    public function getReceivedMethod(): string
    {
        return $this->receivedMethod;
    }

    public function getReceivedArgumentList(): array
    {
        return $this->receivedArgumentList;
    }

    public function getExpectedAction(): ?Action
    {
        return $this->expectedAction;
    }

    private function prepareMessage()
    {
        $this->message = sprintf(
            'Unexpected call to method "%s" with the given argument list.' . PHP_EOL . '%s',
            $this->receivedMethod,
            $this->formatDetailedMessage()
        );
    }

    private function formatDetailedMessage(): string
    {
        if ($this->expectedAction === null) {
            return 'No more calls where expected.';
        }

        $format = 'Expected method: "%s" but got "%s"' . PHP_EOL
            . 'Expected argument list (as json):' . PHP_EOL
            . '%s' . PHP_EOL
            . PHP_EOL
            . 'But got argument list (as json):' . PHP_EOL
            . '%s' . PHP_EOL;

        return sprintf(
            $format,
            $this->expectedAction->getMethod(),
            $this->receivedMethod,
            json_encode($this->expectedAction->getArgumentList()),
            json_encode($this->receivedArgumentList)
        );
    }
}
