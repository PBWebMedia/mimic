<?php declare(strict_types=1);

namespace Pbweb\Mimic\Model;

/**
 * @copyright 2015 PB Web Media B.V.
 */
class Action
{
    private string $method;
    private array $argumentList;
    private $response;
    private bool $throw;

    public function __construct(string $method, array $argumentList = [], $response = null, bool $throw = false)
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

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    public function isThrow(): bool
    {
        return $this->throw;
    }
}
