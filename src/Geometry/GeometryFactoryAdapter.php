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

/**
 * Description of GeometryFactoryAdapter
 *
 * @author mlucas
 */
class GeometryFactoryAdapter extends AbstractGeometryFactory {

    public function area(Polygonal $polygonal): float {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function boundary(Geometry $geometry): Geometry {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function buffer(Geometry $geometry, float $distance): Geometry {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function centroid(Polygonal $polygonal): Point {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function contains(Geometry $geometry, Geometry $other): bool {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function convexHull(Geometry $geometry): Geometry {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function crosses(Geometry $geometry, Geometry $other): bool {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function difference(Geometry $geometry, Geometry $other): Geometry {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function disjoint(Geometry $geometry, Geometry $other): bool {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function distance(Geometry $geometry, Geometry $other): float {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function envelope(Geometry $geometry): Geometry {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function equals(Geometry $geometry, Geometry $other): bool {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function intersection(Geometry $geometry, Geometry $other): Geometry {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function intersects(Geometry $geometry, Geometry $other): bool {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function isEmpty(Geometry $geometry): bool {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function isSimple(Geometry $geometry): bool {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function length(Lineal $lineal): float {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function locateAlong(Geometry $geometry, float $mValue): Geometry {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function locateBetween(Geometry $geometry, float $mStart, float $mEnd): Geometry {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function overlaps(Geometry $geometry, Geometry $other): bool {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function pointOnSurface(Polygonal $polygonal): Point {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function relate(Geometry $geometry, Geometry $other, string $matrix): bool {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function symDifference(Geometry $geometry, Geometry $other): Geometry {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function touches(Geometry $geometry, Geometry $other): bool {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function transform(Geometry $geometry, GeometryFactory $factory): Geometry {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function union(Geometry $geometry, Geometry $other): Geometry {
        throw new \RuntimeException("Not implemented in adapter!");
    }

    public function within(Geometry $geometry, Geometry $other): bool {
        throw new \RuntimeException("Not implemented in adapter!");
    }

}
