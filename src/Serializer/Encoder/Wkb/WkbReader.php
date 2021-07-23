<?php

/*
 * Copyright 2021 Michael Lucas.
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

declare(strict_types=1);

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
use function is_resource;
use function stream_get_contents;
use function ctype_xdigit;
use function pack;
use function array_merge;
use function strtolower;

/**
 * Description of WkbReader
 *
 * @author Michael Lucas <mlucas@nasumilu.com>
 */
class WkbReader implements WkbFormat
{
    /**
     * The current position
     * @var int
     */
    protected $position;

    /**
     * The wkb string to read
     * @var string
     */
    protected $wkb;

    public function __construct($wkb)
    {
        if (is_resource($wkb)) {
            $wkb = stream_get_contents($wkb);
        }

        if (ctype_xdigit($wkb)) {
            $wkb = pack('H*', $wkb);
        }
        $this->wkb = $wkb;
        $this->position = 0;
    }

    /**
     * Reads either a wkb or ewkb binary or hex string.
     *
     * @return array
     */
    public function read(): array
    {

        $byteorder = $this->readByteOrder();
        $type = $this->readGeometryType($byteorder);
        $wkbData = $this->readGeometry($byteorder, $type['type'], $type['crs']['3d'], $type['crs']['measured']);
        return array_merge($type, $wkbData);
    }

    protected function readLong(int $byteorder): int
    {
        $value = unpack(($byteorder) ? 'V' : 'N', $this->wkb, $this->position)[1];
        $this->position += 4;
        return $value;
    }

    protected function readGeometryCollection(int $byteorder, bool $is3D, bool $isMeasured)
    {
        $numGeometries = $this->readLong($byteorder);
        $geometries = [];
        for ($i = 0; $i < $numGeometries; $i++) {
            $byteorder = $this->readByteOrder();
            $type = $this->readGeometryType($byteorder);
            $geometry = $this->readGeometry($byteorder, $type['type'], $is3D, $isMeasured);
            $geometries[] = array_merge($type, $geometry);
        }
        return $geometries;
    }

    protected function readByteOrder(): int
    {
        $value = unpack('C', $this->wkb, $this->position)[1];
        $this->position += 1;
        return $value;
    }

    protected function readGeometry(int $byteorder, string $type, bool $is3D, bool $isMeasured): array
    {
        $method = "read$type";
        $_data = $this->$method($byteorder, $is3D, $isMeasured);
        if (strtolower($type) === GeometryCollection::WKT_TYPE) {
            return ['geometries' => $_data];
        }
        return ['coordinates' => $_data];
    }

    protected function readGeometryType(int $byteorder): array
    {
        $data = [
            'type' => null,
            'crs' => [
                'srid' => -1,
                '3d' => false,
                'measured' => false
            ],
        ];
        $type = $this->readLong($byteorder);
        if ($this->isEwkb($type)) {
            $data['type'] = self::WKB_TYPES[$type & 0xFF];
            if (($type & self::EWKB_SRID) === self::EWKB_SRID) {
                $data['crs']['srid'] = $this->readLong($byteorder);
            }
            $data['crs']['3d'] = ($type & self::EWKBZ) === self::EWKBZ;
            $data['crs']['measured'] = ($type & self::EWKBM) === self::EWKBM;
        } else {
            $data['type'] = self::WKB_TYPES[$type % 1000];
            $dimension = $type - ($type % 1000);
            $data['crs']['3d'] = $dimension === 1000 || $dimension === 3000;
            $data['crs']['measured'] = $dimension === 2000 || $dimension === 3000;
        }
        return $data;
    }

    /**
     * Indicates that the format is extended well-known binary(EWKB).
     *
     * @param int $type
     * @return bool
     */
    protected function isEwkb(int $type): bool
    {
        $wktType = $type & 0xFF;
        return ($wktType >= 1 && $wktType <= 7); // || ($wktType >= 15 && $wktType <= 17);
    }

    protected function readMultiPoint(int $byteorder, bool $is3D, bool $isMeasured): array
    {
        $numPoints = $this->readLong($byteorder);
        $points = [];
        for ($i = 0; $i < $numPoints; $i++) {
            $this->readByteOrder();
            $this->readGeometryType($byteorder);
            $points[] = $this->readPoint($byteorder, $is3D, $isMeasured);
        }
        return $points;
    }

    protected function readMultiLinestring(int $byteorder, bool $is3D, bool $isMeasured): array
    {
        $numSequences = $this->readLong($byteorder);
        $sequences = [];
        for ($i = 0; $i < $numSequences; $i++) {
            $this->readByteOrder();
            $this->readGeometryType($byteorder);
            $sequences[] = $this->readLineString($byteorder, $is3D, $isMeasured);
        }
        return $sequences;
    }

    protected function readMultiPolygon(int $byteorder, bool $is3D, bool $isMeasured): array
    {
        $numPolygons = $this->readLong($byteorder);
        $polygons = [];
        for ($i = 0; $i < $numPolygons; $i++) {
            $this->readByteOrder();
            $this->readGeometryType($byteorder);
            $polygons[] = $this->readPolygon($byteorder, $is3D, $isMeasured);
        }
        return $polygons;
    }

    protected function readPolygon(int $byteorder, bool $is3D, bool $isMeasured): array
    {
        $numLinestrings = $this->readLong($byteorder);
        $linestrings = [];
        for ($i = 0; $i < $numLinestrings; $i++) {
            $linestrings[] = $this->readLineString($byteorder, $is3D, $isMeasured);
        }
        return $linestrings;
    }

    protected function readLineString(int $byteorder, bool $is3D, bool $isMeasured): array
    {
        $numCoordinates = $this->readLong($byteorder);
        $sequence = [];
        for ($i = 0; $i < $numCoordinates; $i++) {
            $sequence[] = $this->readPoint($byteorder, $is3D, $isMeasured);
        }
        return $sequence;
    }

    protected function readPoint(int $byteorder, bool $is3D, bool $isMeasured): array
    {
        $format = ($byteorder) ? 'e' : 'E';
        $unpackFormat = '%1$sx/%1$sy';
        $dimension = 2;
        if ($is3D) {
            $dimension += 1;
            $unpackFormat .= '/%1$sz';
        }
        if ($isMeasured) {
            $dimension += 1;
            $unpackFormat .= '/%1$sm';
        }
        $unpack = sprintf($unpackFormat, $format);
        $values = unpack($unpack, $this->wkb, $this->position);
        $this->position += $dimension * 8;
        return array_values($values);
    }

}
