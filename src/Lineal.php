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
 * Lineal is used to identify 1-dimensional Geometry subclasses.
 */
interface Lineal
{

    /** Lineal dimension value */
    public const DIMENSION = 1;
    
    /**
     * The inherent dimension of <i>this</i> geometric object, which must be 
     * less than or equal to the coordinate dimension. In non-homogeneous 
     * collections, this will return the largest topological dimension of the
     * contained objects.
     * @link https://www.ogc.org/standards/sfa Simple Feature Access - Part 1: Common Architecture
     * @return int the Lineal object's dimension; (MUST BE 1)
     */
    public function getDimension(): int;
    
    /**
     * Gets the length
     * @return float
     */
    public function getLength(): float;
    
    /**
     * Indicates whether the Lineal is closed (Start Point = End Point)
     * @return bool
     */
    public function isClosed(): bool;
    
}
