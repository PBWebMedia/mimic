<?php declare(strict_types=1);

namespace Pbweb\Mimic\Matchers;

use PHPUnit\Framework\TestCase;

class AnyArgumentMatcherTest extends TestCase
{
    public function test(): void
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
