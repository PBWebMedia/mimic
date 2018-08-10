<?php

namespace Pbweb\Mimic\Matchers;

use PHPUnit\Framework\TestCase;

/**
 * @copyright 2018 PB Web Media B.V.
 */
class ArrayContainsMatcherTest extends TestCase
{
    public function testNonArrays()
    {
        $matcher = ArgumentMatchers::arrayContains(['foo' => 'bar']);

        $this->assertFalse($matcher->isMatching(null));
        $this->assertFalse($matcher->isMatching(1));
        $this->assertFalse($matcher->isMatching('1'));
        $this->assertFalse($matcher->isMatching(false));
        $this->assertFalse($matcher->isMatching(true));
    }

    public function testAssociative()
    {
        $matcher = ArgumentMatchers::arrayContains(['foo' => 'bar'], true);

        $this->assertFalse($matcher->isMatching([]));
        $this->assertTrue($matcher->isMatching(['foo' => 'bar']));
        $this->assertTrue($matcher->isMatching(['foo' => 'bar', 'fiz' => 'buz']));
        $this->assertFalse($matcher->isMatching(['foo' => 'baz']));
        $this->assertFalse($matcher->isMatching(['fiz' => 'bar']));
    }

    public function testNonAssociative()
    {
        $matcher = ArgumentMatchers::arrayContains(['bar'], false);

        $this->assertFalse($matcher->isMatching([]));
        $this->assertTrue($matcher->isMatching(['foo' => 'bar']));
        $this->assertTrue($matcher->isMatching(['foo' => 'bar', 'fiz' => 'buz']));
        $this->assertFalse($matcher->isMatching(['foo' => 'baz']));
        $this->assertTrue($matcher->isMatching(['fiz' => 'bar']));
    }
}
