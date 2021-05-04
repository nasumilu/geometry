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

/**
 * A Surface is a 2-dimensional geometric object.
 * 
 * @link https://www.ogc.org/standards/sfa Simple Feature Access - Part 1: Common Architecture
 */
abstract class Surface extends Geometry implements Iterator, Countable
{
    /**
     * @see SpatialEngine::boundary()
     * @return MultiCurve
     */
    public function getBoundary() : MultiCurve {
        return $this->factory->getSpatialEngine()->boundary($this);
    }
    
    /**
     * @see SpatialEngine::area
     * @return float
     */
    public function getArea(): float 
    {
        $this->factory->getSpatialEngine()->area($this);
    }
    
    /**
     * @see SpatialEngine::centroid
     * @return type
     */
    public function getCentroid() {
        return $this->factory->getSpatialEngine()->centroid($this);
    }
    
    /**
     * @see SpatialEngine::pointOnSurface
     * @return Point
     */
    public function getPointOnSurface(): Point
    {
        return $this->factory->getSpatialEngine()->getPointOnSurface($this);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getDimension(): int
    {
        return 2;
    }

}
