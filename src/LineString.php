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

use function \count;
use function \key;
use function \current;
use function \reset;
use function \next;
use function array_values;

/**
 * Description of LineString
 */
class LineString extends Curve
{
    public const WKT_TYPE = 'linestring';
    public const WKB_TYPE = 2;
    
    private array $points;
    
    public function __construct(GeometryFactory $factory, Point ...$points)
    {
        parent::__construct($factory);
        $this->points = $points;
    }

    /**
     * {@inheritDoc}
     */
    public function current(): mixed
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
    public function removePointN(int $offset): void
    {
        unset($this->points[$offset]);
        $this->points = array_values($this->points);
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
    public function setPointN($point, int $offset = null)
    {
        $this->points[$offset ?? count($this->points)] = 
                $this->factory->create($point);
    }

    /**
     * {@inheritDoc}
     */
    public function valid(): bool
    {
        return null !== key($this->points);
    }

}
