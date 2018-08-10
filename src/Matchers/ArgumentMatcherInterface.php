<?php

namespace Pbweb\Mimic\Matchers;

/**
 * @copyright 2018 PB Web Media B.V.
 */
interface ArgumentMatcherInterface {

    /**
     * @param mixed $argument
     * @return bool
     */
    public function isMatching($argument): bool;

}
