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
use Nasumilu\Spatial\Geometry\AbstractGeometryFactory;
use Nasumilu\Spatial\Geometry\Builder\GeometryBuilder;

/**
 * Description of AbstractGeometryFactoryTest
 * @covers \Nasumilu\Spatial\Geometry\AbstractGeometryFactory
 */
class AbstractGeometryFactoryTest extends TestCase
{

    /**
     * @test
     */
    public function testRegisterAndUnregisterBuilder()
    {
        $builder = $this->createMock(GeometryBuilder::class);
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $this->assertFalse($factory->hasBuilder($builder));
        $factory->registerBuilder($builder);
        $this->assertTrue($factory->hasBuilder($builder));
        $factory->unregisterBuilder($builder);
        $this->assertFalse($factory->hasBuilder($builder));
    }

    /**
     * @test
     * @dataProvider dataProvider
     * @param array $options
     */
    public function testGetSpatialDimension(array $options)
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class, [$options]);
        $expected = $factory->is3D() ? 3 : 2;
        $this->assertEquals($expected, $factory->getSpatialDimension());
    }

    /**
     * @test
     * @dataProvider dataProvider
     * @param array $options
     */
    public function testGetCoordinateDimension(array $options)
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class, [$options]);
        $expected = 2;
        if ($factory->is3D()) {
            $expected++;
        }
        if ($factory->isMeasured()) {
            $expected++;
        }
        $this->assertEquals($expected, $factory->getCoordinateDimension());
    }

    /**
     * @test
     * @dataProvider dataProvider
     * @param array $options
     */
    public function testGetSrid(array $options)
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class, [$options]);
        $this->assertEquals($options['srid'], $factory->getSrid());
    }

    public function dataProvider(): array
    {
        return require __DIR__ . '/Resources/php/factory_options.php';
    }

}