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

use Nasumilu\Spatial\Serializer\Endianness;
use Nasumilu\Spatial\Serializer\Decoder\WkbDecoder;

/**
 * EwkbDecoder
 */
class EwkbDecoder extends Wkb12Decoder
{

    /** Bitmask for z-coordinate */
    protected const EWKBZ = 0x80000000;

    /** Bitmask for m-coordinate */
    protected const EWKBM = 0x40000000;

    /** Bitmask for the srid value */
    protected const EWKB_SRID = 0x20000000;
    
    /** Extended Well-Known Binary format */
    public const FORMAT = 'ewkb';

    /**
     * {@inheritDoc}
     */
    protected function decodeGeometryType(array &$context): string
    {
        $wkbType = $this->unpackUInt32($context[Endianness::ENDIANNESS]);
        $type = WkbDecoder::WKB_TYPES[$wkbType & 0xFF];
        if (!isset($context['crs'])) {
            $context['crs'] = [
                'srid' => -1,
                '3d' => false,
                'measured' => false
            ];
        }

        if(($wkbType & self::EWKB_SRID) === self::EWKB_SRID) {
            $context['crs']['srid'] = $this->unpackUInt32($context[Endianness::ENDIANNESS]);
        }
        $context['crs']['3d'] = ($wkbType & self::EWKBZ) === self::EWKBZ;
        $context['crs']['measured'] = ($wkbType & self::EWKBM) === self::EWKBM;

        return $type;
    }

}
