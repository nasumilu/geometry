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

/**
 * Represent a CoordinateSystem 
 */
interface CoordinateSystem
{

    /**
     * Indicates whether the coordinate system is three-dimensional, has a 
     * z-coordinate.
     * @return bool
     */
    public function is3D(): bool;

    /**
     * Indicates whether the coordinate system is measured, has an m-coordinate.
     * @return bool
     */
    public function isMeasured(): bool;

    /**
     * Gets the coordinate systems spatial reference system id (SRID)
     * @return int
     */
    public function srid(): int;

    /**
     * Gets the coordinate dimension as: (x,y) = 2, (x,y,m) = 3, (x,y,z) = 3, 
     * (x,y,z,m) = 4.
     * @return int
     */
    public function coordinateDimension(): int;

    /**
     * Gets the spatial dimension as: (x,y) = 2, (x,y,m) = 2, (x,y,z) = 3, and
     * (x,y,z,m) = 3
     * @return int
     */
    public function spatialDimension(): int;
}
