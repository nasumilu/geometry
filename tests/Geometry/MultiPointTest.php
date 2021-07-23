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

use InvalidArgumentException;
use Nasumilu\Spatial\Geometry\{
    AbstractGeometryFactory,
    Point,
    MultiPoint
};

/**
 * @covers Nasumilu\Spatial\Geometry\MultiPoint
 * @covers Nasumilu\Spatial\Geometry\GeometryCollection
 */
class MultiPointTest extends AbstractGeometryTest
{

    private static array $data;

    /**
     * @beforeClass
     */
    public static function setUpBeforeClass(): void
    {
        self::$data = require __DIR__ . '/../Resources/php/multipoint.php';
    }

    /**
     * @dataProvider factoryOptions
     * @test
     */
    public function testConstructor(array $options)
    {
        $factory = $this->getMockGeometryFactory($options);
        $points = [];
        foreach(self::$data['coordinates'] as $coordinate) {
            $points[] = new Point($factory, $coordinate);
        }
        $multipoint = new MultiPoint($factory, ...$points);
        $this->assertInstanceOf(MultiPoint::class, $multipoint);
        if($options['3d']) {
            $this->assertTrue($multipoint->is3D());
        } else {
            $this->assertFalse($multipoint->is3D());
        }
        
        if($options['measured']) {
            $this->assertTrue($multipoint->isMeasured());
        } else {
            $this->assertFalse($multipoint->isMeasured());
        }
    }
    
    /**
     * @test
     * @param MultiPoint $multipoint
     */
    public function testArrayAccess()
    {
        $factory = $this->getMockGeometryFactory();
        $points = [];
        foreach(self::$data['coordinates'] as $coordinate) {
            $points[] = new Point($factory, $coordinate);
        }
        $multipoint = new MultiPoint($factory, ...$points);
        foreach($multipoint as $point) {
            $this->assertInstanceOf(Point::class, $point);
        }
    }
    
    /**
     */
    public function testsetGeometryN() {
        $factory = $this->getMockGeometryFactory();
        $multipoint = new MultiPoint($factory);
        $multipoint[] = ['type' => 'point'];
        $this->assertCount(1, $multipoint);
        $multipoint->setGeometryN(['type' => 'point']);
        $this->assertCount(2, $multipoint);
        $multipoint->{2} = ['type' => 'point'];
        $this->expectException(InvalidArgumentException::class);
        $multipoint[] = ['type' => 'linestring']; 
    }

    /**
     * Test the MultiPoint::getDimension method
     * @test
     */
    public function testGetDimension()
    {
        $factory = $this->getMockGeometryFactory();
        $multipoint = new MultiPoint($factory);
        $this->assertEquals(0, $multipoint->getDimension());
    }

    /**
     * Test the MultiPoint::getGeometryType method
     * @test
     */
    public function testGetGeometryType()
    {
        $factory = $this->getMockGeometryFactory();
        $multipoint = new MultiPoint($factory);
        $this->assertEquals(MultiPoint::WKT_TYPE, $multipoint->getGeometryType());
    }

}
