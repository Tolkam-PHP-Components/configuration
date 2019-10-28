<?php declare(strict_types=1);

namespace Tolkam\Configuration;

interface ConfigurationsAggregatorInterface
{
    /**
     * Gets aggregated array
     *
     * @return array
     */
    public function aggregate();
}
