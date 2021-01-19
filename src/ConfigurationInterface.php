<?php declare(strict_types=1);

namespace Tolkam\Configuration;

use IteratorAggregate;

interface ConfigurationInterface extends IteratorAggregate
{
    /**
     * Sets value by path
     *
     * @param string $path
     * @param        $value
     *
     * @return $this
     */
    public function set(string $path, $value): self;
    
    /**
     * Gets value by path
     *
     * @param string $path
     * @param null   $default
     *
     * @return mixed
     */
    public function get(string $path, $default = null);
}
