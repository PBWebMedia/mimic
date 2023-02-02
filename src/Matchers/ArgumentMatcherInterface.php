<?php declare(strict_types=1);

namespace Pbweb\Mimic\Matchers;

interface ArgumentMatcherInterface
{
    public function isMatching(mixed $argument): bool;
}
