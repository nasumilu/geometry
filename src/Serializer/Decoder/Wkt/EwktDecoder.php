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

/**
 * Description of EwktDecoder
 */
class EwktDecoder extends Wkt12Decoder
{

    /** Extended Well-Known Text format */
    public const FORMAT = 'ewkt';

    /** Equals type */
    protected const T_EQUALS = 11;

    /** Semicolon type */
    protected const T_SEMICOLON = 50;

    /** "SRID" character set type */
    protected const T_SRID = 501;

    /** 
     * {@inheritDoc}
     */
    public function decodeGeometry(): array
    {
        $srid = $this->decodeSrid();
        $type = $this->decodeGeometryType();
        $dimension = $this->decodeDimension();
        $data = array_merge([
            'type' => $type,
            'crs' => array_merge([
                'srid' => $srid,
                    ], $dimension)
                ],
                $this->decodeCoordinates($type));
        return $data;
    }

    /**
     * Decodes a ewkt geometry srid
     * @return int
     */
    private function decodeSrid(): int
    {
        $srid = -1;
        if ($this->isNextToken(self::T_SRID)) {
            $this->match(self::T_SRID);
            $this->match(self::T_EQUALS);
            $this->match(self::T_NUMERIC);
            $srid = (int) $this->token->value;
            $this->match(self::T_SEMICOLON);
        }
        return $srid;
    }

    /**
     * {@inheritDoc}
     */
    protected function getCatchablePatterns()
    {
        return array_merge(parent::getCatchablePatterns(),
                ['SRID|=|;']);
    }

    /**
     * {@inheritDoc}
     */
    protected function getType(&$value)
    {
        switch (strtolower($value)) {
            case '=':
                return self::T_EQUALS;
            case ';':
                return self::T_SEMICOLON;
            case 'srid':
                return self::T_SRID;
            default:
                return parent::getType($value);
        }
    }

}
