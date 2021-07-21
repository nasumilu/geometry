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

use function count;
use Nasumilu\Spatial\Geometry\{
    Point,
    LineString,
    AbstractGeometryFactory
};

/**
 * @covers \Nasumilu\Spatial\Geometry\LineString
 * @covers \Nasumilu\Spatial\Geometry\Curve
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

    /**
     * Test the Linestring::__constructor
     * @test
     * @covers \Nasumilu\Spatial\Geometry\LineString::__construct
     * @covers \Nasumilu\Spatial\Geometry\Geometry::is3D
     * @covers \Nasumilu\Spatial\Geometry\Geometry::isMeasured
     * @covers \Nasumilu\Spatial\Geometry\AbstractGeometryFactory::createPoint
     * @dataProvider factoryOptions
     * @param array $options
     */
    public function testConstructor(array $options)
    {
        $points = [];
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class, [$options]);
        foreach (self::$data['coordinates'] as $coordinate) {
            $points[] = $factory->createPoint($coordinate);
        }
        $linestring = new LineString($factory, ...$points);
        $this->assertInstanceOf(LineString::class, $linestring);
        if ($options['3d']) {
            $this->assertTrue($linestring->is3D());
        } else {
            $this->assertFalse($linestring->is3D());
        }

        if ($options['measured']) {
            $this->assertTrue($linestring->isMeasured());
        } else {
            $this->assertFalse($linestring->isMeasured());
        }
    }

    /**
     * Tests the Iterator implementation
     * @test
     * @covers \Nasumilu\Spatial\Geometry\LineString::valid
     * @covers \Nasumilu\Spatial\Geometry\LineString::next
     * @covers \Nasumilu\Spatial\Geometry\LineString::rewind
     * @covers \Nasumilu\Spatial\Geometry\LineString::key
     * @covers \Nasumilu\Spatial\Geometry\LineString::current
     * @covers \Nasumilu\Spatial\Geometry\AbstractGeometryFactory::create
     * 
     * @dataProvider factoryOptions
     * @param array $options
     */
    public function testIterator(array $options)
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class, [$options]);
        $linestring = $factory->create(self::$data);
        while ($linestring->valid()) {
            $this->assertInstanceOf(Point::class, $linestring->current());
            $linestring->next();
        }
        $linestring->rewind();
        $this->assertTrue($linestring->valid());
        $this->assertEquals(0, $linestring->key());
    }

    /**
     * Test LineString::getPointN
     * @test
     * @covers \Nasumilu\Spatial\Geometry\LineString::getPointN
     * @covers \Nasumilu\Spatial\Geometry\Curve::offsetGet
     * @covers \Nasumilu\Spatial\Geometry\Curve::__get
     * @covers \Nasumilu\Spatial\Geometry\AbstractGeometryFactory::createPoint
     */
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

    /**
     * Tests LineString::hasPoint
     * @covers \Nasumilu\Spatial\Geometry\LineString::hasPointN
     * @covers \Nasumilu\Spatial\Geometry\LineString::offsetExists
     * @covers \Nasumilu\Spatial\Geometry\LineString::__isset
     */
    public function testHasPointN()
    {
        $points = [];
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        foreach (self::$data['coordinates'] as $coordinate) {
            $points[] = $factory->createPoint($coordinate);
        }
        $linestring = new LineString($factory, ...$points);
        foreach ($points as $offset => $point) {
            $this->assertInstanceOf(Point::class, $point);
            $this->assertTrue($linestring->hasPointN($offset));
            $this->assertTrue(isset($linestring[$offset]));
            $this->assertTrue($linestring->offsetExists($offset));
            $this->assertTrue(isset($linestring->{$offset}));
            $this->assertTrue($linestring->__isset($offset));
        }
        $invalidOffset = count($linestring) + 1;
        $this->assertFalse($linestring->hasPointN($invalidOffset));
        $this->assertFalse(isset($linestring[$invalidOffset]));
        $this->assertFalse($linestring->offsetExists($invalidOffset));
        $this->assertFalse(isset($linestring->{$invalidOffset}));
        $this->assertFalse($linestring->__isset($invalidOffset));
    }

    /**
     * Test LineString::removePointN
     * @test
     * @covers \Nasumilu\Spatial\Geometry\LineString::removePointN
     * @covers \Nasumilu\Spatial\Geometry\LineString::getNumPoints
     * @covers \Nasumilu\Spatial\Geometry\Curve::count
     * @covers \Nasumilu\Spatial\Geometry\Curve::__unset
     * @covers \Nasumilu\Spatial\Geometry\Curve::offsetUnset
     */
    public function testRemovePointN()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $linestring = new LineString($factory);
        foreach (self::$data['coordinates'] as $offset => $coordinate) {
            $linestring[$offset] = ['type' => 'point', 'coordinates' => $coordinate];
        }
        unset($linestring[$linestring->getNumPoints() - 1]);
        $this->assertCount(count(self::$data['coordinates']) - 1, $linestring);
        $linestring->__unset($linestring->getNumPoints() - 1);
        $this->assertCount(count(self::$data['coordinates']) - 2, $linestring);
        $linestring->offsetUnset($linestring->getNumPoints() - 1);
        $this->assertCount(count(self::$data['coordinates']) - 3, $linestring);
        $this->expectException(\OutOfRangeException::class);
        $this->assertInstanceOf(Point::class, $linestring->removePointN(count($linestring) - 1));
    }

    /**
     * Test LineString::setPointN
     * @test
     * @covers \Nasumilu\Spatial\Geometry\LineString::setPointN
     * @covers \Nasumilu\Spatial\Geometry\LineString::getNumPoints
     * @covers \Nasumilu\Spatial\Geometry\Curve::count
     * @covers \Nasumilu\Spatial\Geometry\Curve::__set
     * @covers \Nasumilu\Spatial\Geometry\Curve::offsetSet
     */
    public function testSetPointN()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $linestring = new LineString($factory);
        foreach (self::$data['coordinates'] as $offset => $coordinate) {
            $linestring[$offset] = ['type' => 'point', 'coordinates' => $coordinate];
        }
        $linestring[] = require __DIR__ . '/Resources/php/point.php';
        $this->assertCount(count(self::$data['coordinates']) + 1, $linestring);
        $linestring->__set($linestring->getNumPoints(), require __DIR__ . '/Resources/php/point.php');
        $this->assertCount($linestring->getNumPoints(), $linestring);
        $this->{$linestring->getNumPoints()} = require __DIR__ . '/Resources/php/point.php';
        $this->assertCount($linestring->getNumPoints(), $linestring);
    }

    /**
     * Test the LineString::getDimension
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::getDimension
     */
    public function testGetDimension()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $linestring = new LineString($factory);
        $this->assertEquals(1, $linestring->getDimension());
    }

    /**
     * Test the LineString::getGeometryType
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::getGeometryType
     */
    public function testGetGeometryType()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $linestring = new LineString($factory);
        $this->assertEquals(LineString::WKT_TYPE, $linestring->getGeometryType());
    }

    /**
     * Test the LineString::getStartPoint & LineString::getEndPoint
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Curve::getStartPoint
     * @covers \Nasumilu\Spatial\Geometry\Curve::getEndPoint
     */
    public function testSartPoint()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $linestring = new LineString($factory);
        $linestring[] = require __DIR__ . '/Resources/php/point.php';
        $this->assertSame($linestring[0], $linestring->getStartPoint());
        $this->assertSame($linestring->getStartPoint(), $linestring->getEndPoint());

        $linestring->setPointN(require __DIR__ . '/Resources/php/point.php');
        $this->assertNotSame($linestring->getEndPoint(), $linestring->getStartPoint());
    }

    /**
     * Test the LineString::getLength
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Curve::getLength
     */
    public function testGetLength()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);

        $linestring = new LineString($factory);
        $this->assertEquals(0, $linestring->getLength());
        $expected = 12.214;
        $linestring[] = ['type' => 'point', 'coordinates' => [-85.25631, 29.345665]];
        $linestring[] = require __DIR__ . '/Resources/php/point.php';
        $factory->expects($this->atLeastOnce())
                ->method('length')
                ->willReturn($expected);
        $this->assertEquals($expected, $linestring->getLength());
    }

    /**
     * Test the LineString::isClosed
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Curve::isClosed
     */
    public function testIsClosed()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);

        $linestring = new LineString($factory);
        $this->assertFalse($linestring->isClosed());
        $linestring[] = ['type' => 'point', 'coordinates' => [-85.25631, 29.345665]];
        $linestring[] = require __DIR__ . '/Resources/php/point.php';
        $linestring[] = ['type' => 'point'];
        $linestring[] = ['type' => 'point', 'coordinates' => [-85.25631, 29.345665]];
        $this->assertTrue($linestring->isClosed());
    }

}
