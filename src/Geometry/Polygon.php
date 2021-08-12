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

use OutOfRangeException;
use function count;
use function key;
use function next;
use function current;
use function reset;
use function array_values;
use function array_slice;

/**
 * Polygon is a planar Surface defined by 1 exterior boundary and 0 or more interior
 * boundaries. Each of the interior boundary defines a hole in the Polygon.
 *
 * @link https://www.ogc.org/standards/sfa Simple Feature Access - Part 1: Common Architecture
 */
class Polygon extends Surface
{

    /** The well-known text value */
    public const WKT_TYPE = 'polygon';

    /** The well-known binary value */
    public const WKB_TYPE = 3;

    /**
     * A set of LineString objects; The first one represents the Polygons
     * outer boundary. Preceding LineString represents holes.
     * @var LineString[]
     */
    private array $linestrings = [];

    /**
     * Constructs a Polygon with the GeometryFactory and set of LineStirng objects.
     *
     * Please use {@see GeometryFacotry::create} or {@see GeometryFactory::createPolygon}
     * to construct a Polygon. If using this construct directly ensure that the
     * <code>LineString</code>s found in the set and their <code>Point</code>s where
     * constructed with the same <code>GeometryFactory</code>.
     *
     * @param GeometryFactory $geometryFactory
     * @param LineString ...$linestrings
     */
    public function __construct(GeometryFactory $geometryFactory, LineString ...$linestrings)
    {
        parent::__construct($geometryFactory);
        // make sure it has at least one empty linestring element
        if(0 === count($linestrings)) {
            $linestrings = [$geometryFactory->createLineString()];
        }
        $this->linestrings = $linestrings;
    }

    /**
     * Gets the exterior ring of the Polygon
     * @return LineString
     */
    public function getExteriorRing(): LineString
    {
        return $this->linestrings[0];
    }

    public function getInteriorRings(): array
    {
        return array_slice($this->linestrings, 1);
    }

    /**
     * Gets the number of internal rings (holes) of the Polygon
     * @return int
     */
    public function getNumInteriorRings(): int
    {
        return $this->count() - 1;
    }

    public function getInteriorRingN(int $offset): LineString
    {
        $offset += 1;
        if (!isset($this->linestrings[$offset])) {
            throw new OutOfRangeException("Offset $offset not found!");
        }
        return $this->linestrings[$offset];
    }

    /**
     * @internal Countable::count implementation
     * {@inheritDoc}
     */
    public function count(): int
    {
        return count($this->linestrings);
    }

    /**
     * @internal Iterator::current implementation
     * {@inheritDoc}
     */
    public function current(): LineString
    {
        return current($this->linestrings);
    }

    /**
     * {@inheritDoc}
     */
    public function getGeometryType(): string
    {
        return self::WKT_TYPE;
    }

    /**
     * @internal Iterator::key implementation
     * {@inheritDoc}
     */
    public function key(): int
    {
        return key($this->linestrings);
    }

    /**
     * @internal Iterator::next implementation
     * {@inheritDoc}
     */
    public function next(): void
    {
        next($this->linestrings);
    }

    /**
     * @internal Iterator::rewind implementation
     * {@inheritDoc}
     */
    public function rewind(): void
    {
        reset($this->linestrings);
    }

    /**
     * @internal Iterator::valid implementation
     * {@inheritDoc}
     */
    public function valid(): bool
    {
        return key($this->linestrings) !== null;
    }

    /**
     * @internal ArrayAccess::offsetExists implementation
     * {@inheritDoc}
     */
    public function offsetExists($offset): bool
    {
        return isset($this->linestrings[$offset]);
    }

    /**
     * @internal ArrayAccess::offsetGet implementation
     * {@inheritDoc}
     */
    public function offsetGet($offset): LineString
    {
        return $this->linestrings[$offset];
    }

    /**
     * @internal ArrayAccess::offsetSet implementation
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value): void
    {
        $linestring = $this->factory->createLineString($value);
        $this->linestrings[$offset ?? $this->count()] = $linestring;
    }

    /**
     * @internal ArrayAccess::offsetUnset implementation
     * {@inheritDoc}
     */
    public function offsetUnset($offset): void
    {
        unset($this->linestrings[$offset]);
        $this->linestrings = array_values($this->linestrings);
    }

}
