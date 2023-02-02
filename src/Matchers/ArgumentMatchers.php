<?php declare(strict_types=1);

namespace Pbweb\Mimic\Matchers;

class ArgumentMatchers
{
    /**
     * Matches any argument received.
     */
    public static function any(): ArgumentMatcherInterface
    {
        return new AnyArgumentMatcher();
    }

    /**
     * Matches if the expected contents are equal to (a subset of) the received array contents.
     *
     * If associative is set to TRUE key => value pairs will have to match as a whole, otherwise only values will be
     * checked for.
     */
    public static function arrayContains(array $expectedContentList, bool $associative = false): ArgumentMatcherInterface
    {
        return new ArrayContainsMatcher($expectedContentList, $associative);
    }
}
