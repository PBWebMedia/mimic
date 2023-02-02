<?php declare(strict_types=1);

namespace Pbweb\Mimic\Matchers;

use PHPUnit\Framework\TestCase;

class ArrayContainsMatcherTest extends TestCase
{
    public function testNonArrays(): void
    {
        $matcher = ArgumentMatchers::arrayContains(['foo' => 'bar']);

        $this->assertFalse($matcher->isMatching(null));
        $this->assertFalse($matcher->isMatching(1));
        $this->assertFalse($matcher->isMatching('1'));
        $this->assertFalse($matcher->isMatching(false));
        $this->assertFalse($matcher->isMatching(true));
    }

    public function testAssociative(): void
    {
        $matcher = ArgumentMatchers::arrayContains(['foo' => 'bar'], true);

        $this->assertFalse($matcher->isMatching([]));
        $this->assertTrue($matcher->isMatching(['foo' => 'bar']));
        $this->assertTrue($matcher->isMatching(['foo' => 'bar', 'fiz' => 'buz']));
        $this->assertFalse($matcher->isMatching(['foo' => 'baz']));
        $this->assertFalse($matcher->isMatching(['fiz' => 'bar']));
    }

    public function testNonAssociative(): void
    {
        $matcher = ArgumentMatchers::arrayContains(['bar'], false);

        $this->assertFalse($matcher->isMatching([]));
        $this->assertTrue($matcher->isMatching(['foo' => 'bar']));
        $this->assertTrue($matcher->isMatching(['foo' => 'bar', 'fiz' => 'buz']));
        $this->assertFalse($matcher->isMatching(['foo' => 'baz']));
        $this->assertTrue($matcher->isMatching(['fiz' => 'bar']));
    }
}
