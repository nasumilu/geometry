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
class EwkbEncoder extends Wkb12Encoder
{
    /** Bitmask for z-coordinate */
    protected const EWKBZ = 0x80000000;
    /** Bitmask for m-coordinate */
    protected const EWKBM = 0x40000000;
    /** Bitmask for the srid value */ 
    protected const EWKB_SRID = 0x20000000;

    /** Extended well-known binary */
    public const FORMAT = 'ewkb';

    protected function encodeGeometryType(array $data, array $context = []): string
    {
        $type = $data['binary_type'];

        if ($data['crs']['3d']) {
            $type |= self::EWKBZ;
        }
        if ($data['crs']['measured']) {
            $type |= self::EWKBM;
        }

        if ((-1 !== $data['crs']['srid'] ?? -1)) {
            $type |= self::EWKB_SRID;
            return $this->packUInt32($context[WkbEncoder::ENDIANNESS], $type, $data['crs']['srid']);
        }
        return $this->packUInt32($context[WkbEncoder::ENDIANNESS], $type);
    }

}
