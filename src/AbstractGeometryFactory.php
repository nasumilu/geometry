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

use function \array_unique;
use function \array_merge;
use function \array_search;

use Nasumilu\Spatial\Geometry\Builder\{
    CloneGeometryBuilder,
    ArrayGeometryBuilder,
    GeometryBuilder,
    GeometryBuilderRegistry
};

/**
 * AbstractGeometryFactory
 */
abstract class AbstractGeometryFactory implements GeometryFactory, GeometryBuilderRegistry, CoordinateSystem, SpatialEngine
{

    /** @var int */
    private int $srid;

    /** @var bool */
    private bool $is3D;

    /** @var bool */
    private bool $isMeasured;

    /** @var PrecisionModel */
    private PrecisionModel $precisionModel;

    /** @var GeometryBuilder[] */
    private array $builders = [];

    public function __construct(array $options = [])
    {
        $this->srid = intval($options['srid'] ?? -1);
        $this->is3D = boolval($options['is_3d'] ?? false);
        $this->isMeasured = boolval($options['is_measured'] ?? false);
        $this->precisionModel = $options['precision_model'] ?? new PrecisionModel();
        $builders = array_merge([
            new ArrayGeometryBuilder(),
            new CloneGeometryBuilder()]
                , (array) ($options['builders'] ?? []));
        
        $this->registerBuilder(...$builders);
    }

    /**
     * {@inheritDoc}
     */
    public function getPrecisionModel(): PrecisionModel
    {
        return $this->precisionModel;
    }

    /**
     * {@inheritDoc}
     */
    public function getCoordianteSystem(): CoordinateSystem
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getSpatialEngine(): SpatialEngine
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public final function registerBuilder(GeometryBuilder ...$builders)
    {
        $this->builders = array_unique(array_merge($this->builders, $builders), SORT_REGULAR);
    }

    /**
     * {@inheritDoc}
     */
    public final function unregisterBuilder(GeometryBuilder ...$builers)
    {
        foreach ($builers as $builder) {
            if (false !== $offset = array_search($builder, $this->builders)) {
                unset($this->builders[$offset]);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function coordinateDimension(): int
    {
        $dimension = 2;
        if ($this->is3D) {
            $dimension++;
        }
        if ($this->isMeasured) {
            $dimension++;
        }
        return $dimension;
    }

    /**
     * {@inheritDoc}
     */
    public function is3D(): bool
    {
        return $this->is3D;
    }

    /**
     * {@inheritDoc}
     */
    public function isMeasured(): bool
    {
        return $this->isMeasured;
    }

    /**
     * {@inheritDoc}
     */
    public function spatialDimension(): int
    {
        return ($this->is3D) ? 3 : 2;
    }

    /**
     * {@inheritDoc}
     */
    public function srid(): int
    {
        return $this->srid;
    }
    
    public function create($args): Geometry
    {
        $geometry = null;
        foreach($this->builders as $builder) {
            if(null !== $geometry = $builder->build($this, $args)) {
                return $geometry;
            }
        }
        throw new RuntimeException('Uable to build Geometry!');
    }

    /**
     * {@inheritDoc}
     */
    public function createPoint(array $coordinates): Point
    {
        return new Point($this, $coordinates);
    }

    public function createLineString(array $coordinates): LineString
    {
        $points = [];
        foreach ($coordinates as $coordinate) {
            $points[] = $this->createPoint($coordinate);
        }
        return new LineString($this, ...$points);
    }

}
