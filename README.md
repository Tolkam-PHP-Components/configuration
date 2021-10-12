# tolkam/configuration

Simple configuration container.

## Documentation

The code is rather self-explanatory and API is intended to be as simple as possible. Please, read the sources/Docblock if you have any questions. See [Usage](#usage) for quick start.

## Usage

````php
use Tolkam\Configuration\Configuration;
use Tolkam\Configuration\Provider\ArrayProvider;

$configuration = new Configuration(new ArrayProvider([
    'foo' => [
        'bar' => [
            'baz' => 'value!'
        ]
    ]
]));

echo $configuration->get('foo.bar.baz');
````

## License

Proprietary / Unlicensed 🤷
