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
 * Wkt12Decoder
 * 
 * @link https://portal.ogc.org/files/?artifact_id=25355 Simple Feature Access - Part 1: Common Architecture
 */
class Wkt12Decoder extends Wkt11Decoder
{

    /** Well-known text version 1.2.0 format */
    public const FORMAT = 'wkt';

    /** "Z", "M", or "ZM" character set type */
    protected const T_DIMENSION = 502;

    /** 
     * {@inheritDoc}
     */
    public function decodeGeometry(): array
    {
        $type = $this->decodeGeometryType();
        $dimension = $this->decodeDimension();
        $data = array_merge([
            'type' => $type,
            'crs' => $dimension
                ],
                $this->decodeCoordinates($type));
        return $data;
    }

    /**
     * Decodes a wkt geometry dimension.
     * @return array
     */
    protected function decodeDimension(): array
    {
        $dimension = [
            '3d' => false,
            'measured' => false
        ];
        if ($this->isNextToken(self::T_DIMENSION)) {
            $this->match(self::T_DIMENSION);
            $d = $this->token->value;
            $dimension['3d'] = false !== stripos($d, 'z');
            $dimension['measured'] = false !== stripos($d, 'm');
        }

        return $dimension;
    }

    /**
     * {@inheritDoc}
     */
    protected function getCatchablePatterns()
    {
        return array_merge(parent::getCatchablePatterns(),
                ['zm','z','m']);
    }

    /**
     * {@inheritDoc}
     */
    protected function getType(&$value)
    {
        switch (strtolower($value)) {
            case 'z':
            case 'm':
            case 'zm':
                return self::T_DIMENSION;
            default:
                return parent::getType($value);
        }
    }

}
