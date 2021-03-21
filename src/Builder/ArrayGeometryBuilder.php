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

use function \is_array;
use Nasumilu\Spatial\Geometry\{
    Geometry,
    AbstractGeometryFactory
};

/**
 * ArrayGeometryBuilder builds a Geometry object from an array
 */
class ArrayGeometryBuilder implements GeometryBuilder
{

    /**
     * {@inheritDoc}
     */
    public function build(AbstractGeometryFactory $geometryFactory, $args): ?Geometry
    {
        if (!is_array($args) && !isset($args['type'])) {
            return null;
        }
        return call_user_func([$geometryFactory, 'create' . $args['type']],
                $args['coordinates'] ?? $args['geometries'] ?? []);
    }

}
