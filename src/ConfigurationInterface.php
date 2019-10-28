<?php declare(strict_types=1);

namespace Tolkam\Configuration;

use IteratorAggregate;

interface ConfigurationInterface extends IteratorAggregate
{
    /**
     * Gets value by path
     *
     * @param string $path
     * @param null   $default
     * @param string $separator
     *
     * @return mixed
     */
    public function get(string $path, $default = null, string $separator = '.');
}
