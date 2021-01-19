<?php declare(strict_types=1);

namespace Tolkam\Configuration\Provider;

use Generator;
use RuntimeException;

class AggregateProvider
{
    /**
     * @var callable[]
     */
    protected array $providers = [];
    
    /**
     * @param callable ...$providers
     */
    public function __construct(callable ...$providers)
    {
        $this->providers = $providers;
    }
    
    public function __invoke(): array
    {
        return $this->mergeFromCallables($this->providers);
    }
    
    /**
     * Merges arrays coming from callables
     *
     * @param array $callables
     *
     * @return array
     */
    protected function mergeFromCallables(array $callables): array
    {
        $merged = [];
        
        foreach ($callables as $callable) {
            $arr = $callable();
            if ($arr instanceof Generator) {
                foreach ($arr as $a) {
                    $this->assertArray($a, $callable);
                    $this->merge($merged, $a);
                }
            }
            else {
                $this->assertArray($arr, $callable);
                $this->merge($merged, $arr);
            }
        }
        
        return $merged;
    }
    
    /**
     * Merges arrays recursively, treats duplicate keys
     * same as array_merge(). Modifies target array
     *
     * @see https://www.php.net/manual/en/function.array-merge-recursive.php#92195
     *
     * @param array $target
     * @param array $source
     *
     * @return void
     */
    private function merge(array &$target, array &$source)
    {
        foreach ($source as $key => &$value) {
            if (is_array($value) && isset($target[$key]) && is_array($target[$key])) {
                $this->merge($target[$key], $value);
            }
            else {
                $target[$key] = $value;
            }
        }
    }
    
    /**
     * Checks if source value is array
     *
     * @param          $value
     * @param callable $source
     */
    private function assertArray($value, callable $source)
    {
        if (!is_array($value)) {
            throw new RuntimeException(sprintf(
                'Configuration provider "%s" must return an array, %s returned',
                is_object($source) ? addslashes(get_class($source)) : gettype($source),
                gettype($value),
            ));
        }
    }
}
