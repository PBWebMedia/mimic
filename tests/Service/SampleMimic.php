<?php declare(strict_types=1);

namespace Pbweb\Mimic\Service;

/**
 * @copyright 2015 PB Web Media B.V.
 */
final class SampleMimic extends MimicActionHandler
{
    /**
     * @throws \Throwable
     */
    public function get(int $id): mixed
    {
        return $this->handleAction(__FUNCTION__, func_get_args());
    }

    /**
     * @throws \Throwable
     */
    public function update(int $id, string $value): mixed
    {
        return $this->handleAction(__FUNCTION__, func_get_args());
    }
}
