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

use InvalidArgumentException;

/**
 * Description of MultiPoint
 */
class MultiPoint extends GeometryCollection
{
    /** The well-known text type value */
    public const WKT_TYPE = 'multipoint';
    /** The well-known binary type value */
    public const WKB_TYPE = 4;


    /**
     * Constructs a MultiPoint with the GeometryFactory and set of Point(s)
     * 
     * Please use {@see GeometryFacotry::create} or {@see GeometryFactory::createLineString}
     * to construct a MultiPoint. If using this construct directly ensure that the 
     * <code>Point</code>s found in the set where constructed with the same 
     * <code>GeometryFactory</code>.
     * @param GeometryFactory $factory
     * @param Point $points
     */
    public function __construct(GeometryFactory $factory, Point ...$points)
    {
        parent::__construct($factory, ...$points);
    }
    
    /**
     * {@inheritDoc}
     */
    public function setGeometryN($point, ?int $offset = null): GeometryCollection
    {
        $_point = $this->factory->create($point);
        if(!$_point instanceof Point) {
            throw new InvalidArgumentException("Multipoint is a homogeneous collection which "
                    . "contains only Point objects, found " + $_point->getGeometryType());
        }
        $this->geometries[$offset] = $_point;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getDimension(): int
    {
        return 0;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getGeometryType(): string
    {
        return self::WKT_TYPE;
    }
}
