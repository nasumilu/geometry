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

namespace Nasumilu\Spatial\Serializer\Encoder\Wkb;

use Nasumilu\Spatial\Geometry\{
    Point,
    LineString,
    Polygon,
    MultiPoint,
    MultiLineString,
    MultiPolygon,
    GeometryCollection
};

/**
 * Description of WkbFormat
 */
interface WkbFormat
{
    public const WKBZ = 1000; // wkb z-coordinate type
    public const WKBM = 2000; // wkb m-coordinate type
    public const EWKBZ = 0x80000000; // ewkb z-coordinate type
    public const EWKBM = 0x40000000; // ewkb m-coordinate type
    public const EWKB_SRID = 0x20000000; // ewkb srid flag
    public const WKB_TYPES = [
        Point::WKB_TYPE => Point::WKT_TYPE,
        LineString::WKB_TYPE => LineString::WKT_TYPE,
        Polygon::WKB_TYPE => Polygon::WKT_TYPE,
        MultiPoint::WKB_TYPE => MultiPoint::WKT_TYPE,
        MultiLineString::WKB_TYPE => MultiLineString::WKT_TYPE,
        MultiPolygon::WKB_TYPE => MultiPolygon::WKT_TYPE,
        GeometryCollection::WKB_TYPE => GeometryCollection::WKT_TYPE
    ];
    
}
