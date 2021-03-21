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
    Geometry
};

/**
 * Description of GeometryTest
 */
class GeometryTest extends TestCase
{

    private const FACTORY_OPTIONS = [
        'srid' => 3857,
        'is_3d' => true,
        'is_measured' => true
    ];

    public function testConstructor(): Geometry
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class,
                [self::FACTORY_OPTIONS]);
        $geometry = $this->getMockForAbstractClass(Geometry::class, [$factory]);
        $this->assertSame($factory, $geometry->getFactory());
        return $geometry;
    }

    /**
     * @depends testConstructor
     * @param Geometry $geometry
     */
    public function testGetSrid(Geometry $geometry)
    {
        $this->assertEquals(self::FACTORY_OPTIONS['srid'], $geometry->getSrid());
    }

    /**
     * @depends testConstructor
     * @param Geometry $geometry
     */
    public function testGetIs3d(Geometry $geometry)
    {
        $this->assertTrue($geometry->is3D());
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $geometry = $this->getMockForAbstractClass(Geometry::class, [$factory]);
        $this->assertFalse($geometry->is3D());
    }

    /**
     * @depends testConstructor
     * @param Geometry $geometry
     */
    public function testGetIsMeasured(Geometry $geometry)
    {
        $this->assertTrue($geometry->isMeasured());
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $geometry = $this->getMockForAbstractClass(Geometry::class, [$factory]);
        $this->assertFalse($geometry->isMeasured());
    }

}
