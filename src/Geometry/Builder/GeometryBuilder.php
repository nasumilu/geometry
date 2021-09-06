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

namespace Nasumilu\Spatial\Geometry\Builder;

use Nasumilu\Spatial\Geometry\{
    Geometry,
    GeometryFactory
};

/**
 * Builds Geometry objects from mixed parameters
 */
interface GeometryBuilder
{

    /**
     * Build a Geometry object with <code>$args</code> or return null if unable
     * or not responsible for building a Geometry from the <code>$args</code>.
     * @param AbstractGeometryFactory $geometryFactory
     * @param mixed $args
     * @return Geometry|null
     */
    public function build(GeometryFactory $factory, $args): ?Geometry;
    
}
