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
use Nasumilu\Spatial\Geometry\CoordinateException;

/**
 * Description of CoordinateExceptionTest
 * 
 * @covers \Nasumilu\Spatial\Geometry\CoordinateException
 */
class CoordinateExceptionTest extends AbstractGeometryTest
{

    /**
     * @test
     */
    public function testMessageOrdinateIndex()
    {
        $factory = $this->getMockGeometryFactory();
        $point = $factory->createPoint();
        $this->expectException(CoordinateException::class);
        $this->expectExceptionMessage("The z-coordinate is not supported!");
        $point[2] = 99.54;
    }

    /**
     * @test
     */
    public function testMessageLetterIndex()
    {
        $factory = $this->getMockGeometryFactory();
        $point = $factory->createPoint();
        $this->expectException(CoordinateException::class);
        $this->expectExceptionMessage("The z-coordinate is not supported!");
        $point['z'] = 99.54;
    }

    /**
     * @test
     */
    public function testMessageIndex()
    {
        $factory = $this->getMockGeometryFactory();
        $point = $factory->createPoint();
        $this->expectException(CoordinateException::class);
        $this->expectExceptionMessage("The r-coordinate is not supported!");
        $point['r'] = 99.54;
    }

}
