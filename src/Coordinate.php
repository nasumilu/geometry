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

declare(strict_types=1);

namespace Nasumilu\Spatial\Geometry;

/**
 * A simple class that represents a coordinate on a Cartesian plane.
 */
interface Coordinate extends \ArrayAccess, \Iterator
{

    /** The x-coordinate ordinate */
    public const X = 0;
    /** The y-coordinate ordinate */
    public const Y = 1;
    /** The z-coordinate ordinate */
    public const Z = 2;
    /** The m-coordinate ordinate */
    public const M = 3;
    
    /** Ordinate names mapped to ordinate offset */
    public const ORDIANTES = [
        'x' => self::X,
        'y' => self::Y,
        'z' => self::Z,
        'm' => self::M
    ];
    
    /**
     * Gets the x-coordinate value
     * @return float
     */
    public function getX(): float;
    /**
     * Gets the y-coordinate value
     * @return float
     */
    public function getY(): float;
    /**
     * Gets the z-coordinate value
     * @return float
     * @throws CoordinateException
     */
    public function getZ(): float;
    /**
     * Gets the m-coordinate value
     * @return float
     * @throws CoordinateException
     */
    public function getM(): float;

    /**
     * Indicates whether the coordinate is three-dimensional
     * 
     * @return bool
     */
    public function is3D(): bool;
    
    /**
     * Indicates whether the coordinate is measured
     * @return bool
     */
    public function isMeasured(): bool;
    
}
