<?php declare(strict_types=1);

namespace Pbweb\Mimic\Exception;

use Pbweb\Mimic\Model\Action;

class UnexpectedActionException extends MimicException
{
    private string $receivedMethod;
    private array $receivedArgumentList;
    private ?Action $expectedAction;

    public function __construct(string $receivedMethod, array $receivedArgumentList, Action $expectedAction = null)
    {
        parent::__construct();

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

    private function prepareMessage(): void
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
