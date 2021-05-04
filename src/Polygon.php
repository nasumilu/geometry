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
    private $linestrings = [];

    /**
     * Constructs a Polygon with the GeometryFactory and set of LineStirng objects.
     *
     * Please use {@see GeometryFacotry::create} or {@see GeometryFactory::createPolygon}
     * to construct a Polygon. If using this construct directly ensure that the
     * <code>LineString</code>s found in the set and their <code>Point</code>s where
     * constructed with the same <code>GeometryFactory</code>.
     *
     * @param GeometryFactory $geometryFactory
     * @param LineString $linestrings
     */
    public function __construct(GeometryFactory $geometryFactory, LineString ...$linestrings)
    {
        parent::__construct($geometryFactory);
        // make sure it has at least one empty linestring element
        $this->linestrings = count($linestrings) === 0 ? [$geometryFactory->createLineString()] : $linestrings;
    }

    /**
     * Gets the exterior ring of the Polygon
     * @return LineString
     */
    public function getExternalRing(): LineString
    {
        return $this->linestrings[0];
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
        if (!isset($this->linestrings[$offset + 1])) {
            throw new OutOfRangeException("Offset $offset not found!");
        }
        return $this->linestrings[$offset + 1];
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

}
