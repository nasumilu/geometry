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

use Nasumilu\Spatial\Geometry\Geometry;

/**
 * Description of CloneGeometryBuilderTest
 */
class CloneGeometryBuilderTest extends AbstractGeometryTest
{

    /**
     * @test
     * @dataProvider dataProvider
     */
    public function cloneGeometryWithOneFactory(array $data)
    {
        $factory = $this->getMockGeometryFactory();
        $point = $factory->create($data);
        $this->assertInstanceOf(Geometry::class, $point);
        $this->assertSame($factory, $point->getFactory());
        $clone = $factory->create($point);
        $this->assertNotSame($point, $clone);
        $this->assertSame($factory, $clone->getFactory());
       
    }
    
    /**
     * @test
     * @dataProvider dataProvider
     */
    public function cloneGeometryWithTwoFactory(array $data) {
        $factory1 = $this->getMockGeometryFactory();
        $factory2 = $this->getMockGeometryFactory(['srid' => 3857]);
        
        $factory1->expects($this->atLeastOnce())
                ->method('transform')
                ->will($this->returnValue($factory2->create($data)));
        
        $geometry = $factory1->create($data);
        $this->assertInstanceOf(Geometry::class, $geometry);
        $this->assertSame($factory1, $geometry->getFactory());
        $clone = $factory2->create($geometry);
        $this->assertInstanceOf(Geometry::class, $clone);
        $this->assertSame($factory2, $clone->getFactory());
        $this->assertNotSame($geometry->getFactory(), $clone->getFactory());
    }
    
    /**
     * @test
     */
    public function nullGeometryBuilderTest()
    {
        $data = null;
        $factory = $this->getMockGeometryFactory();
        $this->expectException(\RuntimeException::class);
        $factory->create($data);
    }
    
    public function dataProvider(): array
    {
        $types = ['point','linestring', 'polygon', 'multipoint', 'multilinestring', 'multipolygon', 'geometrycollection'];
        $data = [];
        foreach($types as $type) {
            $data[$type] = [require __DIR__."/../Resources/php/$type.php"];
        }
        return $data;
    }

}
