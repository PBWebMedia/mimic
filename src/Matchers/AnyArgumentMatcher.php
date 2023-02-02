<?php declare(strict_types=1);

namespace Pbweb\Mimic\Matchers;

class AnyArgumentMatcher implements ArgumentMatcherInterface
{
    public function isMatching($argument): bool
    {
        return true;
    }
}
