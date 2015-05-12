<?php

namespace Pbweb\Mimic\Tests\Model;

use Pbweb\Mimic\Model\Action;

/**
 * Class BucketActionTest
 *
 * @copyright 2015 PB Web Media B.V.
 */
class BucketActionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $method
     * @param array  $argumentList
     * @param mixed  $response
     * @param bool   $isThrow
     *
     * @dataProvider getData
     */
    public function test($method, array $argumentList, $response, $isThrow = false)
    {
        $action = new Action($method, $argumentList, $response, $isThrow);

        $this->assertEquals($method, $action->getMethod());
        $this->assertEquals($argumentList, $action->getArgumentList());
        $this->assertEquals($response, $action->getResponse());
        $this->assertEquals($isThrow, $action->isThrow());
    }

    /**
     * @return array[]
     */
    public function getData()
    {
        return [
            [
                'get',
                [1],
                'data-for-id-1',
                false
            ],
            [
                'put',
                ['some-key', 'some-data', 'does-not-really-matter'],
                new \Exception(),
                true
            ],
        ];
    }
}
