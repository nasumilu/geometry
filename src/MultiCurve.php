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
 * A MultiCurve is a 1-dimensional GeometryCollection whose elements
 * are Curves.
 * @link https://www.ogc.org/standards/sfa Simple Feature Access - Part 1: Common Architecture
 */
abstract class MultiCurve extends GeometryCollection implements Lineal
{

    /**
     * Constructs a MultiCurve with the GeometryFactory and set of LineString(s)
     *
     * @param GeometryFactory $factory
     * @param Curve ...$curves
     */
    public function __construct(GeometryFactory $factory, Curve ...$curves)
    {
        parent::__construct($factory, ...$curves);
    }

    /**
     * {@inheritDoc}
     */
    public function getLength(): float
    {
        return $this->factory->getSpatialEngine()->length($this);
    }

    /**
     * {@inheritDoc}
     */
    public function getDimension(): int
    {
        return 1;
    }

    /**
     * {@inheritDoc}
     */
    public function isClosed(): bool
    {
        foreach ($this->geometries as $curve) {
            if (!$curve->isClosed()) {
                return false;
            }
        }
        return true;
    }

}
