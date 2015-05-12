<?php

namespace Pbweb\Mimic\Tests\Service;

use Pbweb\Mimic\Service\MimicActionHandler;

/**
 * Class SampleMimic
 *
 * @copyright 2015 PB Web Media B.V.
 */
final class SampleMimic extends MimicActionHandler
{
    /**
     * @param int $id
     *
     * @return string
     * @throws \Exception
     */
    public function get($id)
    {
        return $this->handleAction(__FUNCTION__, func_get_args());
    }

    /**
     * @param int    $id
     * @param string $value
     *
     * @return bool
     * @throws \Exception
     */
    public function update($id, $value)
    {
        return $this->handleAction(__FUNCTION__, func_get_args());
    }
}