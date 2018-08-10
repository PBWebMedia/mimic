<?php

namespace Pbweb\Mimic\Matchers;

/**
 * @copyright 2018 PB Web Media B.V.
 */
class ArgumentMatchers
{
    /**
     * Matches any argument received.
     *
     * @return ArgumentMatcherInterface
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
     *
     * @param array $expectedContentList
     * @param bool $associative
     * @return ArgumentMatcherInterface
     */
    public static function arrayContains(array $expectedContentList, bool $associative = false): ArgumentMatcherInterface
    {
        return new ArrayContainsMatcher($expectedContentList, $associative);
    }
}
