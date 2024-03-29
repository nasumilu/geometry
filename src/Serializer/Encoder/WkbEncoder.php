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

use Symfony\Component\Serializer\Encoder\ChainEncoder;
use Nasumilu\Spatial\Serializer\Endianness;

/**
 * Description of WkbEncoder
 */
class WkbEncoder extends ChainEncoder
{
 
    public const HEX_STR = 'hex_str';
    
    public function __construct(string $endianness = Endianness::NDR)
    {
        parent::__construct([
            new Wkb\Wkb11Encoder($endianness),
            new Wkb\Wkb12Encoder($endianness),
            new Wkb\EwkbEncoder($endianness)
        ]);
    }


}
