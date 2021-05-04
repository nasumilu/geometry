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
    Polygon
};

/**
 * Description of PolygonTest
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
    
    public function testConstructor() {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $polygon = new Polygon($factory);
        $this->assertInstanceOf(Polygon::class, $polygon);
        $this->assertCount(1, $polygon);
    }

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

}
