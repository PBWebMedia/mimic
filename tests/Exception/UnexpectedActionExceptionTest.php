<?php

namespace Pbweb\Mimic\Exception;

use Pbweb\Mimic\Model\Action;
use PHPUnit\Framework\TestCase;

/**
 * @copyright 2015 PB Web Media B.V.
 */
class UnexpectedActionExceptionTest extends TestCase
{
    public function test()
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
        $action = $this->createMockAction();
        $action->expects($this->once())
            ->method('getMethod')
            ->willReturn($actionMethod);
        $action->expects($this->once())
            ->method('getArgumentList')
            ->willReturn($actionArgumentList);

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

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Action
     */
    private function createMockAction()
    {
        return $this->getMockBuilder(Action::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
