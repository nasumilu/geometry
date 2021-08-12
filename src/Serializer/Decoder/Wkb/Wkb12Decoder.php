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

/**
 * Wkb12Decoder 
 * 
 * @link https://portal.ogc.org/files/?artifact_id=25355 Simple Feature Access - Part 1: Common Architecture
 * 
 */
class Wkb12Decoder extends Wkb11Decoder
{

    /** Well-known binary 1.2.0 format*/
    public const FORMAT = 'wkb';

    /**
     * {@inheritDoc}
     */
    protected function decodeGeometryType(array &$context): string
    {
        $wkbType = $this->unpackUInt32($context[WkbDecoder::ENDIANNESS]);
        $type = WkbDecoder::WKB_TYPES[$wkbType % 1000];

        if (!isset($context['crs'])) {
            $context['crs'] = [
                '3d' => false,
                'measured' => false
            ];
        }
        $dimension = $wkbType - ($wkbType % 1000);
        $context['crs']['3d'] = $dimension === 1000 || $dimension === 3000;
        $context['crs']['measured'] = $dimension === 2000 || $dimension == 3000;
        return $type;
    }

    /**
     * {@inheritDoc}
     */
    protected function decodePoint(array $context): array
    {
        $wkb = [$this->unpackDouble($context[WkbDecoder::ENDIANNESS]),
            $this->unpackDouble($context[WkbDecoder::ENDIANNESS])];
        if($context['crs']['3d'] ?? false) {
            $wkb[] = $this->unpackDouble($context[WkbDecoder::ENDIANNESS]);
        }
        if($context['crs']['measured']) {
            $wkb[] =  $this->unpackDouble($context[WkbDecoder::ENDIANNESS]);
        }
        return $wkb;
    }

}
