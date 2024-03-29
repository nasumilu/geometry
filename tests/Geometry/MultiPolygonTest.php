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

use Nasumilu\Spatial\Geometry\{
    Polygonal,
    MultiPolygon
};

/**
 * @covers \Nasumilu\Spatial\Geometry\MultiPolygon
 * @covers \Nasumilu\Spatial\Geometry\MultiSurface
 */
class MultiPolygonTest extends AbstractGeometryTest
{

    private static $data;

    /**
     * @before
     */
    public static function setUpBeforeClass(): void
    {
        self::$data = require __DIR__ . '/../Resources/php/multipolygon.php';
    }

    /**
     * @test
     */
    public function testGetArea()
    {
        $expected = 958654.655656;
        $factory = $this->getMockGeometryFactory();
        $factory->expects($this->atLeastOnce())
                ->method('area')
                ->willReturn($expected);
        $this->assertEquals($expected, $factory->createMultiPolygon()->getArea());
    }

    /**
     * @test
     */
    public function testGetCentroid()
    {
        $factory = $this->getMockGeometryFactory();
        $expected = $factory->createPoint();
        $factory->expects($this->atLeastOnce())
                ->method('centroid')
                ->willReturn($expected);
        $factory->expects($this->atLeastOnce())
                ->method('pointOnSurface')
                ->willReturn($expected);
        $this->assertSame($expected, $factory->createMultiPolygon()->getCentroid());
        $this->assertSame($expected, $factory->createMultiPolygon()->getPointOnSurface());
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\MultiSurface::getDimension
     */
    public function testGetDimension()
    {
        $factory = $this->getMockGeometryFactory();
        $multipolygon = $factory->createMultiPolygon();
        $this->assertEquals(Polygonal::DIMENSION, $multipolygon->getDimension());
    }

    /**
     * @test
     */
    public function testGetGeometryType()
    {
        $factory = $this->getMockGeometryFactory();
        $multipolygon = $factory->createMultiPolygon();
        $this->assertEquals(MultiPolygon::WKT_TYPE, $multipolygon->getGeometryType());
    }

    /**
     * @test
     * @dataProvider factoryOptions
     */
    public function testArrayAccessOffsetSet(array $options)
    {

        $factory = $this->getMockGeometryFactory($options);
        $multipolygon = $factory->create(self::$data);
        $multipolygon[] = ['type' => 'polygon'];
        $this->expectException(\InvalidArgumentException::class);
        $multipolygon[] = ['type' => 'point'];
    }

    public function testOutput()
    {
        $linestring = $this->getMockGeometryFactory(['srid' => 3857, 'measured' => true, '3d' => true])
                ->create(require __DIR__ . '/../Resources/php/multipolygon.php');

        $expectedWkt = file_get_contents(__DIR__ . '/../Resources/wkt/xyzm/multipolygon.wkt');
        $this->assertEquals($expectedWkt, $linestring->asText());
        $expectedEwkt = file_get_contents(__DIR__ . '/../Resources/ewkt/xyzm/multipolygon.wkt');
        $this->assertEquals($expectedEwkt, $linestring->asText(['extended' => true]));
        $this->assertEquals($expectedWkt, $linestring->output('wkt'));
        $this->assertEquals($expectedEwkt, $linestring->output('ewkt'));
    }

}
