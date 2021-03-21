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
 * SpatialEngine provides shared geometry accessors, spatial relationships, and 
 * processing
 * @link https://www.ogc.org/standards/sfa Simple Feature Access - Part 1: Common Architecture
 */
interface SpatialEngine
{

    /**
     * The minimum bounding box of a Geometry.
     * 
     * @param Geometry $geometry
     * @return Geometry
     */
    public function envelope(Geometry $geometry): Geometry;

    /**
     * Indicates whether a Geometry has anomalous geometric points, such as
     * self intersection or self tangency.
     * @param Geometry $geometry used to check if simple
     * @return bool true if simple; false otherwise
     */
    public function isSimple(Geometry $geometry): bool;
    
    /**
     * Indicates whether a Geometry is empty.
     * @param Geometry $geometry use to chek if empty
     * @return bool if Geometry represents an empty point set ∅ for the 
     *              coordinate space.
     */
    public function isEmpty(Geometry $geometry): bool;

    /**
     * Gets the closure of the combinatorial boundary of the Geometry object.
     * @param Geometry $geometry
     * @return Geometry
     */
    public function boundary(Geometry $geometry): Geometry;

    /**
     * Indicates whether two Geometry objects are spatially equal.
     * 
     * @param Geometry $geometry
     * @param Geometry $other
     * @return bool
     */
    public function equals(Geometry $geometry, Geometry $other): bool;

    /**
     * Indicates whether two Geometry objects are spatially disjoint.
     * @param Geometry $geometry
     * @param Geometry $other
     * @return bool
     */
    public function disjoint(Geometry $geometry, Geometry $other): bool;

    public function intersects(Geometry $geometry, Geometry $other): bool;

    public function touches(Geometry $geometry, Geometry $other): bool;

    public function crosses(Geometry $geometry, Geometry $other): bool;

    public function within(Geometry $geometry, Geometry $other): bool;

    public function contains(Geometry $geometry, Geometry $other): bool;

    public function overlaps(Geometry $geometry, Geometry $other): bool;

    public function relate(Geometry $geometry, Geometry $other, string $matrix): bool;

    public function locateAlong(Geometry $geometry, float $mValue): Geometry;

    public function locateBetween(Geometry $geometry, float $mStart, float $mEnd): Geometry;

    // analysis
    public function distance(Geometry $geometry, Geometry $other): float;

    public function buffer(Geometry $geometry, float $distance): Geometry;

    public function convexHull(Geometry $geometry): Geometry;

    public function intersection(Geometry $geometry, Geometry $other): Geometry;

    public function union(Geometry $geometry, Geometry $other): Geometry;

    public function difference(Geometry $geometry, Geometry $other): Geometry;

    public function symDifference(Geometry $geometry, Geometry $other): Geometry;
    
}
