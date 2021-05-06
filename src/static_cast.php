<?php

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

namespace Nasumilu\Spatial\Geometry;

use function is_numeric;
use function is_object;
use function get_class;
use function gettype;

/**
 * Casts a numeric value to an integer
 * 
 * @param mixed $value
 * @return int
 * @throws InvalidArgumentException
 */
function static_cast_int($value): int
{
    if(!is_numeric($value)) {
        throw new InvalidArgumentException("Value must be numeric, found %s!", is_object($value) ? get_class($value) : gettype($value));
    }
    return intval($value);
}