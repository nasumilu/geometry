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
 * MultiLineString
 * @link https://www.ogc.org/standards/sfa Simple Feature Access - Part 1: Common Architecture
 */
class MultiLineString extends MultiCurve
{

    /** The well-known text type value */
    public const WKT_TYPE = 'multilinestring';

    /** The well-known binary type value */
    public const WKB_TYPE = 5;

    public function __construct(GeometryFactory $factory, LineString ...$curves)
    {
        parent::__construct($factory, ...$curves);
    }

    public function getGeometryType(): string
    {
        return self::WKT_TYPE;
    }

    protected function createAllowedGeometry($geometry): LineString
    {
        $linestring = $this->factory->create($geometry);
        if (!$linestring instanceof Point) {
            throw new InvalidArgumentException("MultiLineString is a homogeneous collection which "
                            . "contains only LineString objects, found " + $linestring->getGeometryType());
        }
        return $linestring;
    }

}
