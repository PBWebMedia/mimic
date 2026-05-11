<?php declare(strict_types=1);

namespace Pbweb\Mimic\Matchers;

class ArrayContainsMatcher implements ArgumentMatcherInterface
{
    /** @var array<mixed> */
    private array $expectedContentList;
    private bool $associative;

    /**
     * @param array<mixed> $expectedContentList
     */
    public function __construct(array $expectedContentList, bool $associative) {
        $this->expectedContentList = $expectedContentList;
        $this->associative = $associative;
    }

    public function isMatching(mixed $argument): bool
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

    /**
     * @param array<mixed> $argument
     */
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

    /**
     * @param array<mixed> $argument
     */
    private function matchNonAssociative(array $argument): bool {
        foreach ($this->expectedContentList as $expectedValue) {
            if ( ! in_array($expectedValue, $argument)) {
                return false;
            }
        }

        return true;
    }
}
