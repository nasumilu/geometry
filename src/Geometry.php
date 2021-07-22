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
 * The root (base) class of the hierarchy. 
 * @link https://www.ogc.org/standards/sfa Simple Feature Access - Part 1: Common Architecture
 */
abstract class Geometry
{

    /**
     * @var GeometryFactory
     */
    protected $factory;

    public function __construct(GeometryFactory $geometryFactory)
    {
        $this->factory = $geometryFactory;
    }

    /**
     * Gets the GemetryFactory which contains the context used to create this
     * Geometry.
     * @return GeometryFactory S
     */
    public function getFactory(): GeometryFactory
    {
        return $this->factory;
    }

    /**
     * Gets the Spatial Reference System ID
     * @return int the Geometry objects SRID
     */
    public function getSrid(): int
    {
        return $this->factory->getCoordianteSystem()->getSrid();
    }

    /**
     * Indicates whether the GeometryFactory supports the z-coordinate.
     * @return bool
     */
    public function is3D(): bool
    {
        return $this->factory->getCoordianteSystem()->is3D();
    }

    /**
     * Indicates whether the GeometryFactory supports the m-coordinate.
     * @return bool
     */
    public function isMeasured(): bool
    {
        return $this->factory->getCoordianteSystem()->isMeasured();
    }

    /**
     * {@inheritDoc}
     */
    public function getEnvelope(): Geometry
    {
        return $this->factory->getSpatialEngine()->envelope($this);
    }

    public function isSimple(): bool
    {
        return $this->factory->getSpatialEngine()->isSimple($this);
    }

    public function getBoundary(): Geometry
    {
        return $this->factory->getSpatialEngine()->boundary($this);
    }

    /**
     * Indicates whether <code>$this</code> Geometry object is spatially 
     * disjoint to <code>$other</code> Geometry object
     * @param Geometry $other
     * @return bool
     */
    public function disjoint(Geometry $other): bool
    {
        return $this->factory->getSpatialEngine()->disjoint($this, $other);
    }

    /**
     * Indicates whether <code>$this</code> Geometry object spatially 
     * intersects <code>$other</code> Geometry object
     * @param Geometry $other
     * @return bool
     */
    public function intersects(Geometry $other): bool
    {
        return $this->factory->getSpatialEngine()->intersects($this, $other);
    }

    /**
     * Indicates whether <code>$this</code> Geometry object spatially touches
     * <code>$other</code> Geometry object
     * @param Geometry $other
     * @return bool
     */
    public function touches(Geometry $other): bool
    {
        return $this->factory->getSpatialEngine()->touches($this, $other);
    }

    /**
     * Indicates whether <code>$this</code> Geometry object spatial crosses 
     * <code>$other</code> Geometry object
     * @param Geometry $other
     * @return bool
     */
    public function crosses(Geometry $other): bool
    {
        return $this->factory->getSpatialEngine()->crosses($this, $other);
    }

    /**
     * Indicates whether <code>$this</code> Geometry object is spatially within
     * <code>$other</code> Geometry object
     * @param Geometry $other
     * @return bool
     */
    public function within(Geometry $other): bool
    {
        return $this->factory->getSpatialEngine()->within($this, $other);
    }

    /**
     * Indicates whether <code>$this</code> object is spatially 
     * contains by <code>$other</code> Geometry object
     * @param Geometry $other
     * @return bool
     */
    public function contains(Geometry $other): bool
    {
        return $this->factory->getSpatialEngine()->contains($this, $other);
    }

    /**
     * Indicates whether <code>$this</code> Geometry object spatially 
     * overlaps <code>$other</code> Geometry object
     * @param Geometry $other
     * @return bool
     */
    public function overlaps(Geometry $other): bool
    {
        return $this->factory->getSpatialEngine()->overlaps($this, $other);
    }

    /**
     * Indicates whether <code>$this</code> object is spatially 
     * related to <code>$other</code> by testing for intersections between the
     * interior boundary and exterior of the two Geometry objects specified by 
     * the values in the <code>$matrix</code>
     * @param Geometry $other
     * @param string $matrix
     * @return bool
     */
    public function relate(Geometry $other, string $matrix): bool
    {
        if (0 === preg_match('/^(T|F|\*){9}$/i', $matrix)) {
            throw new \InvalidArgumentException('Invalid DE-9IM model! Model must'
                            . ' only contain 9 characters of T, F, or *');
        }
        return $this->factory->getSpatialEngine()->relate($this, $other, $matrix);
    }

    /**
     * Gets a derived Geometry value that matches the specified <em>m</em> 
     * -coordinate value.
     * @param float $mValue
     * @return Geometry
     * @throws InvalidArgumentException when the geometry is polygonal
     * @throws CoordinateException if the m-coordinate is not supported
     */
    public function locateAlong(float $mValue): Geometry
    {
        if ($this instanceof Polygonal) {
            throw new \InvalidArgumentException('Polygongal geometry objects '
                            . 'are not supported!');
        }
        if (!$this->isMeasured()) {
            throw CoordinateException::ordinateNotSupported('m');
        }
        return $this->factory->getSpatialEngine()->locateAlong($this, $mValue);
    }

    /**
     * Gets a derived Geometry value that matches the specified range of <em>m</em>
     * -coordinate values inclusively. 
     * @param float $mStart
     * @param float $mEnd
     * @return Geometry
     * @throws InvalidArgumentException when the geometry is polygonal
     * @throws CoordinateException if m-coordinate is not supported
     */
    public function locateBetween(float $mStart, float $mEnd): Geometry
    {
        if ($this instanceof Polygonal) {
            throw new \InvalidArgumentException('Polygongal geometry objects '
                            . 'are not supported!');
        }
        if (!$this->isMeasured()) {
            throw CoordinateException::ordinateNotSupported('m');
        }
        return $this->factory->getSpatialEngine()->locateBetween($this, $mStart, $mEnd);
    }

    /**
     * Gets the shortest distance between any two Points in <code>$this</code>
     * Geometry object and <code>$other</code> Geometry object
     * @param Geometry $other
     * @return float
     */
    public function distance(Geometry $other): float
    {
        return $this->factory->getSpatialEngine()->disjoint($this, $other);
    }

    /**
     * Gets a Geometry object that represents all Points whose distance from 
     * the <code>$geometry</code> object is less than or equal to 
     * <code>$distance</code>
     * @param float $distance
     * @return Geometry
     */
    public function buffer(float $distance): Geometry
    {
        return $this->factory->getSpatialEngine()->buffer($this, $distance);
    }

    /**
     * Gets a Geometry object that represents the convex hull of <code>$geometry
     * </code> object
     * @return Geometry
     */
    public function convexHull(): Geometry
    {
        return $this->factory->getSpatialEngine()->convexHull($this);
    }

    /**
     * Gets a Geometry object that represents the Point set intersection of 
     * <code>$this</code> Geometry object with <code>$other</code> Geometry
     * object.
     * @param Geometry $other
     * @return Geometry
     */
    public function intersection(Geometry $other): Geometry
    {
        return $this->factory->getSpatialEngine()->intersection($this, $other);
    }

    /**
     * Gets a Geometry object that represents the Point set union of <code>$geometry
     * </code> object with <code>$other</code> Geometry object.
     * @param Geometry $other
     * @return Geometry
     */
    public function union(Geometry $other): Geometry
    {
        return $this->factory->getSpatialEngine()->union($this, $other);
    }

    /**
     * Get a Geometry object that represents the Point set difference of
     * <code>$this</code> Geometry object with <code>$other</code> Geometry 
     * object.
     * @param Geometry $other
     * @return Geometry
     */
    public function difference(Geometry $other): Geometry
    {
        return $this->factory->getSpatialEngine()->difference($this, $other);
    }

    /**
     * Get the Geometry object that represents the Point set symmetric difference
     * of <code>$this</code> Geometry object with <code>$other</code> Geometry 
     * object.
     * @param Geometry $other
     * @return Geometry
     */
    public function symDifference(Geometry $other): Geometry
    {
        return $this->factory->getSpatialEngine()->symDifference($this, $other);
    }

    /**
     * Gets a new Geometry object with its coordinates transformed to a different
     * spatial reference system contained in the <code>$factory</code>
     * @param GeometryFactory $factory
     * @return Geometry
     */
    public function transform(GeometryFactory $factory): Geometry
    {
        if ($this->factory->getCoordianteSystem()->getSrid() ===
                $factory->getCoordianteSystem()->getSrid()) {
            return $factory->create($this);
        }
        return $this->factory->getSpatialEngine()->transform($this, $factory);
    }

    /**
     * The inherent dimension of <i>this</i> geometric object, which must be 
     * less than or equal to the coordinate dimension. In non-homogeneous 
     * collections, this will return the largest topological dimension of the
     * contained objects.
     * @return int the Geometry object's dimension
     */
    public abstract function getDimension(): int;

    /**
     * Gets the simple name of the instantiable subtype of Geometry of which 
     * <i>this</i> geometric object is an instantiable member. The name of the 
     * subtype of Geometry is a lowercase string.
     * @return string the Geometry object's instantiable subtype simple name
     */
    public abstract function getGeometryType(): string;

    /**
     * Indicates whether a Geometry is empty or not
     * 
     * @return bool
     */
    public abstract function isEmpty(): bool;
}
