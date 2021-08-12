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

namespace Nasumilu\Spatial\Serializer\Decoder;

use Symfony\Component\Serializer\Encoder\ChainDecoder;
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
 * WkbDecoder is used to decode well-known binary into a normalized geometry
 * <br>
 * <strong>Supported Formats</strong>
 * <ul>
 *  <li><em>wkb11</em> - {@link https://portal.ogc.org/files/?artifact_id=13227 Well-known Binary v1.1.0}</li>
 *  <li><em>wkb</em> - {@link https://portal.ogc.org/files/?artifact_id=18241 Well-Known Binary v1.2.0}</li>
 *  <li><em>ewkb</em> - {@link https://postgis.net/docs/ST_GeomFromEWKB.html Extended Well-Known Binary}</li>
 * </ul>
 */
class WkbDecoder extends ChainDecoder
{

    /** Little-endian */
    public const NDR = 'NDR';

    /** Big-endian */
    public const XDR = 'XDR';  
    
    /** Byteorder (endianness) context option */
    public const ENDIANNESS = 'endianness';
    
    /**
     * Map with the key value as the wkb type value and the value as its
     * wkt type value
     */
    public const WKB_TYPES = [
        Point::WKB_TYPE => Point::WKT_TYPE,
        LineString::WKB_TYPE => LineString::WKT_TYPE,
        Polygon::WKB_TYPE => Polygon::WKT_TYPE,
        MultiPoint::WKB_TYPE => MultiPoint::WKT_TYPE,
        MultiLineString::WKB_TYPE => MultiLineString::WKT_TYPE,
        MultiPolygon::WKB_TYPE => MultiPolygon::WKT_TYPE,
        GeometryCollection::WKB_TYPE => GeometryCollection::WKT_TYPE
    ];

    /**
     * {@inhertiDoc}
     */
    public function __construct()
    {
        parent::__construct([
            new Wkb\Wkb11Decoder(),
            new Wkb\Wkb12Decoder(),
            new Wkb\EwkbDecoder()
        ]);
    }

}
