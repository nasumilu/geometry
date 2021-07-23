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
 * MultiPolygon
 */
class MultiPolygon extends MultiSurface
{

    /** The well-known text type value */
    public const WKT_TYPE = 'multipolygon';

    /** The well-known binary type value */
    public const WKB_TYPE = 6;

    protected function createAllowedGeometry($geometry): Polygon
    {
        $polygon = $this->factory->create($geometry);
        if (!$polygon instanceof Polygon) {
            throw new InvalidArgumentException("MultiPolygon is a homogeneous collection which "
                            . "contains only Polyton objects, found " . $polygon->getGeometryType());
        }
        return $polygon;
    }
    
    public function getGeometryType(): string
    {
        return self::WKT_TYPE;
    }

}
