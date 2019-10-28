<?php declare(strict_types=1);

namespace Tolkam\Configuration;

use ArrayIterator;

class Configuration implements ConfigurationInterface
{
    /**
     * @var array
     */
    private array $data = [];
    
    /**
     * @var array
     */
    private array $options = [
        // throw on invalid paths
        'strict' => true,
    ];
    
    /**
     * @param ConfigurationsAggregatorInterface $aggregator
     * @param array                             $options
     */
    public function __construct(
        ConfigurationsAggregatorInterface $aggregator,
        array $options = []
    ) {
        $this->data = $aggregator->aggregate();
        $this->options = array_merge($this->options, $options);
    }
    
    /**
     * @inheritDoc
     */
    public function get(string $path, $default = null, string $separator = '.')
    {
        $found = $this->data;
        $segments = explode($separator, $path);
        
        while ($segment = array_shift($segments)) {
            if (array_key_exists($segment, $found)) {
                $found = $found[$segment];
            } else {
                if (!!$this->options['strict']) {
                    throw new ConfigurationException(sprintf(
                        'Unknown configuration path "%s"',
                        $path
                    ));
                } else {
                    return $default;
                }
            }
        }
        
        return $found;
    }
    
    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }
}
