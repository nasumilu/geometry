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
    AbstractGeometryFactory,
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
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $multilinestring = new MultiLineString($factory);
        $expected = 1234.2233;
        $factory->method('length')->willReturn($expected);
        $this->assertEquals($expected, $multilinestring->getLength());
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\MultiCurve::isClosed
     */
    public function isClosed()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $multilinestring = new MultiLineString($factory);
        $this->assertTrue($multilinestring->isClosed());
        $multilinestring[] = require __DIR__ . '/Resources/php/linestring.php';
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
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
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
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
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
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $multilinestring = new MultiLineString($factory);
        $this->expectException(\InvalidArgumentException::class);
        $multilinestring[] = ['type' => 'point'];
    }

}
