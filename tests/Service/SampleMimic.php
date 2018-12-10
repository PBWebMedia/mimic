<?php

namespace Pbweb\Mimic\Service;

/**
 * @copyright 2015 PB Web Media B.V.
 */
final class SampleMimic extends MimicActionHandler
{
    /**
     * @throws \Exception
     */
    public function get(int $id)
    {
        return $this->handleAction(__FUNCTION__, func_get_args());
    }

    /**
     * @throws \Exception
     */
    public function update(int $id, string $value)
    {
        return $this->handleAction(__FUNCTION__, func_get_args());
    }
}
