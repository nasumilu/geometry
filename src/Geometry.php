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
        return $this->factory->getCoordianteSystem()->srid();
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
     * The inherent dimension of <i>this</i> geometric object, which must be 
     * less than or equal to the coordinate dimension. In non-homogeneous 
     * collections, this will return the largest topological dimension of the
     * contained objects.
     * @link https://www.ogc.org/standards/sfa Simple Feature Access - Part 1: Common Architecture
     * @return int the Geometry object's dimension
     */
    public abstract function getDimension(): int;

    /**
     * Gets the simple name of the instantiable subtype of Geometry of which 
     * <i>this</i> geometric object is an instantiable member. The name of the 
     * subtype of Geometry is a lowercase string.
     * @link https://www.ogc.org/standards/sfa Simple Feature Access - Part 1: Common Architecture
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
