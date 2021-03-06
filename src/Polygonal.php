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

/**
 * Polygonal is used to identify 2-dimensional Geometry subclasses.
 */
interface Polygonal
{

    /** Polygonal dimension value */
    public const DIMENSION = 2;

    /**
     * The inherent dimension of <i>this</i> geometric object, which must be 
     * less than or equal to the coordinate dimension. In non-homogeneous 
     * collections, this will return the largest topological dimension of the
     * contained objects.
     * @link https://www.ogc.org/standards/sfa Simple Feature Access - Part 1: Common Architecture
     * @return int the Polygonal object's dimension; (MUST BE 2)
     */
    public function getDimension(): int;

    /**
     * Gets the area
     * @return float
     */
    public function getArea(): float;

    /**
     * Gets the mathematical centroid. 
     * 
     * The results are not guaranteed to be on the Polygonal
     * @return Point
     */
    public function getCentroid(): Point;

    /**
     * Gets a Point guaranteed to be on the Polygonal
     * @return Point
     */
    public function getPointOnSurface(): Point;
}
