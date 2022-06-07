<?php declare(strict_types=1);

namespace Tolkam\Configuration;

use ArrayIterator;
use Closure;
use Tolkam\Utils\Arr;
use Traversable;

class Configuration implements ConfigurationInterface
{
    /**
     * @var array
     */
    private array $data;

    /**
     * @var array
     */
    private array $options = [
        // throw on invalid paths
        'strict' => true,

        // read-only mode
        'readOnly' => true,

        // resolve, if found value is instance of Closure
        'resolveClosures' => true,

        'pathSeparator' => '.',
    ];

    /**
     * @param callable $provider
     * @param array    $options
     */
    public function __construct(
        callable $provider,
        array $options = []
    ) {
        $data = $provider();

        if (!is_array($data)) {
            throw new ConfigurationException(sprintf(
                'Data provider must return array, %s returned',
                is_object($provider) ? addslashes(get_class($provider)) : gettype($provider),
            ));
        }

        $this->options = array_replace($this->options, $options);
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function get(string $path = null, $default = null)
    {
        $found = $this->data;
        $separator = (string) $this->options['pathSeparator'];

        if ($path === null || $path === '') {
            return $found;
        }

        $segments = explode($separator, $path);

        while ($segment = array_shift($segments)) {
            if (isset($found[$segment]) || array_key_exists($segment, $found)) {
                $found = $found[$segment];

                if (!!$this->options['resolveClosures'] && $found instanceof Closure) {
                    $found = $found();
                }
            }
            elseif (!!$this->options['strict']) {
                throw new ConfigurationException(sprintf(
                    'Unknown configuration path "%s"',
                    $path
                ));
            }
            else {
                return $default;
            }
        }

        return $found;
    }

    /**
     * @inheritDoc
     */
    public function set(string $path, $value): self
    {
        if (!!$this->options['readOnly']) {
            throw new ConfigurationException(
                'Configuration is read-only. No modifications possible'
            );
        }
        Arr::set(
            $this->data,
            $path,
            $value,
            (string) $this->options['pathSeparator']
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
    }
}
