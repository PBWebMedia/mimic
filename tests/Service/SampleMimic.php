<?php declare(strict_types=1);

namespace Pbweb\Mimic\Service;

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
