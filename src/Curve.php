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

use function \intval;

/**
 * A 1-dimensional geometric object usually stored as a sequence of Points.
 */
abstract class Curve extends Geometry implements \ArrayAccess, \Iterator, \Countable
{

    protected function __construct(GeometryFactory $factory)
    {
        parent::__construct($factory);
    }

    public abstract function getPointN(int $offset): Point;
    
    public abstract function setPointN($point, int $offset = null);
    
    public abstract function hasPointN(int $offset): bool;
    
    public abstract function removePointN(int $offset): void;
    
    public abstract function getNumPoints(): int;

    /**
     * Gets the start point of the Curve
     * @return Point|null
     */
    public function getStartPoint(): ?Point
    {
        return $this->hasPointN(0) ? $this->getPointN(0) : null;
    }

    /**
     * Gets the end point of the Curve
     * @return Point|null
     */
    public function getEndPoint(): ?Point
    {
        $offset = $this->getNumPoints() - 1;
        return $this->hasPointN($offset) ? $this->getPointN($offset) : null;
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
    public function count()
    {
        return $this->getNumPoints();
    }
    
    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value): void
    {
        $this->setPointN($value, $offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset): Point
    {
        return $this->getPointN($offset);
    }
    
    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset): bool
    {
        return $this->hasPointN($offset);
    }
    
    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset): void
    {
        $this->removePointN($offset);
    }
    
    public function __get($name)
    {
        return $this->getPointN(intval($name));
    }
    
    public function __set($name, $value) {
        $this->setPointN($value, intval($name));
    }
    
    public function __isset($name)
    {
        return $this->hasPointN(intval($name));
    }
}
