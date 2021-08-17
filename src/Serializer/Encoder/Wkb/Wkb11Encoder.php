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

use Symfony\Component\Serializer\Encoder\EncoderInterface;
use InvalidArgumentException;
use Nasumilu\Spatial\Serializer\Encoder\WkbEncoder;
use Nasumilu\Spatial\Serializer\{
    Endianness,
    EndiannessTrait
};
use Nasumilu\Spatial\Geometry\{
    Point,
    LineString,
    Polygon
};
use function strtolower;
use function pack;
use function unpack;
use function count;

/**
 * Well-known binary version 1.1.0 encoder
 * 
 * @link https://portal.ogc.org/files/?artifact_id=13227 OpenGIS&reg; Part1: Common architecture
 */
class Wkb11Encoder implements EncoderInterface
{

    use EndiannessTrait;

    /** Well-known binary 1.1 format */
    public const FORMAT = 'wkb11';

    public function __construct(string $endiannes = Endianness::NDR)
    {
        $this->endianness($endiannes);
    }

    public function encode($data, string $format, array $context = []): string
    {
        $context[Endianness::ENDIANNESS] = $context[Endianness::ENDIANNESS] ?? $this->endianness;
        if (!$this->validateEndianness($context[Endianness::ENDIANNESS])) {
            throw new InvalidArgumentException(sprintf('Endianness must be %s or %s, found %s!',
                                    Endianness::NDR, Endianness::XDR, $context[Endianness::ENDIANNESS]));
        }
        $wkb = $this->encodeNormalizedGeometry($data, $context);
        if ((bool) ($context[WkbEncoder::HEX_STR] ?? false)) {
            return unpack('H*', $wkb)[1];
        }
        return $wkb;
    }

    public function getEndianness(): string
    {
        return $this->endianness;
    }

    private function validateEndianness(string $endianness): bool
    {
        return in_array(strtolower($endianness),
                array_map('strtolower', [Endianness::NDR, Endianness::XDR]),
                true);
    }

    public final function setEndianness(string $endianness = self::NDR): void
    {
        if (!$this->validateEndianness($endianness)) {
            throw new \InvalidArgumentException(sprintf('Endianness must be %s '
                                    . 'or %s, found %!', Endianness::NDR, Endianness::XDR, $endianness));
        }
        $this->endianness = $endianness;
    }

    public function supportsEncoding(string $format): bool
    {
        return strtolower($format) === static::FORMAT;
    }

    /**
     * Encodes a normalized geometry as well-known text
     * @param array $data
     * @param array $context
     * @return string
     */
    protected function encodeNormalizedGeometry(array $data, array $context = []): string
    {
        $wkb = $this->encodeEndianness($context[Endianness::ENDIANNESS]);
        $wkb .= $this->encodeGeometryType($data, $context);
        if (!isset($data['coordinates']) && !isset($data['geometries'])) {
            return $wkb;
        }
        $wkb .= call_user_func([$this, "encode{$data['type']}"], $data, $context);
        return $wkb;
    }

    /**
     * Encodes a geometry type as well-known binary
     * 
     * @param array $data
     * @return string
     */
    protected function encodeGeometryType(array $data, array $context = []): string
    {
        return $this->packUInt32($context[Endianness::ENDIANNESS], $data['binary_type']);
    }

    /**
     * Encodes a single coordinate as well-known binary
     * 
     * @param float[] $values
     * @return string
     */
    protected function encodeCoordinate(array $values, array $context = []): string
    {
        return $this->packDouble($context[Endianness::ENDIANNESS], ...array_slice($values, 0, 2));
    }

    /**
     * Encodes a coordinate sequence as well-known text
     * 
     * @param array $values
     * @return string
     */
    protected function encodeCoordinateSequence(array $values, array $context = []): string
    {
        $wkb = $this->packUInt32($context[Endianness::ENDIANNESS], count($values));
        foreach ($values as $value) {
            $wkb .= $this->encodeCoordinate($value, $context);
        }
        return $wkb;
    }

    /**
     * Encodes the coordinates of a point as well-known binary
     * 
     * @param array $data
     * @param array $context
     * @return string
     */
    public function encodePoint(array $data, array $context = []): string
    {
        return $this->encodeCoordinate($data['coordinates'] ?? $data, $context);
    }

    /**
     * Encodes the coordinates of a linestring as well-known binary
     * @param array $data
     * @param array $context
     * @return string
     */
    public function encodeLineString(array $data, array $context = []): string
    {
        return $this->encodeCoordinateSequence($data['coordinates'], $context);
    }

    /**
     * Encodes the coordinates of a polygon as well-known binary
     * @param array $data
     * @param array $context
     * @return string
     */
    public function encodePolygon(array $data, array $context = []): string
    {
        $wkb = $this->packUInt32($context[Endianness::ENDIANNESS], count($data['coordinates'] ?? $data));
        foreach ($data['coordinates'] ?? $data as $linestring) {
            $wkb .= $this->encodeCoordinateSequence($linestring, $context);
        }
        return $wkb;
    }

    /**
     * Encodes the coordinates of a multipolygon as well-known binary
     * @param array $data
     * @param array $context
     * @return string
     */
    public function encodeMultiPoint(array $data, array $context = []): string
    {
        $wkb = $this->packUInt32($context[Endianness::ENDIANNESS], count($data['coordinates']));
        foreach ($data['coordinates'] as $point) {
            $wkb .= $this->encodeNormalizedGeometry([
                'type' => Point::WKT_TYPE,
                'binary_type' => Point::WKB_TYPE,
                'coordinates' => $point,
                'crs' => &$data['crs']
                    ], $context);
        }
        return $wkb;
    }

    /**
     * Encodes the coordinates of a multilinestring as well-known binary
     * @param array $data
     * @param array $context
     * @return string
     */
    public function encodeMultiLineString(array $data, array $context = []): string
    {
        $wkb = $this->packUInt32($context[Endianness::ENDIANNESS], count($data['coordinates']));
        foreach ($data['coordinates'] as $linestring) {
            $wkb .= $this->encodeNormalizedGeometry([
                'type' => LineString::WKT_TYPE,
                'binary_type' => LineString::WKB_TYPE,
                'coordinates' => $linestring,
                'crs' => &$data['crs']
                    ], $context);
        }
        return $wkb;
    }

    /**
     * Encodes a the coordinates of a multipolygon as well-known binary
     * @param array $data
     * @param array $context
     * @return string
     */
    public function encodeMultiPolygon(array $data, array $context = []): string
    {
        $wkb = $this->packUInt32($context[Endianness::ENDIANNESS], count($data['coordinates']));
        foreach ($data['coordinates'] as $polygon) {
            $wkb .= $this->encodeNormalizedGeometry([
                'type' => Polygon::WKT_TYPE,
                'binary_type' => Polygon::WKB_TYPE,
                'coordinates' => $polygon,
                'crs' => &$data['crs']
                    ], $context);
        }
        return $wkb;
    }

    /**
     * Encodes the geometries of a geometry collection as well-known text.
     * 
     * @param array $data
     * @param array $context
     * @return string
     */
    public function encodeGeometryCollection(array $data, array $context = []): string
    {
        $wkb = $this->packUInt32($context[Endianness::ENDIANNESS], count($data['geometries']));
        foreach ($data['geometries'] as $geometry) {
            $wkb .= $this->encodeNormalizedGeometry($geometry, $context);
        }
        return $wkb;
    }

    /**
     * Pack an unsigned char
     * @param string $endianness
     * @return string
     */
    protected function encodeEndianness(string $endianness): string
    {
        return pack('C', (($endianness === Endianness::NDR) ? 1 : 0));
    }

    /**
     * Pack unsigned long (always 32 bit).
     * @param string $endianness
     * @param int $values
     * @return string
     */
    protected function packUInt32(string $endianness, int ...$values): string
    {
        $format = ($endianness === Endianness::NDR) ? 'V*' : 'N*';
        return pack($format, ...$values);
    }

    /**
     * Pack double using machine dependent size
     * @param string $endianness 
     * @param float ...$values
     * @return string
     */
    protected function packDouble(string $endianness, float ...$values): string
    {
        $format = ($endianness === Endianness::NDR) ? 'e*' : 'E*';
        return pack($format, ...$values);
    }

}
