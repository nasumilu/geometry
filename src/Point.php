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

use function current;
use function reset;
use function key;
use function next;
use function intval;
use function array_key_exists;
use function is_nan;

/**
 * A Point is a 0-dimensional geometric object and represents a single location 
 * in coordinate space.
 * 
 * @link https://www.ogc.org/standards/sfa Simple Feature Access - Part 1: Common Architecture
 */
class Point extends Geometry implements Coordinate
{

    /** The well-known text type value */
    public const WKT_TYPE = 'point';

    /** The well-known binary type value */
    public const WKB_TYPE = 1;

    /**
     * The point's coordinate values.
     *
     * @var float[]
     */
    private $coordinates = [];

    /**
     * Constructs a Point from an array of float values.
     *
     * @param GeometryFactory $geometryFactory
     * @param array $coordinates
     */
    public function __construct(GeometryFactory $geometryFactory, array $coordinates = [])
    {
        parent::__construct($geometryFactory);
        $precision = $geometryFactory->getPrecisionModel();
        $this->coordinates[self::X] = $precision
                ->makePrecise($coordinates[self::X] ?? $coordinates['x'] ?? NAN);
        $this->coordinates[self::Y] = $precision
                ->makePrecise($coordinates[self::Y] ?? $coordinates['y'] ?? NAN);

        //set z-coordinate
        $cs = $geometryFactory->getCoordianteSystem();
        $is3d = $cs->is3D();
        $isMeasured = $cs->isMeasured();
        if ($is3d) {
            $this->coordinates[self::Z] = $precision
                    ->makePrecise($coordinates[self::Z] ?? $coordinates['z'] ?? NAN);
        }
        if ($isMeasured) {
            $this->coordinates[self::M] = $precision->makePrecise(
                    ($coordinates[self::M] ?? $coordinates['m'] ?? null) ?? (!$is3d ? $coordinates[self::Z] ?? null : null) ?? NAN);
        }
    }

    /**
     * Gets an ordinate value
     * @param int $ordinate the ordinate offset
     * @return float the ordinate value
     * @throws CoordinateException when the ordinate is not supported
     */
    public function getOrdinate(int $ordinate): float
    {
        if (null === $value = $this->coordinates[$ordinate] ?? null) {
            throw CoordinateException::ordinateNotSupported($ordinate);
        }
        return $value;
    }

    /**
     * Sets an ordinate value
     * @param int $ordinate the ordinate offset
     * @param float $value the ordinate value to set
     * @throws CoordinateException when the ordinate is not supported
     */
    public function setOrdinate(int $ordinate, float $value): void
    {
        if (!$this->hasOrdinate($ordinate)) {
            throw CoordinateException::ordinateNotSupported($ordinate);
        }
        $this->coordinates[$ordinate] = $this->factory
                ->getPrecisionModel()
                ->makePrecise($value);
    }

    /**
     * Indicates whether the Point has an ordinate
     * @param int $ordinate the ordinate offset to check
     * @return bool true if ordinate is supported; false otherwise
     */
    public function hasOrdinate(int $ordinate): bool
    {
        return array_key_exists($ordinate, $this->coordinates);
    }

    // Utility method to convert an offset to an integer
    private function ordinate($offset): int
    {
        if(!is_numeric($offset)) {
            $offset = self::ORDIANTES[$offset];
        }
        return intval($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function current(): float
    {
        return current($this->coordinates);
    }

    /**
     * Gets the m-coordinate value
     * @return float the m-coordinate value
     * @throws CoordinateException if the m-coordinate is not supported
     */
    public function getM(): float
    {
        return $this->getOrdinate(self::M);
    }

    /**
     * Sets a m-coordinate value
     * @param float $m the m-coordinate value
     * @return Point
     * @throws CoordinateException if the m-coordinate is not supported
     */
    public function setM(float $m = NAN): self
    {
        $this->setOrdinate(self::M, $m);
        return $this;
    }

    /**
     * Gets the x-coordinate value
     * @return float the x-coordinate value
     */
    public function getX(): float
    {
        return $this->getOrdinate(self::X);
    }

    /**
     * Sets the x-coordinate value
     * @param float $x the x-coordinate value
     * @return Point
     */
    public function setX(float $x = NAN): self
    {
        $this->setOrdinate(self::X, $x);
        return $this;
    }

    /**
     * Gets the y-coordinate value
     * @return float the y-coordinate value
     */
    public function getY(): float
    {
        return $this->getOrdinate(self::Y);
    }

    /**
     * Sets the y-coordinate value
     * @param float $y
     * @return Point
     */
    public function setY(float $y = NAN): self
    {
        $this->setOrdinate(self::Y, $y);
        return $this;
    }

    /**
     * Gets the z-coordinate value
     * @return float the z-coordinate value
     * @throws CoordinateException if the z-coordinate is not supported
     */
    public function getZ(): float
    {
        return $this->getOrdinate(self::Z);
    }

    /**
     * Sets the z-coordinate value
     * @param float $z the z-coordinate value
     * @return Point
     * @throws CoordinateException if the z-coordinate is not supported
     */
    public function setZ(float $z = NAN): self
    {
        $this->setOrdinate(self::Z, $z);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function key(): int
    {
        return key($this->coordinates);
    }

    /**
     * {@inheritDoc}
     */
    public function next(): void
    {
        next($this->coordinates);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset): bool
    {
        return $this->hasOrdinate($this->ordinate($offset));
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset): float
    {
        return $this->getOrdinate($this->ordinate($offset));
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value): void
    {
        $this->setOrdinate($this->ordinate($offset), $value);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset): void
    {
        $ordinate = $this->ordinate($offset);
        if (!$this->hasOrdinate($ordinate)) {
            throw CoordinateException::ordinateNotSupported($ordinate);
        }
        $this->coordinates[$ordinate] = NAN;
    }

    /**
     * {@inheritDoc}
     */
    public function rewind(): void
    {
        reset($this->coordinates);
    }

    /**
     * {@inheritDoc}
     */
    public function valid(): bool
    {
        return null !== key($this->coordinates);
    }

    /**
     * {@inheritDoc}
     */
    public function __set($name, $value)
    {
        $this->setOrdinate($this->ordinate($name), $value);
    }

    /**
     * {@inheritDoc}
     */
    public function __get($name)
    {
        return $this->getOrdinate($this->ordinate($name));
    }

    /**
     * {@inheritDoc}
     */
    public function __isset($name)
    {
        return $this->hasOrdinate($this->ordinate($name));
    }
    
    public function __unset($name)
    {
        $this->offsetUnset($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getDimension(): int
    {
        return 0;
    }

    /**
     * {@inheritDoc}
     */
    public function getGeometryType(): string
    {
        return self::WKT_TYPE;
    }
    
    /**
     * {@inheritDoc}
     */
    public function isEmpty(): bool
    {
        return is_nan($this->coordinates[self::X]) 
            || is_nan($this->coordinates[self::Y]);
    }

}
