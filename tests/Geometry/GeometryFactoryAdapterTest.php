<?php

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

use RuntimeException;
use Nasumilu\Spatial\Geometry\{
    GeometryFactoryAdapter,
    GeometryFactory
};
use PHPUnit\Framework\TestCase;

/**
 * Description of GeometryFactoryAdapterTest
 *
 * @author mlucas
 * 
 * @covers \Nasumilu\Spatial\Geometry\GeometryFactoryAdapter
 */
class GeometryFactoryAdapterTest extends TestCase {

    private GeometryFactory $factory;

    protected function setUp(): void {
        $this->factory = new GeometryFactoryAdapter(['srid'=> 3857, '3d' => true, 'measured' => true]);
    }

    /**
     * @test
     * @return void
     */
    public function area(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $this->factory->area($polygon);
    }

    /**
     * @test
     * @return void
     */
    public function boundary(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $this->factory->boundary($polygon);
    }

    /**
     * @test
     * @return void
     */
    public function buffer(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $this->factory->buffer($polygon, 100);
    }

    /**
     * @test
     * @return void
     */
    public function centroid(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $this->factory->centroid($polygon);
    }

    /**
     * @test
     * @return void
     */
    public function contains(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $other = $this->factory->createPoint();
        $this->factory->contains($polygon, $other);
    }

    /**
     * @test
     * @return void
     */
    public function convexHull(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $this->factory->convexHull($polygon);
    }

    /**
     * @test
     * @return void
     */
    public function crosses(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $other = $this->factory->createPoint();
        $this->factory->crosses($polygon, $other);
    }

    /**
     * @test
     * @return void
     */
    public function difference(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $other = $this->factory->createPoint();
        $this->factory->difference($polygon, $other);
    }

    /**
     * @test
     * @return void
     */
    public function disjoint(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $other = $this->factory->createPoint();
        $this->factory->disjoint($polygon, $other);
    }

    /**
     * @test
     * @return void
     */
    public function distance(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $other = $this->factory->createPoint();
        $this->factory->distance($polygon, $other);
    }

    /**
     * @test
     * @return void
     */
    public function envelope(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $this->factory->envelope($polygon);
    }

    /**
     * @test
     * @return void
     */
    public function equals(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $other = $this->factory->createPoint();
        $this->factory->equals($polygon, $other);
    }

    /**
     * @test
     * @return void
     */
    public function intersection(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $other = $this->factory->createPoint();
        $this->factory->intersection($polygon, $other);
    }

    /**
     * @test
     * @return void
     */
    public function intersects(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $other = $this->factory->createPoint();
        $this->factory->intersects($polygon, $other);
    }

    /**
     * @test
     * @return void
     */
    public function geometryIsEmpty(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $this->factory->isEmpty($polygon);
    }

    /**
     * @test
     * @return void
     */
    public function isSimple(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $this->factory->isSimple($polygon);
    }

    /**
     * @test
     * @return void
     */
    public function length(): void {
        $this->expectException(RuntimeException::class);
        $line = $this->factory->createLineString();
        $this->factory->length($line);
    }

    /**
     * @test
     * @return void
     */
    public function locateAlong(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $this->factory->locateAlong($polygon, 12);
    }

    /**
     * @test
     * @return void
     */
    public function locateBetween(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $this->factory->locateBetween($polygon, 12, 100);
    }

    /**
     * @test
     * @return void
     */
    public function overlaps(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $other = $this->factory->createPoint();
        $this->factory->overlaps($polygon, $other);
    }

    /**
     * @test
     * @return void
     */
    public function pointOnSurface(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $this->factory->pointOnSurface($polygon);
    }

    /**
     * @test
     * @return void
     */
    public function relate(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $other = $this->factory->createPoint();
        $this->factory->relate($polygon, $other, 'FFF001002');
    }

    /**
     * @test
     * @return void
     */
    public function symDifference(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $other = $this->factory->createPoint();
        $this->factory->symDifference($polygon, $other);
    }

    /**
     * @test
     * @return void
     */
    public function touches(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $other = $this->factory->createPoint();
        $this->factory->touches($polygon, $other);
    }

    /**
     * @test
     * @return void
     */
    public function transform(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $this->factory->transform($polygon, $this->getMockForAbstractClass(GeometryFactory::class));
    }

    /**
     * @test
     * @return void
     */
    public function union(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $other = $this->factory->createPoint();
        $this->factory->union($polygon, $other);
    }

    /**
     * @test
     * @return void
     */
    public function within(): void {
        $this->expectException(RuntimeException::class);
        $polygon = $this->factory->createPolygon();
        $other = $this->factory->createPoint();
        $this->factory->within($polygon, $other);
    }

}
