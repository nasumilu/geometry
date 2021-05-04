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

namespace Nasumilu\Spatial\Tests\Geometry;

use function \count;
use Nasumilu\Spatial\Geometry\{
    Point,
    LineString,
    AbstractGeometryFactory
};

/**
 * Description of LineStringTest
 */
class LineStringTest extends AbstractGeometryTest
{

    private static array $data;
    
    /**
     * @beforeClass
     */
    public static function setUpBeforeClass(): void
    {
        self::$data = require __DIR__ . '/Resources/php/linestring.php';
    }
    
    public function testConstructor()
    {
        $points = [];
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        foreach (self::$data['coordinates'] as $coordinate) {
            $points[] = $factory->createPoint($coordinate);
        }
        $linestring = new LineString($factory, ...$points);
        $this->assertInstanceOf(LineString::class, $linestring);
    }

    public function testGetPointN()
    {
        $points = [];
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        foreach (self::$data['coordinates'] as $coordinate) {
            $points[] = $factory->createPoint($coordinate);
        }
        $linestring = new LineString($factory, ...$points);
        foreach ($points as $offset => $point) {
            $this->assertSame($point, $linestring->getPointN($offset));
            $this->assertSame($point, $linestring[$offset]);
            $this->assertSame($point, $linestring->{$offset});
        }
        $invalidOffset = count($linestring) + 1;
        $this->expectException(\OutOfRangeException::class);
        $linestring[$invalidOffset];
    }

    public function testHasPointN()
    {
        $points = [];
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        foreach (self::$data['coordinates'] as $coordinate) {
            $points[] = $factory->createPoint($coordinate);
        }
        $linestring = new LineString($factory, ...$points);
        foreach ($points as $offset => $point) {
            $this->assertTrue($linestring->hasPointN($offset));
            $this->assertTrue(isset($linestring[$offset]));
            $this->assertTrue(isset($linestring->{$offset}));
        }
        $invalidOffset = count($linestring) + 1;
        $this->assertFalse($linestring->hasPointN($invalidOffset));
        $this->assertFalse(isset($linestring[$invalidOffset]));
        $this->assertFalse(isset($linestring->{$invalidOffset}));
    }

    public function testRemovePointN()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $linestring = new LineString($factory);
        foreach (self::$data['coordinates'] as $offset => $coordinate) {
            $linestring[$offset] = ['type' => 'point', 'coordinates' => $coordinate];
        }
        unset($linestring[$linestring->getNumPoints() - 1]);
        $this->assertCount(count(self::$data['coordinates']) - 1, $linestring);
        
        $this->assertInstanceOf(Point::class, $linestring->removePointN(count($linestring) - 1));
        $this->expectException(\OutOfRangeException::class);
        $linestring->removePointN(1000);
       
    }

    public function testSetPointN()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $linestring = new LineString($factory);
        foreach (self::$data['coordinates'] as $offset => $coordinate) {
            $linestring[$offset] = ['type' => 'point', 'coordinates' => $coordinate];
        }
        $linestring[] = require __DIR__ . '/Resources/php/point.php';
        $this->assertCount(count(self::$data['coordinates']) + 1, $linestring);
    }

    public function testNumPoints()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $linestring = new LineString($factory);
        $this->assertCount(0, $linestring);
        $this->assertEquals(count($linestring), $linestring->getNumPoints());
    }

    public function testGetDimension()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $linestring = new LineString($factory);
        $this->assertEquals(1, $linestring->getDimension());
    }

    public function testGetGeometryType()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $linestring = new LineString($factory);
        $this->assertEquals(LineString::WKT_TYPE, $linestring->getGeometryType());
    }

}
