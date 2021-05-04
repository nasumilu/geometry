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

namespace Nasumilu\Spatial\Geometry;

use function \array_search;

class CoordinateException extends \InvalidArgumentException
{

    /**
     * Utility method for generating a CoordinateException when a <code>$ordinate</code>
     * is not supported.
     * @param int $ordinate
     * @return CoordinateException
     */
    public static function ordinateNotSupported(int $ordinate)
    {
        if (false === $key = array_search($ordinate, Coordinate::ORDIANTES, true)) {
            $key = $ordinate;
        }
        return new self(sprintf("The %s-coordinate is not "
                        . "supported!", $key));
    }

}
