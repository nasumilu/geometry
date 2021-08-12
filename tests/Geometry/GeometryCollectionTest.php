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

use Nasumilu\Spatial\Geometry\GeometryCollection;

/**
 * Description of GeometryCollectionTest
 * 
 * @covers \Nasumilu\Spatial\Geometry\GeometryCollection
 */
class GeometryCollectionTest extends AbstractGeometryTest
{

    /**
     * @test
     */
    public function testGetDimension()
    {
        $factory = $this->getMockGeometryFactory();
        $geometry = $factory->createGeometryCollection();
        $this->assertEquals(0, $geometry->getDimension());
        $geometry = $factory->create(require __DIR__ . '/../Resources/php/geometrycollection.php');
        $this->assertEquals(2, $geometry->getDimension());
    }

    /**
     * @test
     */
    public function testGetGeometryType()
    {
        $factory = $this->getMockGeometryFactory();
        $geometry = $factory->createGeometryCollection();
        $this->assertEquals(GeometryCollection::WKT_TYPE, $geometry->getGeometryType());
    }

    /**
     * @test
     */
    public function testIsEmpty()
    {
        $factory = $this->getMockGeometryFactory();
        $geometry = $factory->createGeometryCollection();
        $this->assertTrue($geometry->isEmpty());
        $geometry = $factory->create(require __DIR__ . '/../Resources/php/geometrycollection.php');
        $this->assertFalse($geometry->isEmpty());
    }

    /**
     * @test
     */
    public function testIterable()
    {
        $factory = $this->getMockGeometryFactory();
        $geometry = $factory->createGeometryCollection();
        $this->assertCount(0, $geometry);
        $data = require __DIR__ . '/../Resources/php/geometrycollection.php';
        foreach ($data['geometries'] as $shape) {
            $geometry[] = $shape;
        }
        $this->assertCount(count($data['geometries']), $geometry);
        foreach ($geometry as $key => $value) {
            $this->assertTrue(isset($geometry[$key]));
            $this->assertTrue(isset($geometry->{$key}));
            $this->assertSame($geometry->{$key}, $value);
            $this->assertSame($geometry[$key], $value);
        }
        $this->assertFalse(isset($geometry[99]));
        $this->assertFalse(isset($geometry->{99}));
    }

    /**
     * @test
     */
    public function testUnset()
    {
        $data = require __DIR__ . '/../Resources/php/geometrycollection.php';
        $factory = $this->getMockGeometryFactory();
        $geometry = $factory->create($data);
        $this->assertFalse($geometry->isEmpty());
        $firstGeometry = $geometry[0];
        $this->assertSame($firstGeometry, $geometry->removeGeometryN(0));
        $this->assertCount(count($data['geometries']) - 1, $geometry);
        $firstGeometry = $geometry->getGeometryN(0);
        unset($geometry[0]);
        $this->assertCount(count($data['geometries']) - 2, $geometry);
        foreach ($geometry as $shape) {
            $this->assertNotSame($firstGeometry, $shape);
        }
    }

    public function testOutput()
    {
        $linestring = $this->getMockGeometryFactory(['srid' => 3857, 'measured' => true, '3d' => true])
                ->create(require __DIR__ . '/../Resources/php/geometrycollection.php');

        $expectedWkt = file_get_contents(__DIR__ . '/../Resources/wkt/xyzm/geometrycollection.wkt');
        $this->assertEquals($expectedWkt, $linestring->asText());
        $expectedEwkt = file_get_contents(__DIR__ . '/../Resources/ewkt/xyzm/geometrycollection.wkt');
        $this->assertEquals($expectedEwkt, $linestring->asText(['extended' => true]));
        $this->assertEquals($expectedWkt, $linestring->output('wkt'));
        $this->assertEquals($expectedEwkt, $linestring->output('ewkt'));
    }

}
