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

use Symfony\Component\Serializer\SerializerInterface;
use Nasumilu\Spatial\Geometry\{
    GeometryFactory,
    Geometry
};

/**
 * SerializedGeometryBuilder builds Geometry object from well-known text and 
 * binary and extended well-known text and binary. 
 * 
 * @todo implement some format guessing from the builder argument
 */
class SerializeGeometryBuilder implements GeometryBuilder
{
    
    private SerializerInterface $serializer;
    
    /**
     * Constructs a GeometryBuilder which build Geometry object from wkt, wkb, 
     * ewkt, and ewkb
     * 
     * @param SerializerInterface $seralizer
     */
    public function __construct(SerializerInterface $seralizer)
    {
        $this->serializer = $seralizer;
    }
    
    /**
     * {@inheritDoc}
     */
    public function build(GeometryFactory $factory, $args): ?Geometry
    {
        if(!is_string($args)) {
            return null;
        }
        
        foreach(['wkt', 'wkb', 'ewkt', 'ewkb'] as $format) {
            try {
                return $this->serializer->deserialize($args, Geometry::class, $format, ['factory' => $factory]);
            } catch(\Exception $e) {
                // nothing here
            }
        }
        return null;
 
    }
    
}
