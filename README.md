# nasumilu/geometry [![Build Status](https://app.travis-ci.com/nasumilu/geometry.svg?branch=main)](https://app.travis-ci.com/nasumilu/geometry) [![codecov](https://codecov.io/gh/nasumilu/geometry/branch/main/graph/badge.svg?token=FEV4KWKKQJ)](https://codecov.io/gh/nasumilu/geometry)

## Description
`nasumilu\geometry` **mostly** provides an implementation of the [OpenGIS&reg; standards for geographic information, common architecture](https://www.ogc.org/standards/sfa) for PHP. 

This component **only** provides a common framework used to develop platform specific implementation.

## Basic usage

```php

$options = [
    '3d' => true,
    'measured' => true,
    'srid' => 3857
];

$factory = new GeometryFactoryAdapter($options);

$point = $factory->createPoint([
    -93957413244860,
    -89692863500304,
    58.464,
    9863321.35
]);

echo $point->asText();
echo $point->asBinary(['hex_str' => true, 'endianness' => 'XDR']);

```
Output
```php
POINTZM(-93957413244860 -89692863500304 58.464 9863321.35)
0000000bb9c2d55d0a8f63ef00c2d464cfd1240400404d3b645a1cac084162d0132b333333
```

Creating geometry from WKT
```php
$point = $factory->create('POINTZM(-93957413244860 -89692863500304 58.464 9863321.35)');

echo $point->output('json', ['json_encode_options' => ]);

```
Output 
```json
{
    "type": "point",
    "binary_type": 1,
    "crs": {
        "srid": 3857,
        "3d": true,
        "measured": true,
        "dimension": 4
    },
    "coordinates": [
        -93957413244860,
        -89692863500304,
        58.464,
        9863321.35
    ]
}
```

## Download and Test

```bash
$ git clone git@github.com:nasumilu/geometry.git
$ cd geometry
$ composer update
$ cp phpunit.dist.xml phpunit.xml
$ vendor/bin/phpunit
```