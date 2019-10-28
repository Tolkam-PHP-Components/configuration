<?php declare(strict_types=1);

namespace Tolkam\Configuration\Provider;

/**
 * Reads from array literal
 *
 * @package Tolkam\Configuration\Aggregator
 */
class ArrayProvider
{
    /**
     * @var array
     */
    private array $arr;
    
    /**
     * @param array $arr
     */
    public function __construct(array $arr)
    {
        $this->arr = $arr;
    }
    
    /**
     * @return array
     */
    public function __invoke(): array
    {
        return $this->arr;
    }
}
