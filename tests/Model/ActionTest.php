<?php declare(strict_types=1);

namespace Pbweb\Mimic\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ActionTest extends TestCase
{
    #[DataProvider('getData')]
    public function test(string $method, array $argumentList, mixed $response, bool $isThrow = false): void
    {
        $action = new Action($method, $argumentList, $response, $isThrow);

        $this->assertEquals($method, $action->getMethod());
        $this->assertEquals($argumentList, $action->getArgumentList());
        $this->assertEquals($response, $action->getResponse());
        $this->assertEquals($isThrow, $action->isThrow());
    }

    public static function getData(): array
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
