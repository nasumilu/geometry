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
    MultiLineString
};

/**
 * @covers Nasumilu\Spatial\Geometry\MultiLineString
 */
class MultiLineStringTest extends AbstractGeometryTest
{

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\MultiCurve::getLength
     * @covers \Nasumilu\Spatial\Geometry\MultiCurve::__construct
     */
    public function testGetLength()
    {
        $factory = $this->getMockGeometryFactory();
        $multilinestring = new MultiLineString($factory);
        $expected = 1234.2233;
        $factory->expects($this->atLeastOnce())
                ->method('length')
                ->willReturn($expected);
        $this->assertEquals($expected, $multilinestring->getLength());
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\MultiCurve::isClosed
     */
    public function isClosed()
    {
        $factory = $this->getMockGeometryFactory();
        $multilinestring = new MultiLineString($factory);
        $this->assertTrue($multilinestring->isClosed());
        $multilinestring[] = require __DIR__ . '/../Resources/php/linestring.php';
        $this->assertFalse($multilinestring->isClosed());
        $multilinestring[0][] = $multilinestring[0][0];
        $this->assertTrue($multilinestring->isClosed());
    }

    /**
     * Tests the MultiLineString::getDimension method
     * @test
     * @covers \Nasumilu\Spatial\Geometry\MultiCurve::getDimension
     */
    public function testGetDimension()
    {
        $factory = $this->getMockGeometryFactory();
        $multilinestring = new MultiLineString($factory);
        $this->assertEquals(1, $multilinestring->getDimension());
    }

    /**
     * Test the MultiLineString::getGeometryType method
     * @test
     * @covers \Nasumilu\Spatial\Geometry\MultiCurve::getGeometryType
     */
    public function testGetGeometryType()
    {
        $factory = $this->getMockGeometryFactory();
        $multilinestring = new MultiLineString($factory);
        $this->assertEquals(MultiLineString::WKT_TYPE, $multilinestring->getGeometryType());
    }

    /**
     * Test the MultiLineString::getGeometryType method
     * @test
     * @covers \Nasumilu\Spatial\Geometry\MultiCurve::getGeometryType
     */
    public function testOffsetSet()
    {
        $factory = $this->getMockGeometryFactory();
        $multilinestring = new MultiLineString($factory);
        $this->expectException(\InvalidArgumentException::class);
        $multilinestring[] = ['type' => 'point'];
    }

    public function testOutput()
    {
        $linestring = $this->getMockGeometryFactory(['srid' => 3857, 'measured' => true, '3d' => true])
                ->create(require __DIR__ . '/../Resources/php/multilinestring.php');

        $expectedWkt = file_get_contents(__DIR__ . '/../Resources/wkt/xyzm/multilinestring.wkt');
        $this->assertEquals($expectedWkt, $linestring->asText());
        $expectedEwkt = file_get_contents(__DIR__ . '/../Resources/ewkt/xyzm/multilinestring.wkt');
        $this->assertEquals($expectedEwkt, $linestring->asText(['extended' => true]));
        $this->assertEquals($expectedWkt, $linestring->output('wkt'));
        $this->assertEquals($expectedEwkt, $linestring->output('ewkt'));
    }

}
