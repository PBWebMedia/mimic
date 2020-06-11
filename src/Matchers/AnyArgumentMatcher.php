<?php declare(strict_types=1);

namespace Pbweb\Mimic\Matchers;

/**
 * @copyright 2018 PB Web Media B.V.
 */
class AnyArgumentMatcher implements ArgumentMatcherInterface
{
    public function isMatching($argument): bool
    {
        return true;
    }
}
