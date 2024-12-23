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

namespace Nasumilu\Spatial\Serializer\Decoder\Wkt;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Doctrine\Common\Lexer\AbstractLexer;
use Nasumilu\Spatial\Geometry\GeometryCollection;
use UnexpectedValueException;
use function strtolower;

/**
 * Wkt11Decoder
 * 
 * @link https://portal.ogc.org/files/?artifact_id=13227 Simple Feature Access - Part 1: Common Architecture
 */
class Wkt11Decoder extends AbstractLexer implements DecoderInterface
{

    /** None or Unknown type */
    protected const T_NONE = 1;

    /** Numeric type (float or integer) */
    protected const T_NUMERIC = 2;

    /** Closed parenthesis type */
    protected const T_CLOSE_PARENTHESIS = 6;

    /** Open parenthesis type */
    protected const T_OPEN_PARENTHESIS = 7;

    /** Comma type */
    protected const T_COMMA = 8;

    /** "EMPTY" character set type */
    protected const T_EMPTY = 500;

    /** Wkt geometry type character set type (point, linestring ... geometrycollection) */
    protected const T_GEOMETRY_TYPE = 600;

    /** Wkt version 1.1.0 format */
    public const FORMAT = 'wkt11';

    /**
     * {@inheritDoc}
     */
    public function decode(string $data, string $format, array $context = []): array
    {
        try {
            $this->setInput($data);
            $this->moveNext();
            $geometry = $this->decodeGeometry();
            $this->reset();
            return $geometry;
        } catch (\Exception $ex) {
            $this->reset();
            throw $ex;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDecoding(string $format): bool
    {
        return strtolower($format) === static::FORMAT;
    }

    /**
     * Decode the normalized well-known text
     * @return array
     */
    public function decodeGeometry(): array
    {
        $type = $this->decodeGeometryType();
        $data = array_merge(['type' => $type], $this->decodeCoordinates($type));
        return $data;
    }

    /**
     * Decodes a wkt geometry type
     * @return string
     */
    protected function decodeGeometryType(): string
    {
        $this->match(self::T_GEOMETRY_TYPE);
        return $this->token->value;
    }

    /**
     * Decodes a wkt coordinate
     * @return array
     */
    protected function decodeCoordinate(): array
    {
        while ($this->isNextToken(self::T_NUMERIC)) {
            $this->match($this->lookahead->type);
            $value = (float) $this->token->value;
            $values[] = $value;
        }
        return $values;
    }

    /**
     * Decodes a wkt coordinate sequence
     * @return array
     */
    protected function decodeCoordinateSeq(): array
    {
        $values = [$this->decodeCoordinate()];
        while ($this->isNextToken(self::T_COMMA)) {
            $this->match(self::T_COMMA);
            $values[] = $this->decodeCoordinate();
        }
        return $values;
    }

    /**
     * Decodes a wkt geometry coordinates
     * @param string $type
     * @return array
     */
    protected function decodeCoordinates(string $type): array
    {
        $data = [];
        $key = strtolower($type) === GeometryCollection::WKT_TYPE ? 'geometries' : 'coordinates';
        if (!$this->isNextToken(self::T_EMPTY)) {
            $data[$key] = $this->{'decode' . $type}();
        } else {
            $this->match(self::T_EMPTY);
            $data[$key] = [];
        }
        return $data;
    }

    /**
     * Decodes a wkt point coordinates
     * @return array
     */
    protected function decodePoint(): array
    {
        $this->match(self::T_OPEN_PARENTHESIS);
        $coordinates = $this->decodeCoordinate();
        $this->match(self::T_CLOSE_PARENTHESIS);
        return $coordinates;
    }

    /**
     * Decodes a wkt linestring coordinates
     * @return array
     */
    protected function decodeLineString(): array
    {
        $this->match(self::T_OPEN_PARENTHESIS);
        $coordinates = $this->decodeCoordinateSeq();
        $this->match(self::T_CLOSE_PARENTHESIS);
        return $coordinates;
    }

    /**
     * Decodes a wkt polygon coordinates
     * @return array
     */
    protected function decodePolygon(): array
    {
        $coordiantes = [];
        $this->match(self::T_OPEN_PARENTHESIS);
        $coordinates[] = $this->decodeLineString();
        while ($this->isNextToken(self::T_COMMA)) {
            $this->match(self::T_COMMA);
            $coordinates[] = $this->decodeLineString();
        }
        $this->match(self::T_CLOSE_PARENTHESIS);
        return $coordinates;
    }

    /**
     * Decodes a wkt multipoint coordinates
     * @return array
     */
    protected function decodeMultiPoint(): array
    {

        $this->match(self::T_OPEN_PARENTHESIS);
        $coordinates = $this->decodeCoordinateSeq();
        $this->match(self::T_CLOSE_PARENTHESIS);
        return $coordinates;
    }

    /**
     * Decodes a wkt multilinestring coordinates
     * @return array
     */
    protected function decodeMultiLineString(): array
    {
        $coordiantes = [];
        $this->match(self::T_OPEN_PARENTHESIS);
        $coordinates[] = $this->decodeLineString();
        while ($this->isNextToken(self::T_COMMA)) {
            $this->match(self::T_COMMA);
            $coordinates[] = $this->decodeLineString();
        }
        $this->match(self::T_CLOSE_PARENTHESIS);
        return $coordinates;
    }

    /**
     * Decodes a wkt multilinestring coordinates
     * @return array
     */
    protected function decodeMultiPolygon(): array
    {
        $coordiantes = [];
        $this->match(self::T_OPEN_PARENTHESIS);
        $coordinates[] = $this->decodePolygon();
        while ($this->isNextToken(self::T_COMMA)) {
            $this->match(self::T_COMMA);
            $coordinates[] = $this->decodePolygon();
        }
        $this->match(self::T_CLOSE_PARENTHESIS);
        return $coordinates;
    }

    /**
     * Decode a wkt geometrycollection geometries.
     * @return array
     */
    protected function decodeGeometryCollection(): array
    {
        $geometries = [];
        $this->match(self::T_OPEN_PARENTHESIS);
        $geometries[] = $this->decodeGeometry();
        while ($this->isNextToken(self::T_COMMA)) {
            $this->match(self::T_COMMA);
            $geometries[] = $this->decodeGeometry();
        }
        $this->match(self::T_CLOSE_PARENTHESIS);
        return $geometries;
    }

    /**
     * {@inheritDoc}
     */
    protected function getCatchablePatterns()
    {
        return [
            'point|linestring|polygon|multipoint|multilinestring|multipolygon|geometrycollection',
            'empty|(|)|,',
            '[+-]?[0-9]+(?:[\.][0-9]+)?(?:e[+-]?[0-9]+)?'
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function getNonCatchablePatterns()
    {
        return ['\s+'];
    }

    protected function getType(&$value)
    {
        if (is_numeric($value)) {
            return self::T_NUMERIC;
        }
        switch (strtolower($value)) {
            case ',':
                return self::T_COMMA;
            case '(':
                return self::T_OPEN_PARENTHESIS;
            case ')':
                return self::T_CLOSE_PARENTHESIS;
            case 'point':
            case 'linestring':
            case 'polygon':
            case 'multipoint':
            case 'multilinestring':
            case 'multipolygon':
            case 'geometrycollection':
                return self::T_GEOMETRY_TYPE;
            case 'empty':
                return self::T_EMPTY;
            default:
                return self::T_NONE;
        }
    }

    /**
     * Matches a token and move to the next
     * 
     * @todo Needs better error reporting when a match fails; The current exception
     * argument is ambiguous.
     * @param int $token
     * @throws \Exception
     */
    protected function match($token)
    {
        $lookaheadType = $this->lookahead->type;
        if ($lookaheadType !== $token) {
            throw new UnexpectedValueException('Syntax error near ' . $this->getLiteral($token) . print_r($this->peek(), true));
        }
        $this->moveNext();
    }

}
