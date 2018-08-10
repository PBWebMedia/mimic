<?php

namespace Pbweb\Mimic\Matchers;

use PHPUnit\Framework\TestCase;

/**
 * @copyright 2018 PB Web Media B.V.
 */
class AnyArgumentMatcherTest extends TestCase
{
    public function test()
    {
        $matcher = ArgumentMatchers::any();

        $this->assertTrue($matcher->isMatching(null));
        $this->assertTrue($matcher->isMatching(1));
        $this->assertTrue($matcher->isMatching('1'));
        $this->assertTrue($matcher->isMatching([]));
        $this->assertTrue($matcher->isMatching(false));
        $this->assertTrue($matcher->isMatching(true));
        $this->assertTrue($matcher->isMatching(['a' => ['nested' => ['array' => []]]]));
    }
}
