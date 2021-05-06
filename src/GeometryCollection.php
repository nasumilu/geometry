<?php

declare(strict_types=1);

/*
 * Copyright 2021 mlucas.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Nasumilu\Spatial\Geometry;

use Iterator;
use Countable;
use ArrayAccess;
use OutOfRangeException;
use InvalidArgumentException;
use function Nasumilu\Spatial\Geometry\static_cast_int;
use function current;
use function next;
use function key;
use function reset;
use function count;
use function array_values;

/**
 * GeometryCollection is a collection of zero or more geometry objects.
 */
class GeometryCollection extends Geometry implements ArrayAccess, Iterator, Countable
{

    /** The well-known text type value */
    public const WKT_TYPE = 'geometrycollection';

    /** The well-known binary type value */
    public const WKB_TYPE = 7;

    /**
     * @var Geometry[]
     */
    protected $geometries;

    /**
     * Constructs a GeometryCollection with the GeometryFactory and set of Geometry
     * objects
     * 
     * Please use {@see GeometryFacotry::create} or {@see GeometryFactory::createLineString}
     * to construct a GeometryCollection. If using this construct directly ensure that the 
     * <code>Geometry</code>s found in the set where constructed with the same 
     * <code>GeometryFactory</code>.
     * 
     * @param GeometryFactory $factory
     * @param Geometry ...$geometries
     */
    public function __construct(GeometryFactory $factory, Geometry ...$geometries)
    {
        parent::__construct($factory);
        $this->geometries = $geometries;
    }

    /**
     * {@inheritDoc}
     */
    public function getGeometryType(): string
    {
        return self::WKT_TYPE;
    }

    /**
     * {@inheritDoc}
     */
    public function getDimension(): int
    {
        $dimension = 0;
        foreach ($this->geometries as $geometry) {
            $dimension = max($dimension, $geometry->getDimension());
        }
    }
    
    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * @internal Countable::count implementation
     * {@inheritDoc}
     */
    public function count(): int
    {
        return count($this->geometries);
    }

    /**
     * @internal Iterator::current implementation
     * {@inheritDoc}
     */
    public function current(): Geometry
    {
        return current($this->geometries);
    }

    /**
     * @internal Iterator::key implementation
     * {@inheritDoc}
     */
    public function key(): int
    {
        return key($this->geometries);
    }

    /**
     * @internal Iterator::next implementation
     * {@inheritDoc}
     */
    public function next(): void
    {
        next($this->geometries);
    }

    /**
     * Indicates whether a Geometry exists a <code>$offset</code>
     * @param int $offset
     * @return bool
     */
    public function hasGeometry(int $offset): bool
    {
        return isset($this->geometries[$offset]);
    }

    /**
     * Gets the Geometry found at <code>$offset</code>
     * @param int $offset
     * @return Geometry
     * @throws OutOfRangeException
     */
    public function getGeometryN(int $offset): Geometry
    {
        if (!isset($this->geometries)) {
            throw new OutOfRangeException("No geometry at offset $offset found!");
        }
        return $this->geometries[$offset];
    }
    
    protected function createAllowedGeometry($geometry) : Geometry 
    {
        return $this->factory->create($geometry);
    }

    /**
     * Sets a Geometry at <code>$offset</code> or push it to the end of the set
     * when not provided.
     * @param type $geometry
     * @param int|null $offset
     * @return GeometryCollection
     * @throws InvalidArgumentException argument is not a correct type in homogeneous
     * collections
     */
    public function setGeometryN($geometry, ?int $offset = null): GeometryCollection
    {
        $this->geometries[$offset ?? $this->count()] = $this->createAllowedGeometry($geometry);
        return $this;
    }

    /**
     * Removes the Geometry found at <code>$offset</code>
     * @param int $offset
     * @return Geometry
     * @throws OutOfRangeException
     */
    public function removeGeometryN(int $offset): Geometry
    {
        if (!isset($this->geometries)) {
            throw new OutOfRangeException("No geometry at offset $offset found!");
        }
        $oldValue = $this->geometries[$offset];
        unset($this->geometries[$offset]);
        $this->geometries = array_values($this->geometries);
        return $oldValue;
    }

    /**
     * @internal ArrayAccess::offsetExists implementation
     * {@inheritDoc}
     */
    public function offsetExists($offset): bool
    {
        return $this->hasGeometry($offset);
    }

    /**
     * @internal ArrayAccess::offsetGet implementation
     * {@inheritDoc}
     */
    public function offsetGet($offset): Geometry
    {
        return $this->getGeometryN($offset);
    }

    /**
     * @internal ArrayAccess::offsetSet implementation
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value): void
    {
        $this->setGeometryN($value, $offset);
    }

    /**
     * @internal ArrayAccess::offsetUnset implementation
     * {@inheritDoc}
     */
    public function offsetUnset($offset): void
    {
        $this->removeGeometryN($offset);
    }

    /**
     * @internal Iterator::rewind implementation
     * {@inheritDoc}
     */
    public function rewind(): void
    {
        reset($this->geometries);
    }

    /**
     * @internal Iterator::valid implementation
     * {@inheritDoc}
     */
    public function valid(): bool
    {
        return key($this->geometries) !== null;
    }

    /**
     * @internal Magic __isset implementation
     * {@inheritDoc}
     */
    public function __isset($name)
    {
        return $this->hasGeometry(static_cast_int($name));
    }

    /**
     * @internal Magic __set implementation
     * {@inheritDoc}
     */
    public function __set($name, $value)
    {
        $this->setGeometryN($value, static_cast_int($name));
    }

    /**
     * @internal Magic __get implementation
     * {@inheritDoc}
     */
    public function __get($name)
    {
        return $this->getGeometryN(static_cast_int($name));
    }

}
