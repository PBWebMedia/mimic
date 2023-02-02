<?php declare(strict_types=1);

namespace Pbweb\Mimic\Model;

class Action
{
    private string $method;
    private array $argumentList;
    private mixed $response;
    private bool $throw;

    public function __construct(string $method, array $argumentList = [], mixed $response = null, bool $throw = false)
    {
        $this->method = $method;
        $this->argumentList = $argumentList;
        $this->response = $response;
        $this->throw = $throw;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return mixed[]
     */
    public function getArgumentList(): array
    {
        return $this->argumentList;
    }

    public function getResponse(): mixed
    {
        return $this->response;
    }

    public function isThrow(): bool
    {
        return $this->throw;
    }
}
