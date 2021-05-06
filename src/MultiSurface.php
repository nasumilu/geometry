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
 * MultiSurface 2-dimensional GoemetryCollection whose elements are Surfaces.
 * 
 * @link https://www.ogc.org/standards/sfa Simple Feature Access - Part 1: Common Architecture
 */
class MultiSurface extends GeometryCollection
{
    
    public function __construct(GeometryFactory $factory, Surface ...$surface)
    {
        parent::__construct($factory, ...$surface);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getDimension(): int
    {
        return parent::getDimension();
    }
    
}
