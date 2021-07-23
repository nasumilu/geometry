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

namespace Nasumilu\Spatial\Serializer\Encoder;

use function in_array;
use Nasumilu\Spatial\Serializer\Encoder\Wkb\{
    WkbWriter,
    WkbReader
};
use Symfony\Component\Serializer\Encoder\{
    EncoderInterface,
    DecoderInterface
};

/**
 * Description of WkbEncoder
 */
class WkbEncoder implements EncoderInterface, DecoderInterface
{

    public const BYTEORDER = 'byteorder';
    public const XDR = 0; //big-endian
    public const NDR = 1; //little-endian
    public const WKB = 'wkb';
    public const EWKB = 'ewkb';
    public const HEX_WKB = 'hex_wkb';
    public const HEX_EWKB = 'hex_ewkb';
    public const FORMATS = [
        self::WKB,
        self::EWKB,
        self::HEX_EWKB,
        self::HEX_WKB
    ];

    private $defaultByteOrder;

    public function __construct(?int $defaultByteOrder = null)
    {
        $this->defaultByteOrder = $defaultByteOrder ?? self::NDR;
    }

    public function encode($data, string $format, array $context = []): string
    {
        $writer = new WkbWriter($data, $context[self::BYTEORDER] ??  $this->defaultByteOrder);
        $wkb = $writer->write();
        if(stripos($format, 'hex_')) {
            return pack('H*', $wkb);
        }
        return $wkb;
    }

    public function supportsEncoding(string $format): bool
    {
        return in_array($format, self::FORMATS, true);
    }

    public function decode(string $data, string $format, array $context = []): mixed
    {
        $reader = new WkbReader($data);
        return $reader->read();
    }

    public function supportsDecoding(string $format): bool
    {
        return $this->supportsEncoding($format);
    }

   
}
