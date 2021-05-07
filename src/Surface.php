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

use ArrayAccess;
use Iterator;
use Countable;

/**
 * A Surface is a 2-dimensional geometric object.
 * 
 * @link https://www.ogc.org/standards/sfa Simple Feature Access - Part 1: Common Architecture
 */
abstract class Surface extends Geometry implements Iterator, ArrayAccess, Countable, Polygonal
{
    
    /**
     * Gets the exterior ring of the Surface
     * @return Curve
     */
    public abstract function getExteriorRing(): Curve;
    
    /**
     * Gets the interior rings (holes).
     * 
     * @return LineString[]
     */
    public abstract function getInteriorRings(): array;

    /**
     * @see SpatialEngine::boundary()
     * @return MultiCurve
     */
    public function getBoundary(): MultiCurve
    {
        return $this->factory->getSpatialEngine()->boundary($this);
    }

    /**
     * {@inheritDoc}
     */
    public function getArea(): float
    {
        return $this->factory->getSpatialEngine()->area($this);
    }

    /**
     * {@inheritDoc}
     */
    public function getCentroid(): Point
    {
        return $this->factory->getSpatialEngine()->centroid($this);
    }

    /**
     * {@inheritDoc}
     */
    public function getPointOnSurface(): Point
    {
        return $this->factory->getSpatialEngine()->pointOnSurface($this);
    }

    /**
     * Indicates whether the Surface is empty
     * 
     * <strong>Note: this method only checks that the exterior ring is not empty
     * </strong>
     * @see Curve::isEmpty
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->getExteriorRing()->isEmpty();
    }

    /**
     * {@inheritDoc}
     */
    public function getDimension(): int
    {
        return self::DIMENSION;
    }

}
