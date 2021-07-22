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
     * @param Geometry $geometry use to check if empty
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
     * @param Geometry $geometry
     * @param Geometry $other
     * @return bool
     */
    public function equals(Geometry $geometry, Geometry $other): bool;

    /**
     * Gets the area of a Polygonal objects
     * @param Polgyonal $polygonal
     * @return float
     */
    public function area(Polygonal $polygonal): float;

    /**
     * Get the length of a Lineal object
     * @param Lineal $lineal
     * @return float
     */
    public function length(Lineal $lineal): float;

    /**
     * Gets the mathematical centroid of a Surface. The result is not 
     * guaranteed to be on the Surface
     * @param Surface $polygonal
     * @return Point
     */
    public function centroid(Polygonal $polygonal): Point;

    /**
     * Gets a Point guaranteed to be on the Surface
     * @param Surface $polygonal
     * @return Point
     */
    public function pointOnSurface(Polygonal $polygonal): Point;

    /**
     * Indicates whether <code>$geometry</code> object is spatially 
     * disjoint to <code>$other</code> Geometry object
     * @param Geometry $geometry
     * @param Geometry $other
     * @return bool
     */
    public function disjoint(Geometry $geometry, Geometry $other): bool;

    /**
     * Indicates whether <code>$geometry</code> object spatially 
     * intersects <code>$other</code> Geometry object
     * @param Geometry $geometry
     * @param Geometry $other
     * @return bool
     */
    public function intersects(Geometry $geometry, Geometry $other): bool;

    /**
     * Indicates whether <code>$geometry</code> object spatially touches
     * <code>$other</code> Geometry object
     * @param Geometry $geometry
     * @param Geometry $other
     * @return bool
     */
    public function touches(Geometry $geometry, Geometry $other): bool;

    /**
     * Indicates whether <code>$geometry</code> object spatial crosses 
     * <code>$other</code> Geometry object
     * @param Geometry $geometry
     * @param Geometry $other
     * @return bool
     */
    public function crosses(Geometry $geometry, Geometry $other): bool;

    /**
     * Indicates whether <code>$geometry</code> object is spatially within
     * <code>$other</code> Geometry object
     * @param Geometry $geometry
     * @param Geometry $other
     * @return bool
     */
    public function within(Geometry $geometry, Geometry $other): bool;

    /**
     * Indicates whether <code>$geometry</code> object is spatially 
     * contains <code>$other</code> Geometry object
     * @param Geometry $geometry
     * @param Geometry $other
     * @return bool
     */
    public function contains(Geometry $geometry, Geometry $other): bool;

    /**
     * Indicates whether <code>$geometry</code> object spatially 
     * overlaps <code>$other</code> Geometry object
     * @param Geometry $geometry
     * @param Geometry $other
     * @return bool
     */
    public function overlaps(Geometry $geometry, Geometry $other): bool;

    /**
     * Indicates whether <code>$geometry</code> object is spatially 
     * related to <code>$other</code> by testing for intersections between the
     * interior boundary and exterior of the two Geometry objects specified by 
     * the values in the <code>$matrix</code>.
     * @param Geometry $geometry
     * @param Geometry $other
     * @param string $matrix
     * @return bool
     */
    public function relate(Geometry $geometry, Geometry $other, string $matrix): bool;

    /**
     * Gets a derived Geometry value that matches the specified <em>m</em> 
     * -coordinate value.
     * @param Geometry $geometry
     * @param float $mValue
     * @return Geometry
     */
    public function locateAlong(Geometry $geometry, float $mValue): Geometry;

    /**
     * Gets a derived Geometry value that matches the specified range of <em>m</em>
     * -coordinate values inclusively. 
     * @param Geometry $geometry
     * @param float $mStart
     * @param float $mEnd
     * @return Geometry
     */
    public function locateBetween(Geometry $geometry, float $mStart, float $mEnd): Geometry;

    /**
     * Gets the shortest distance between any two Points in two Geometry objects.
     * @param Geometry $geometry
     * @param Geometry $other
     * @return float
     */
    public function distance(Geometry $geometry, Geometry $other): float;

    /**
     * Gets a Geometry object that represents all Points whose distance from 
     * the <code>$geometry</code> object is less than or equal to 
     * <code>$distance</code>.
     * @param Geometry $geometry
     * @param float $distance
     * @return Geometry
     */
    public function buffer(Geometry $geometry, float $distance): Geometry;

    /**
     * Gets a Geometry object that represents the convex hull of <code>$geometry
     * </code>.
     * @param Geometry $geometry
     * @return Geometry
     */
    public function convexHull(Geometry $geometry): Geometry;

    /**
     * Gets a Geometry object that represents the Point set intersection of 
     * <code>Geometry</code> object with <code>$other</code> Geometry
     * object.
     * @param Geometry $geometry
     * @param Geometry $other
     * @return Geometry
     */
    public function intersection(Geometry $geometry, Geometry $other): Geometry;

    /**
     * Gets a Geometry object that represents the Point set union of <code>$geometry
     * </code> object with <code>$other</code> Geometry object.
     * @param Geometry $geometry
     * @param Geometry $other
     * @return Geometry
     */
    public function union(Geometry $geometry, Geometry $other): Geometry;

    /**
     * Get a Geometry object that represents the Point set difference of
     * <code>$geometry</code> object with <code>$other</code> Geometry object.
     * 
     * @param Geometry $geometry
     * @param Geometry $other
     * @return Geometry
     */
    public function difference(Geometry $geometry, Geometry $other): Geometry;

    /**
     * Get the Geometry object that represents the Point set symmetric difference
     * of <code>$geometry</code> object with <code>$other</code> Geometry object.
     * @param Geometry $geometry
     * @param Geometry $other
     * @return Geometry
     */
    public function symDifference(Geometry $geometry, Geometry $other): Geometry;

    /**
     * Gets a new Geometry object with its coordinates transformed to a different
     * spatial reference system contained in the <code>$factory</code>
     * @param Geometry $geometry
     * @param GeometryFactory $factory
     * @return Geometry
     */
    public function transform(Geometry $geometry, GeometryFactory $factory): Geometry;
}
