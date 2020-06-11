<?php declare(strict_types=1);

namespace Pbweb\Mimic\Matchers;

/**
 * @copyright 2018 PB Web Media B.V.
 */
class ArrayContainsMatcher implements ArgumentMatcherInterface
{
    private array $expectedContentList;
    private bool $associative;

    public function __construct(array $expectedContentList, bool $associative) {
        $this->expectedContentList = $expectedContentList;
        $this->associative = $associative;
    }

    public function isMatching($argument): bool
    {
        if ( ! is_array($argument)) {
            return false;
        }

        if ($this->associative) {
            return $this->matchAssociative($argument);
        } else {
            return $this->matchNonAssociative($argument);
        }
    }

    private function matchAssociative(array $argument): bool {
        foreach ($this->expectedContentList as $expectedKey => $expectedValue) {
            if ( ! isset($argument[$expectedKey])) {
                return false;
            }

            if ($argument[$expectedKey] != $expectedValue) {
                return false;
            }
        }

        return true;
    }

    private function matchNonAssociative(array $argument): bool {
        foreach ($this->expectedContentList as $expectedValue) {
            if ( ! in_array($expectedValue, $argument)) {
                return false;
            }
        }

        return true;
    }
}
