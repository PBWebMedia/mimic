<?php declare(strict_types=1);

namespace Pbweb\Mimic\Exception;

use Pbweb\Mimic\Model\Action;
use PHPUnit\Framework\TestCase;

class UnexpectedActionExceptionTest extends TestCase
{
    public function test(): void
    {
        $method = 'get';
        $argumentList = [
            'i::#1',
        ];

        $actionMethod = 'insert';
        $actionArgumentList = [
            'i::#1',
            'data-to-insert'
        ];
        $action = $this->createStub(Action::class);
        $action->method('getMethod')->willReturn($actionMethod);
        $action->method('getArgumentList')->willReturn($actionArgumentList);

        $exception = new UnexpectedActionException($method, $argumentList, $action);
        $this->assertEquals($method, $exception->getReceivedMethod());
        $this->assertEquals($argumentList, $exception->getReceivedArgumentList());
        $this->assertSame($action, $exception->getExpectedAction());

        $expectedMessage = 'Unexpected call to method "get" with the given argument list.' . PHP_EOL
            . 'Expected method: "insert" but got "get"' . PHP_EOL
            . 'Expected argument list (as json):' . PHP_EOL
            . '["i::#1","data-to-insert"]' . PHP_EOL
            . PHP_EOL
            . 'But got argument list (as json):' . PHP_EOL
            . '["i::#1"]' . PHP_EOL;
        $this->assertEquals($expectedMessage, $exception->getMessage());
    }

    public function testNoAction()
    {
        $method = 'flush';
        $exception = new UnexpectedActionException($method, []);

        $expectedMessage = 'Unexpected call to method "' . $method . '" with the given argument list.' . PHP_EOL
            . 'No more calls where expected.';
        $this->assertEquals($expectedMessage, $exception->getMessage());
    }
}
