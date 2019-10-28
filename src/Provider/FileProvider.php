<?php declare(strict_types=1);

namespace Tolkam\Configuration\Provider;

use Generator;

/**
 * Reads arrays from files
 *
 * @package Tolkam\Configuration\Aggregator
 */
class FileProvider
{
    /**
     * @var string
     */
    private string $globPattern;
    
    /**
     * @var int
     */
    private int $globFlags;
    
    /**
     * @param string $globPattern
     * @param int    $globFlags
     */
    public function __construct(string $globPattern, int $globFlags = GLOB_BRACE | GLOB_NOSORT)
    {
        $this->globPattern = $globPattern;
        $this->globFlags = $globFlags;
    }
    
    /**
     * @return Generator
     */
    public function __invoke(): Generator
    {
        foreach (glob($this->globPattern, $this->globFlags) as $item) {
            yield include $item;
        }
    }
}
