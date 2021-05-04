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
    Point,
    MultiPoint
};

/**
 * Description of MultiPointTest
 */
class MultiPointTest extends AbstractGeometryTest
{

    private static array $data;

    /**
     * @beforeClass
     */
    public static function setUpBeforeClass(): void
    {
        self::$data = require __DIR__ . '/Resources/php/multipoint.php';
    }

    /**
     * @test
     */
    public function testConstructor()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $points = [];
        foreach(self::$data['coordinates'] as $coordinate) {
            $points[] = new Point($factory, $coordinate);
        }
        $multipoint = new MultiPoint($factory, ...$points);
        $this->assertInstanceOf(MultiPoint::class, $multipoint);
    }

    /**
     * Test the MultiPoint::getDimension method
     * @test
     */
    public function testGetDimension()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $multipoint = new MultiPoint($factory);
        $this->assertEquals(0, $multipoint->getDimension());
    }

    /**
     * Test the MultiPoint::getGeometryType method
     * @test
     */
    public function testGetGeometryType()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $multipoint = new MultiPoint($factory);
        $this->assertEquals(MultiPoint::WKT_TYPE, $multipoint->getGeometryType());
    }

}
