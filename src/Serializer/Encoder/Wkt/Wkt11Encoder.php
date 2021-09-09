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

namespace Nasumilu\Spatial\Serializer\Encoder\Wkt;

use Symfony\Component\Serializer\Encoder\EncoderInterface;
use function strtoupper;
use function strtolower;
use function rtrim;

/**
 * Well-known text version 1.1.0 encoder.
 * 
 * @link https://portal.ogc.org/files/?artifact_id=13227 OpenGIS&reg; Part1: Common architecture
 */
class Wkt11Encoder implements EncoderInterface
{

    /** Well-Known Text v1.1.0 format */
    public const FORMAT = 'wkt11';

    /**
     * {@inheritDoc}
     */
    public function encode($data, string $format, array $context = array()): string
    {
        return $this->encodeNormalizedGeometry($data, $context);
    }

    /**
     * {@inheritDoc}
     */
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
        $wkt = $this->encodeGeometryType($data);
        if (!isset($data['coordinates']) && !isset($data['geometries'])) {
            return "$wkt EMPTY";
        }
        $wkt .= '(' . call_user_func([$this, "encode{$data['type']}"], $data, $context) . ')';
        return $wkt;
    }

    /**
     * Encodes a geometry type as well-known text
     * 
     * @param array $data
     * @return string
     */
    protected function encodeGeometryType(array $data): string
    {
        return strtoupper($data['type']);
    }

    /**
     * Encodes a single coordinate as well-known text
     * 
     * @param float[] $values
     * @return string
     */
    protected function encodeCoordinate(array $values, array $context = []): string
    {
        return "{$values[0]} {$values[1]}";
    }

    /**
     * Encodes a coordinate sequence as well-known text
     * 
     * @param array $values
     * @return string
     */
    protected function encodeCoordinateSequence(array $values, array $context = []): string
    {
        $wkt = '';
        foreach ($values as $value) {
            $wkt .= $this->encodeCoordinate($value, $context) . ',';
        }
        return rtrim($wkt, ',');
    }

    /**
     * Encodes the coordinates of a point as well-known text
     * 
     * @param array $data
     * @param array $context
     * @return string
     */
    public function encodePoint(array $data, array $context = []): string
    {
        return $this->encodeCoordinate($data['coordinates'], $context);
    }

    /**
     * Encodes the coordinates of a linestring as well-known text
     * @param array $data
     * @param array $context
     * @return string
     */
    public function encodeLineString(array $data, array $context = []): string
    {
        return $this->encodeCoordinateSequence($data['coordinates'], $context);
    }

    /**
     * Encodes the coordinates of a polygon as well-known text
     * @param array $data
     * @param array $context
     * @return string
     */
    public function encodePolygon(array $data, array $context = []): string
    {
        $wkt = '';
        foreach ($data['coordinates'] ?? $data as $linestring) {
            $wkt .= '(' . $this->encodeCoordinateSequence($linestring, $context) . '),';
        }
        return rtrim($wkt, ',');
    }

    /**
     * Encodes the coordinates of a multipolygon as well-known text
     * @param array $data
     * @param array $context
     * @return string
     */
    public function encodeMultiPoint(array $data, array $context = []): string
    {
        return $this->encodeCoordinateSequence($data['coordinates'], $context);
    }

    /**
     * Encodes the coordinates of a multilinestring as well-known text
     * @param array $data
     * @param array $context
     * @return string
     */
    public function encodeMultiLineString(array $data, array $context = []): string
    {
        $wkt = '';
        foreach ($data['coordinates'] as $linestring) {
            $wkt .= '(' . $this->encodeCoordinateSequence($linestring, $context) . '),';
        }
        return rtrim($wkt, ',');
    }

    /**
     * Encodes a the coordinates of a multipolygon as well-known text
     * @param array $data
     * @param array $context
     * @return string
     */
    public function encodeMultiPolygon(array $data, array $context = []): string
    {
        $wkt = '';
        foreach ($data['coordinates'] as $polygon) {
            $wkt .= '(' . $this->encodePolygon($polygon, $context) . '),';
        }
        return rtrim($wkt, ',');
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
        $wkt = '';
        foreach ($data['geometries'] as $geometry) {
            $wkt .= $this->encodeNormalizedGeometry($geometry, $context) . ',';
        }
        return rtrim($wkt, ',');
    }

}
