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

namespace Nasumilu\Spatial\Serializer;

use function in_array;
use function array_map;
use function strtolower;

/**
 * Description of EndiannessTrait
 */
trait EndiannessTrait
{
    
    private string $endianness = Endianness::NDR;
    
    public function getEndianness(): string
    {
        return $this->$this->endianness;
    }

    private function validateEndianness(string $endianness): bool
    {
        return in_array(strtolower($endianness),
                array_map('strtolower', [Endianness::NDR, Endianness::XDR]),
                true);
    }
    
    public function setEndianness(string $endianness = Endianness::NDR): void
    {
        $this->endianness($endianness);
    }
    
    private function endianness(string $endianness = Endianness::NDR): void
    {
        if(!$this->validateEndianness($endianness)) {
            throw new \InvalidArgumentException(sprintf('Endianness must be %s '
                    . 'or %s, found %!', Endianness::NDR, Endianness::XDR, $endianness));
        }
        $this->endianness = $endianness;
    }
}
