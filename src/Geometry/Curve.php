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

use ArrayAccess;
use Iterator;
use Countable;

/**
 * A 1-dimensional geometric object usually stored as a sequence of Points.
 * 
 * @link https://www.ogc.org/standards/sfa Simple Feature Access - Part 1: Common Architecture
 */
abstract class Curve extends Geometry implements ArrayAccess, Iterator, Countable, Lineal
{

    /**
     * Gets the <i>n<sup>th</sup></i> Point
     * @param int $offset
     * @return Point;
     */
    public abstract function getPointN(int $offset): Point;

    /**
     * Sets the <i>n<sup>th</sup></i> Point
     * 
     * If the no offset is provided then the Point is pushed onto the 
     * the end, becoming the Curve's end point.
     * 
     * @param mixed $point
     * @param int $offset
     * @return Curve
     */
    public abstract function setPointN($point, ?int $offset = null): Curve;

    /**
     * Indicates whether the Curve contains a point a <code>$offset</code> 
     * position.
     * 
     * @param int $offset
     * @return bool
     */
    public abstract function hasPointN(int $offset): bool;

    /**
     * Removes a the Point found a <code>$offset</code>
     * 
     * @param int $offset
     * @return Point the removed Point
     */
    public abstract function removePointN(int $offset): Point;

    /**
     * Gets the number of points found in the Curve
     * 
     * @return int
     */
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
     * {@inheritDoc}
     */
    public function getLength(): float
    {
        if($this->count() < 2) {
            return 0.0;
        }
        return $this->factory->getSpatialEngine()->length($this);
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
        return self::DIMENSION;
    }

    /**
     * Indicates whether the Curve is closed.
     * 
     * A closed curve has a minimum of 4 points and the 
     * start point == end point
     * 
     * @return bool
     */
    public function isClosed(): bool
    {
        return $this->count() > 3 
                && $this->getStartPoint() == $this->getEndPoint();
    }
    
    /**
     * An empty Curve has less than two points. 
     * 
     * <strong>Note: This method does not check the Curve's set of Point.
     * Just that it contains 2 or more Point objects</strong>
     * 
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->count() < 2;
    }
    
    /**
     * @internal Countable::count implementation, delegates to
     * Curve::getNumPoints
     * {@inheritDoc}
     */
    public function count(): int
    {
        return $this->getNumPoints();
    }

    /**
     * @internal ArrayAccess::offsetSet implementation, delegates to
     * Curve::setPointN
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value): void
    {
        $this->setPointN($value, $offset);
    }

    /**
     * @internal ArrayAccess::offsetGet implementation, delegates to 
     * Curve::offsetGet
     * {@inheritDoc}
     */
    public function offsetGet($offset): Point
    {
        return $this->getPointN($offset);
    }

    /**
     * @internal ArrayAccess::offsetExists implementation, delegates to 
     * Curve::hasPointN
     * {@inheritDoc}
     */
    public function offsetExists($offset): bool
    {
        return $this->hasPointN($offset);
    }

    /**
     * @internal ArrayAccess::offsetUnset implementation delegates to 
     * Curve::removePointN
     * {@inheritDoc}
     */
    public function offsetUnset($offset): void
    {
        $this->removePointN($offset);
    }

    /**
     * @internal magic method __get, delegates to Curve::getPointN
     * {@inheritDoc}
     */
    public function __get($name): Point
    {
        return $this->getPointN((int) $name);
    }

    /**
     * @internal magic method __set, delegates to Curve::setPointN
     * {@inheritDoc}
     */
    public function __set($name, $value): void
    {
        $this->setPointN($value, (int) $name);
    }

    /**
     * @internal magic method __isset so a Curve object will work with some
     * of the existing array_** functions; delegates to Curve::hasPointN
     * {@inheritDoc}
     */
    public function __isset($name): bool
    {
        return $this->hasPointN((int) $name);
    }

    public function __unset($name): void
    {

        $this->removePointN((int) $name);
    }

}
