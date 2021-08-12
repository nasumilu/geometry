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

use function array_values;
use Nasumilu\Spatial\Geometry\{
    GeometryFactory,
    CoordinateException,
    Point
};

/**
 * @covers \Nasumilu\Spatial\Geometry\Point
 */
class PointTest extends AbstractGeometryTest
{

    /** Some coordinate data to test with */
    private const COORDINATES = [
        'x' => -85.14654564,
        'y' => 28.4964655465,
        'z' => 562.45,
        'm' => 892.455
    ];

    /**
     * Test the Point class Iterator interface
     * @test
     * @dataProvider factoryProvider
     * @param GeometryFactory $factory
     */
    public function testIteratorInterface(GeometryFactory $factory)
    {
        $point = new Point($factory, self::COORDINATES);
        $precision = $factory->getPrecisionModel();
        $expected = array_values(self::COORDINATES);
        $i = 0;

        while ($point->valid()) {
            $this->assertEquals($precision->makePrecise($expected[$i]), $point->current());
            $point->next();
            $i++;

            if ($i == 2 && $factory->isMeasured() && !$factory->is3d()) {
                $i++;
                $this->assertEquals(3, $point->key());
            }
        }
        $point->rewind();
        $this->expectException(CoordinateException::class);
        $point['d'];
    }

    public function testOutput()
    {
        $point = $this->getMockGeometryFactory(['srid' => 3857, 'measured' => true, '3d' => true])
                ->create(require __DIR__.'/../Resources/php/point.php');
        
        $expectedWkt = file_get_contents(__DIR__.'/../Resources/wkt/xyzm/point.wkt');
        $this->assertEquals($expectedWkt, $point->asText());
        $expectedEwkt = file_get_contents(__DIR__.'/../Resources/ewkt/xyzm/point.wkt');
        $this->assertEquals($expectedEwkt, $point->asText(['extended' => true]));
        $this->assertEquals($expectedWkt, $point->output('wkt'));
        $this->assertEquals($expectedEwkt, $point->output('ewkt'));
    }
    
    /**
     * Test the Point ArrayAccess implementation
     * @test
     * @dataProvider factoryProvider
     * @param GeometryFactory $factory
     */
    public function testArrayAccess(GeometryFactory $factory)
    {
        $point = new Point($factory, self::COORDINATES);
        $precision = $factory->getPrecisionModel();
        $expected = array_values(self::COORDINATES);

        $i = 0;
        while ($point->valid()) {
            if ($i == 2 && $factory->isMeasured() && !$factory->is3D()) {
                $i++;
            }
            $this->assertTrue($point->offsetExists($i));
            $this->assertEquals($precision->makePrecise($expected[$i]), $point[$i]);
            $i++;
            $point->next();
        }

        unset($point['y']);
        $this->assertNan($point->y);
        if (!$point->is3D()) {
            $this->expectException(CoordinateException::class);
            $point->__unset('z');
        }
    }

    /**
     * Test the Point::hasOrdinate 
     * @test
     * @dataProvider factoryProvider
     * @param GeometryFactory $factory
     */
    public function testHasOrdinate(GeometryFactory $factory)
    {
        foreach ([self::COORDINATES, array_values(self::COORDINATES)] as $param) {
            $point = new Point($factory, $param);
            $this->assertTrue($point->hasOrdinate(0));
            $this->assertTrue($point->offsetExists(0));
            $this->assertTrue(isset($point[0]));
            $this->assertTrue(isset($point['x']));
            $this->assertTrue(isset($point->x));
            $this->assertTrue($point->hasOrdinate(1));
            $this->assertTrue(isset($point[1]));
            $this->assertTrue(isset($point['y']));
            $this->assertTrue(isset($point->y));
            if ($factory->getCoordianteSystem()->is3D()) {
                $this->assertTrue($point->offsetExists(2));
                $this->assertTrue($point->hasOrdinate(2));
                $this->assertTrue(isset($point[2]));
                $this->assertTrue(isset($point['z']));
                $this->assertTrue(isset($point->z));
            } else {
                $this->assertFalse($point->offsetExists(2));
                $this->assertFalse($point->hasOrdinate(2));
                $this->assertFalse(isset($point[2]));
                $this->assertFalse(isset($point['z']));
                $this->assertFalse(isset($point->z));
            }

            if ($factory->getCoordianteSystem()->isMeasured()) {
                $this->assertTrue($point->offsetExists(3));
                $this->assertTrue($point->hasOrdinate(3));
                $this->assertTrue(isset($point[3]));
                $this->assertTrue(isset($point['m']));
                $this->assertTrue(isset($point->m));
            } else {
                $this->assertFalse($point->offsetExists(3));
                $this->assertFalse($point->hasOrdinate(3));
                $this->assertFalse(isset($point[3]));
                $this->assertFalse(isset($point['m']));
                $this->assertFalse(isset($point->m));
            }
        }
    }

    /**
     * Tests the Point::getX method
     * @test
     * @dataProvider factoryProvider
     * @param GeometryFactory $factory
     */
    public function testGetX(GeometryFactory $factory)
    {
        $expected = $factory->getPrecisionModel()->makePrecise(self::COORDINATES['x']);
        foreach ([self::COORDINATES, array_values(self::COORDINATES)] as $param) {
            $point = new Point($factory, $param);
            $this->assertEquals($expected, $point->x);
            $this->assertEquals($expected, $point[0]);
            $this->assertEquals($expected, $point['x']);
            $this->assertEquals($expected, $point->getX());
            $this->assertEquals($expected, $point->getOrdinate(0));
        }
    }

    /**
     * Test Point::setX method
     * @test
     * @dataProvider factoryProvider
     * @param GeometryFactory $factory
     */
    public function testSetX(GeometryFactory $factory)
    {
        $point = new Point($factory);
        $this->assertNan($point->getX());
        $precision = $point->getFactory()->getPrecisionModel();
        $this->assertEquals($precision->makePrecise(-85.125468795), $point->setX(-85.125468795)->getX());
        $point->x = -82.456877445;
        $this->assertEquals($precision->makePrecise(-82.456877445), $point->getX());
        $point['x'] = -87.785412369;
        $this->assertEquals($precision->makePrecise(-87.785412369), $point->getX());
        $point[0] = -89.12358746614;
        $this->assertEquals($precision->makePrecise(-89.12358746614), $point->getX());
        $point->setOrdinate(0, -87.12547896);
        $this->assertEquals($precision->makePrecise(-87.12547896), $point->getOrdinate(0));
    }

    /**
     * Tests the Point::getY method
     * @test
     * @dataProvider factoryProvider
     * @param GeoemtryFactory $factory
     */
    public function testGetY(GeometryFactory $factory)
    {
        $expected = $factory->getPrecisionModel()->makePrecise(self::COORDINATES['y']);
        foreach ([self::COORDINATES, array_values(self::COORDINATES)] as $param) {
            $point = new Point($factory, $param);
            $this->assertEquals($expected, $point->y);
            $this->assertEquals($expected, $point[1]);
            $this->assertEquals($expected, $point['y']);
            $this->assertEquals($expected, $point->getY());
            $this->assertEquals($expected, $point->getOrdinate(1));
        }
    }

    /**
     * Test Point::sety method
     * @test
     * @dataProvider factoryProvider
     * @param GeometryFactory $factory
     */
    public function testSetY(GeometryFactory $factory)
    {
        $point = new Point($factory);
        $this->assertNan($point->getY());
        $precision = $point->getFactory()->getPrecisionModel();
        $this->assertEquals($precision->makePrecise(29.5644654547), $point->setY(29.5644654547)->getY());
        $point->y = 28.4655646565;
        $this->assertEquals($precision->makePrecise(28.4655646565), $point->getY());
        $point['y'] = 27.4655678892;
        $this->assertEquals($precision->makePrecise(27.4655678892), $point->getY());
        $point[1] = 28.76168456344;
        $this->assertEquals($precision->makePrecise(28.76168456344), $point->getY());
        $point->setOrdinate(1, 25.1654654218);
        $this->assertEquals($precision->makePrecise(25.1654654218), $point->getY());
    }

    /**
     * Tests the Point::getZ method
     * @test
     * @covers Nasumilu\Spatial\Geometry\CoordinateException::ordinateNotSupported
     * @dataProvider factoryProvider
     * @param array $factory
     */
    public function testGetZ(GeometryFactory $factory)
    {
        $expected = $factory->getPrecisionModel()->makePrecise(self::COORDINATES['z']);
        foreach ([self::COORDINATES, array_values(self::COORDINATES)] as $param) {
            $point = new Point($factory, $param);
            if ($factory->getCoordianteSystem()->is3D()) {
                $this->assertEquals($expected, $point->z);
                $this->assertEquals($expected, $point[2]);
                $this->assertEquals($expected, $point['z']);
                $this->assertEquals($expected, $point->getZ());
                $this->assertEquals($expected, $point->getOrdinate(2));
            } else {
                $this->expectException(CoordinateException::class);
                $this->expectExceptionMessage('The z-coordinate is not supported!');
                $point->z;
            }
        }
    }

    /**
     * Test Point::setZ method
     * @test
     * @covers Nasumilu\Spatial\Geometry\CoordinateException::ordinateNotSupported
     * @dataProvider factoryProvider
     * @param GeometryFactory $factory
     */
    public function testSetZ(GeometryFactory $factory)
    {
        $point = new Point($factory);
        if ($factory->getCoordianteSystem()->is3D()) {
            $this->assertNan($point->getZ());
            $precision = $point->getFactory()->getPrecisionModel();
            $this->assertEquals($precision->makePrecise(3.25), $point->setZ(3.25)->getZ());
            $point->z = 2.46546546454;
            $this->assertEquals($precision->makePrecise(2.46546546454), $point->getZ());
            $point['z'] = 98.4648546456;
            $this->assertEquals($precision->makePrecise(98.4648546456), $point->getZ());
            $point[2] = 2887.76168456344;
            $this->assertEquals($precision->makePrecise(2887.76168456344), $point->getZ());
            $point->setOrdinate(2, 251654.654218);
            $this->assertEquals($precision->makePrecise(251654.654218), $point->getZ());
        } else {
            $this->expectException(CoordinateException::class);
            $this->expectExceptionMessage('The z-coordinate is not supported!');
            $point->setZ(38.554);
        }
    }

    /**
     * Tests the Point::getM method
     * @test
     * @covers Nasumilu\Spatial\Geometry\CoordinateException::ordinateNotSupported
     * @dataProvider factoryProvider
     * @param GeoemtryFactory $factory
     */
    public function testGetM(GeometryFactory $factory)
    {
        $expected = $factory->getPrecisionModel()->makePrecise(self::COORDINATES['m']);
        foreach ([self::COORDINATES, array_values(self::COORDINATES)] as $param) {
            $point = new Point($factory, $param);
            if ($factory->getCoordianteSystem()->isMeasured()) {
                $this->assertEquals($expected, $point->m);
                $this->assertEquals($expected, $point[3]);
                $this->assertEquals($expected, $point['m']);
                $this->assertEquals($expected, $point->getM());
                $this->assertEquals($expected, $point->getOrdinate(3));
            } else {
                $this->expectException(CoordinateException::class);
                $this->expectExceptionMessage('The m-coordinate is not supported!');
                $point->m;
            }
        }
    }

    /**
     * Test Point::setM method
     * @test
     * @covers Nasumilu\Spatial\Geometry\CoordinateException::ordinateNotSupported
     * @dataProvider factoryProvider
     * @param GeometryFactory $factory
     */
    public function testSetM(GeometryFactory $factory)
    {
        $point = new Point($factory);
        if ($factory->getCoordianteSystem()->isMeasured()) {
            $this->assertNan($point->getM());
            $precision = $point->getFactory()->getPrecisionModel();
            $this->assertEquals($precision->makePrecise(3.25), $point->setM(3.25)->getM());
            $point->m = 2.46546546454;
            $this->assertEquals($precision->makePrecise(2.46546546454), $point->getM());
            $point['m'] = 98.4648546456;
            $this->assertEquals($precision->makePrecise(98.4648546456), $point->getM());
            $point[3] = 2887.76168456344;
            $this->assertEquals($precision->makePrecise(2887.76168456344), $point->getM());
            $point->setOrdinate(3, 251654.654218);
            $this->assertEquals($precision->makePrecise(251654.654218), $point->getM());
        } else {
            $this->expectException(CoordinateException::class);
            $this->expectExceptionMessage('The m-coordinate is not supported!');
            $point->setM(38.554);
        }
    }

    /**
     * Test the Point::getDimension
     * @test
     */
    public function testGetDimension()
    {
        $factory = $this->getMockGeometryFactory();
        $point = new Point($factory);
        $this->assertEquals(0, $point->getDimension());
    }

    /**
     * Test the Point::getGeometryType
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Point::__construct
     * @covers \Nasumilu\Spatial\Geometry\Geometry::getGeometryType
     */
    public function testGetGeometryType()
    {
        $factory = $this->getMockGeometryFactory();
        $point = new Point($factory);
        $this->assertEquals(Point::WKT_TYPE, $point->getGeometryType());
    }

    /**
     * Test Point::isEmpty
     * @test
     */
    public function testIsEmpty()
    {
        $factory = $this->getMockGeometryFactory();
        $point = new Point($factory);
        $this->assertTrue($point->isEmpty());
        $point->x = -85.1111111111;
        $this->assertTrue($point->isEmpty());
        $point->y = 29.6456656545;
        $this->assertFalse($point->isEmpty());
    }

}
