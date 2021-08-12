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

use Nasumilu\Spatial\Serializer\Encoder\WkbEncoder;

/**
 * Well-known binary version 1.1.0 encoder
 *
 * @link https://portal.ogc.org/files/?artifact_id=13227 OpenGIS&reg; Part1: Common architecture
 */
class Wkb12Encoder extends Wkb11Encoder
{
    
    /** Well-known binary 1.2.0 format */
    public const FORMAT = 'wkb';

    /**
     * Encodes a single coordinate as well-known binary
     *
     * @param float[] $values
     * @return string
     */
    protected function encodeCoordinate(array $values, array $context = []): string
    {
        return $this->packDouble($context[WkbEncoder::ENDIANNESS], ...$values);
    }

    /**
     * {@inheritDoc}
     */
    protected function encodeGeometryType(array $data, array $context = []): string
    {
        $type = $data['binary_type'];
        if($data['crs']['3d']) {
            $type += 1000;
        }
        if($data['crs']['measured']) {
            $type += 2000;
        }
        return $this->packUInt32($context[WkbEncoder::ENDIANNESS], $type);
    }

}
