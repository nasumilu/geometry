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

use PHPUnit\Framework\TestCase;
use Nasumilu\Spatial\Geometry\{
    AbstractGeometryFactory,
    LineString,
    Polygon
};

/**
 * @covers Nasumilu\Spatial\Geometry\Polygon
 */
class PolygonTest extends AbstractGeometryTest
{

    private static array $data;

    /**
     * @beforeClass
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$data = require __DIR__ . '/Resources/php/polygon.php';
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Surface::__construct
     * @covers \Nasumilu\Spatial\Geometry\Geometry::is3D
     * @covers \Nasumilu\Spatial\Geometry\Geometry::isMeasured
     * @dataProvider factoryOptions
     * @param array $options
     */
    public function testConstructor(array $options)
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class, [$options]);
        $polygon = new Polygon($factory);
        $this->assertInstanceOf(Polygon::class, $polygon);
        $this->assertCount(1, $polygon);
        $this->assertEquals(0, $polygon->getNumInteriorRings());
        if ($options['3d']) {
            $this->assertTrue($polygon->is3D());
        } else {
            $this->assertFalse($polygon->is3D());
        }

        if ($options['measured']) {
            $this->assertTrue($polygon->isMeasured());
        } else {
            $this->assertFalse($polygon->isMeasured());
        }
    }

    /**
     * @dataProvider factoryOptions
     * @param array $options
     */
    public function testIteratorAndArrayAccess(array $options)
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class, [$options]);
        $polygon = $factory->create(self::$data);
        $polygon->rewind();
        while($polygon->valid()) {
            $this->assertInstanceOf(LineString::class, $polygon->current());
            $polygon->next();
        }
        $this->assertFalse($polygon->valid());
        $polygon->rewind();
        $this->assertTrue($polygon->valid());
        foreach($polygon as $key=>$value) {
            $this->assertEquals($key, $polygon->key());
            $this->assertSame($polygon[$key], $value);
            $this->assertTrue($polygon->offsetExists($key));
            $this->assertTrue(isset($polygon[$key]));
        }
        
        unset($polygon[count($polygon) - 1]);
        $this->assertCount(3, $polygon);
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\AbstractGeometryFactory::create
     * @covers \Nasumilu\Spatial\Geometry\Polygon::getExteriorRing
     * @covers \Nasumilu\Spatial\Geometry\Polygon::getInteriorRingN
     * @dataProvider factoryOptions
     * @param array $options
     */
    public function testInterorRings(array $options)
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class, [$options]);
        $polygon = $factory->create(self::$data);
        $this->assertInstanceOf(Polygon::class, $polygon);
        $rings = $polygon->getInteriorRings();
        $this->assertCount(3, $rings);
        foreach ($rings as $key => $ring) {
            $this->assertInstanceOf(LineString::class, $ring);
            $this->assertNotSame($polygon->getExteriorRing(), $ring);
            $this->assertSame($polygon->getInteriorRingN($key), $ring);
        }
        $this->expectException(\OutOfRangeException::class);
        $polygon->getInteriorRingN(100);
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Surface::getDimension
     */
    public function testGetDimension()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $polygon = new Polygon($factory);
        $this->assertEquals(2, $polygon->getDimension());
    }

    public function testGetGeometryType()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $polygon = new Polygon($factory);
        $this->assertEquals(Polygon::WKT_TYPE, $polygon->getGeometryType());
    }

    /**
     * Test Surface::getBoundary method
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Surface::getBoundary
     */
    public function testGetBoundary()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $polygon = new Polygon($factory);
        $expected = $factory->createMultiLineString();
        $factory->method('boundary')->willReturn($expected);
        $this->assertSame($expected, $polygon->getBoundary());
    }

    /**
     * Test Surface::getCentroid method
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Surface::getCentroid
     */
    public function testGetCentroid()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $polygon = new Polygon($factory);
        $expected = $factory->createPoint();
        $factory->method('centroid')->willReturn($expected);
        $this->assertSame($expected, $polygon->getCentroid());
    }

    /**
     * Test Surface::getArea method
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Surface::getArea
     */
    public function testGetArea()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $polygon = new Polygon($factory);
        $expected = 6545654.5465456;
        $factory->method('area')->willReturn($expected);
        $this->assertEquals($expected, $polygon->getArea());
    }

    /**
     * Test Surface::getPointOnSurface method
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Surface::getPointOnSurface
     */
    public function testGetPointOnSurface()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $polygon = new Polygon($factory);
        $expected = $factory->createPoint();
        $factory->method('pointOnSurface')->willReturn($expected);
        $this->assertSame($expected, $polygon->getPointOnSurface());
    }

    /**
     * Test Surface::isEmpty method
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Surface::isEmpty
     */
    public function testIsEmpty()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $polygon = new Polygon($factory);
        $this->assertTrue($polygon->isEmpty());
        $polygon[0] = self::$data['coordinates'][0];
        $this->assertFalse($polygon->isEmpty());
        $this->assertTrue($polygon->getExteriorRing()->isClosed());
    }

}
