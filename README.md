# nasumilu/geometry [![Build Status](https://app.travis-ci.com/nasumilu/geometry.svg?branch=main)](https://app.travis-ci.com/nasumilu/geometry) [![codecov](https://codecov.io/gh/nasumilu/geometry/branch/main/graph/badge.svg?token=FEV4KWKKQJ)](https://codecov.io/gh/nasumilu/geometry)

## Description
`nasumilu\geometry` **mostly** provides an implementation of the [OpenGIS&reg; standards for geographic information, common architecture](https://www.ogc.org/standards/sfa) for PHP. 

This component **only** provides a common framework used to develop platform specific implementation.

## Download and Test

```bash
$ git clone git@github.com:nasumilu/geometry.git
$ cd geometry
$ composer update
$ cp phpunit.dist.xml phpunit.xml
$ vendor/bin/phpunit
```