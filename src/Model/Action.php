<?php

namespace Pbweb\Mimic\Model;

/**
 * Class Action
 *
 * @copyright 2015 PB Web Media B.V.
 */
class Action
{
    /** @var string */
    private $method;

    /** @var array */
    private $argumentList;

    /** @var mixed[] */
    private $response;

    /** @var bool */
    private $throw;

    /**
     * @param string $method
     * @param array  $argumentList
     * @param mixed  $response
     * @param bool   $throw
     */
    public function __construct($method, array $argumentList = [], $response = null, $throw = false)
    {
        $this->method = $method;
        $this->argumentList = $argumentList;
        $this->response = $response;
        $this->throw = $throw;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return mixed[]
     */
    public function getArgumentList()
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

    /**
     * @return boolean
     */
    public function isThrow()
    {
        return $this->throw;
    }
}
