<?php declare(strict_types=1);

namespace Pbweb\Mimic\Model;

use PHPUnit\Framework\TestCase;

/**
 * @copyright 2015 PB Web Media B.V.
 */
class ActionTest extends TestCase
{
    /**
     * @dataProvider getData
     */
    public function test(string $method, array $argumentList, $response, bool $isThrow = false)
    {
        $action = new Action($method, $argumentList, $response, $isThrow);

        $this->assertEquals($method, $action->getMethod());
        $this->assertEquals($argumentList, $action->getArgumentList());
        $this->assertEquals($response, $action->getResponse());
        $this->assertEquals($isThrow, $action->isThrow());
    }

    public function getData(): array
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
