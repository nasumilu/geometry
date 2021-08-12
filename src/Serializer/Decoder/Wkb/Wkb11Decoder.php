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

namespace Nasumilu\Spatial\Serializer\Decoder\Wkb;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Nasumilu\Spatial\Serializer\Decoder\WkbDecoder;
use Nasumilu\Spatial\Geometry\GeometryCollection;
use InvalidArgumentException;
use function ctype_xdigit;
use function pack;
use function unpack;
use function array_merge;
use function array_filter;

/**
 * Wkb11Decoder
 * 
 * @link https://portal.ogc.org/files/?artifact_id=13227 Simple Feature Access - Part 1: Common Architecture
 */
class Wkb11Decoder implements DecoderInterface
{

    /** Well-Known Binary 1.1.0 format */
    public const FORMAT = 'wkb11';

    /** 
     * The input strings position
     * @var int
     */
    private int $position = 0;
    /**
     * The input string
     * @var string|null
     */
    private ?string $wkb = null;

    /**
     * {@inheritDoc}
     */
    public function decode(string $data, string $format, array $context = []): array
    {
        if (ctype_xdigit($data)) {
            $data = pack('H*', $data);
        }
        $this->setInput($data);
        $value = $this->decodeGeometry($context);
        $this->reset();
        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDecoding(string $format): bool
    {
        return strtolower($format) === static::FORMAT;
    }

    /**
     * Decode a normalized Geometry object
     * @param array $context
     * @return type
     * @throws InvalidArgumentException
     */
    protected function decodeGeometry(array &$context)
    {
        $endianness = $this->unpackEndianness();
        if (0 === $endianness) {
            $context[WkbDecoder::ENDIANNESS] = WkbDecoder::XDR;
        } else if (1 === $endianness) {
            $context[WkbDecoder::ENDIANNESS] = WkbDecoder::NDR;
        } else {
            throw new InvalidArgumentException("Unknown endianness expected 0 (XDR) or 1 (NDR) found, $endianness!");
        }
        $type = $this->decodeGeometryType($context);
        $key = (GeometryCollection::WKT_TYPE === $type) ? 'geometries' : 'coordinates';
        return array_filter(array_merge(['type' => $type], [$key => $this->{'decode' . $type}($context)], $context['crs'] ?? []));
    }

    /**
     * Decode the well-known binary geometry type
     * @param array $context
     * @return string
     */
    protected function decodeGeometryType(array &$context): string
    {
        $wkbType = $this->unpackUInt32($context[WkbDecoder::ENDIANNESS]);
        return WkbDecoder::WKB_TYPES[$wkbType];
    }

    /**
     * Decodes the well-known binary point coordinates
     * @param array $context
     * @return array
     */
    protected function decodePoint(array $context): array
    {
        return [$this->unpackDouble($context[WkbDecoder::ENDIANNESS]),
            $this->unpackDouble($context[WkbDecoder::ENDIANNESS])];
    }

    /**
     * Decodes the well-known binary linestring coordinates
     * @param array $context
     * @return array
     */
    protected function decodeLineString(array $context): array
    {
        $numPoints = $this->unpackUInt32($context[WkbDecoder::ENDIANNESS]);
        $points = [];
        for ($i = 0; $i < $numPoints; $i++) {
            $points[] = $this->decodePoint($context);
        }
        return $points;
    }

    /**
     * Decodes the well-known binary polygon coordinates
     * @param array $context
     * @return array
     */
    protected function decodePolygon(array $context): array
    {
        $numRings = $this->unpackUInt32($context[WkbDecoder::ENDIANNESS]);
        $linestrings = [];
        for ($i = 0; $i < $numRings; $i++) {
            $linestrings[] = $this->decodeLineString($context);
        }
        return $linestrings;
    }

    /**
     * Decodes the well-known binary multipoint coordinates
     * @param array $context
     * @return array
     */
    protected function decodeMultiPoint(array $context): array
    {
        $numPoints = $this->unpackUInt32($context[WkbDecoder::ENDIANNESS]);
        $points = [];
        for ($i = 0; $i < $numPoints; $i++) {
            $points[] = $this->decodeGeometry($context)['coordinates'];
        }
        return $points;
    }

    /**
     * Decodes the well-known binary multilinestring coordinates
     * @param array $context
     * @return array
     */
    protected function decodeMultiLineString(array $context): array
    {
        $numLineStrings = $this->unpackUInt32($context[WkbDecoder::ENDIANNESS]);
        $linestrings = [];
        for ($i = 0; $i < $numLineStrings; $i++) {
            $linestrings[] = $this->decodeGeometry($context)['coordinates'];
        }
        return $linestrings;
    }

    /**
     * Decodes the well-known binary multipolygon coordinates.
     * @param array $context
     * @return array
     */
    protected function decodeMultiPolygon(array $context): array
    {
        $numPolygons = $this->unpackUInt32($context[WkbDecoder::ENDIANNESS]);
        $polygons = [];
        for ($i = 0; $i < $numPolygons; $i++) {
            $polygons[] = $this->decodeGeometry($context)['coordinates'];
        }
        return $polygons;
    }
    
    /**
     * Decodes the well-known binary geometry collection geometries
     * @param array $context
     * @return array
     */
    protected function decodeGeometryCollection(array $context): array
    {
        $numGeometries = $this->unpackUInt32($context[WkbDecoder::ENDIANNESS]);
        $geometries = [];
        for($i = 0; $i < $numGeometries; $i++) {
            $geometries[] = $this->decodeGeometry($context);
        }
        return $geometries;
    }

    /**
     * Sets well-known binary string and resets to the beginning (position = 0)
     * @param string $wkb
     * @return void
     */
    protected function setInput(string $wkb): void
    {
        $this->reset();
        $this->wkb = $wkb;
    }

    /**
     * Sets the well-known binary to null and resets the position to the beginning
     * (position = 0)
     * @return void
     */
    protected function reset(): void
    {
        $this->wkb = null;
        $this->position = 0;
    }

    /**
     * Unpack the endianness and advances the position one byte
     * @return int
     */
    public function unpackEndianness(): int
    {
        $value = unpack('C', $this->wkb, $this->position)[1];
        $this->position += 1;
        return $value;
    }

    /**
     * Unpack an unsigned 32-bit integer and advances the position 
     * four bytes
     * 
     * @param string $endianness
     * @return int
     */
    public function unpackUInt32(string $endianness): int
    {
        $format = ($endianness === WkbDecoder::NDR) ? 'V' : 'N';
        $unpack = unpack($format, $this->wkb, $this->position);
        $this->position += 4;
        return $unpack[1];
    }

    /**
     * Unpack a double and advances the position eight bytes
     * 
     * @param string $endinannes
     * @return float
     */
    public function unpackDouble(string $endinannes): float
    {
        $format = ($endinannes === WkbDecoder::NDR) ? 'e' : 'E';
        $unpack = unpack($format, $this->wkb, $this->position)[1];
        $this->position += 8;
        return $unpack;
    }

}
