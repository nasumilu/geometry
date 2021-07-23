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
use function current;
use function reset;
use function next;
use function array_values;

/**
 * LineString is a Curve with linear interpolation between points. Each consecutive
 * pair of Points defines a Line segment.
 * 
 * @link https://www.ogc.org/standards/sfa Simple Feature Access - Part 1: Common Architecture
 */
class LineString extends Curve
{

    public const WKT_TYPE = 'linestring';
    public const WKB_TYPE = 2;

    private $points;

    /**
     * Constructs a LineString with the GeometryFactory and set of Point(s)
     * 
     * Please use {@see GeometryFacotry::create} or {@see GeometryFactory::createLineString}
     * to construct a LineString. If using this construct directly ensure that the 
     * <code>Point</code>s found in the set where constructed with the same 
     * <code>GeometryFactory</code>.
     * 
     * @param GeometryFactory $factory
     * @param Point $points
     */
    public function __construct(GeometryFactory $factory, Point ...$points)
    {
        parent::__construct($factory);
        $this->points = $points;
    }

    /**
     * {@inheritDoc}
     */
    public function current(): Point
    {
        return current($this->points);
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
    public function getNumPoints(): int
    {
        return count($this->points);
    }

    /**
     * {@inheritDoc}
     */
    public function getPointN(int $offset): Point
    {
        if (!$this->hasPointN($offset)) {
            throw new OutOfRangeException("Offset: $offset out of range!");
        }
        return $this->points[$offset];
    }

    /**
     * {@inheritDoc}
     */
    public function hasPointN(int $offset): bool
    {
        return isset($this->points[$offset]);
    }

    public function key(): int
    {
        return key($this->points);
    }

    /**
     * {@inheritDoc}
     */
    public function next(): void
    {
        next($this->points);
    }

    /**
     * {@inheritDoc}
     */
    public function removePointN(int $offset): Point
    {
        if (!$this->hasPointN($offset)) {
            throw new OutOfRangeException("Offset: $offset out of range!");
        }
        $oldValue = $this->points[$offset];
        unset($this->points[$offset]);
        $this->points = array_values($this->points);
        return $oldValue;
    }

    /**
     * {@inheritDoc}
     */
    public function rewind(): void
    {
        reset($this->points);
    }

    /**
     * {@inheritDoc}
     */
    public function setPointN($point, ?int $offset = null): LineString
    {
        $this->points[$offset ?? count($this->points)] = $this->factory->create($point);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function valid(): bool
    {
        return null !== key($this->points);
    }

}
