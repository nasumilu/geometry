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
use Nasumilu\Spatial\Geometry\CoordinateException;

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

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::getEnvelope
     */
    public function testGetEnvelope()
    {

        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $expected = $factory->createPolygon();
        $factory->expects($this->atLeastOnce())
                ->method('envelope')
                ->willReturn($expected);
        $geometry = $factory->createMultiLineString();
        $envelope = $geometry->getEnvelope();
        $this->assertEquals($expected, $envelope);
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::isSimple
     */
    public function testIsSimple()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $factory->expects($this->atLeastOnce())
                ->method('isSimple')
                ->willReturn(true);
        $geometry = $factory->createLineString();
        $this->assertTrue($geometry->isSimple());
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::getBoundary
     */
    public function testGetBoundary()
    {

        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $expected = $factory->createPolygon();
        $factory->expects($this->atLeastOnce())
                ->method('boundary')
                ->willReturn($expected);
        $geometry = $factory->createMultiLineString();
        $boundary = $geometry->getBoundary();
        $this->assertEquals($expected, $boundary);
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::disjoint
     */
    public function testDisjoint()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $factory->expects($this->atLeastOnce())
                ->method('disjoint')
                ->willReturn(false);
        $geometry = $factory->createPoint();
        $other = $factory->createPolygon();
        $this->assertFalse($geometry->disjoint($other));
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::intersects
     */
    public function testIntersects()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $factory->expects($this->atLeastOnce())
                ->method('intersects')
                ->willReturn(true);
        $geometry = $factory->createPoint();
        $other = $factory->createPolygon();
        $this->assertTrue($geometry->intersects($other));
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::touches
     */
    public function testTouches()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $factory->expects($this->atLeastOnce())
                ->method('touches')
                ->willReturn(false);
        $geometry = $factory->createPoint();
        $other = $factory->createPolygon();
        $this->assertFalse($geometry->touches($other));
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::crosses
     */
    public function testCrosses()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $factory->expects($this->atLeastOnce())
                ->method('crosses')
                ->willReturn(true);
        $geometry = $factory->createPoint();
        $other = $factory->createPolygon();
        $this->assertTrue($geometry->crosses($other));
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::within
     */
    public function testWithin()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $factory->expects($this->atLeastOnce())
                ->method('within')
                ->willReturn(true);
        $geometry = $factory->createPoint();
        $other = $factory->createPolygon();
        $this->assertTrue($geometry->within($other));
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::contains
     */
    public function testContains()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $factory->expects($this->atLeastOnce())
                ->method('contains')
                ->willReturn(true);
        $geometry = $factory->createPoint();
        $other = $factory->createPolygon();
        $this->assertTrue($geometry->contains($other));
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::overlaps
     */
    public function testOverlaps()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $factory->expects($this->atLeastOnce())
                ->method('overlaps')
                ->willReturn(true);
        $geometry = $factory->createPoint();
        $other = $factory->createPolygon();
        $this->assertTrue($geometry->overlaps($other));
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::relate
     */
    public function testRelate()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $factory->expects($this->atLeastOnce())
                ->method('relate')
                ->willReturn(true);
        $geometry = $factory->createPoint();
        $other = $factory->createPolygon();
        $this->assertTrue($geometry->relate($other, 'T*F**FFF*'));
        $this->assertTrue($geometry->relate($other, 't*f**tff*'));
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid DE-9IM model! Model must only '
                . 'contain 9 characters of T, F, or *');
        $geometry->relate($other, 'T*F**FFFFFF*');
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::relate
     */
    public function testRelateError()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $geometry = $factory->createPoint();
        $other = $factory->createPolygon();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid DE-9IM model! Model must only '
                . 'contain 9 characters of T, F, or *');
        $geometry->relate($other, '123456789');
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::locateAlong
     */
    public function testLocateAlong()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class, [['measured' => true]]);
        $expected = $factory->createMultiPoint();
        $factory->expects($this->atLeastOnce())
                ->method('locateAlong')
                ->willReturn($expected);
        $geometry = $factory->createMultiLineString();
        $this->assertSame($expected, $geometry->locateAlong(10));
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::locateAlong
     */
    public function testLocateAlongNotMeasured()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class, [['measured' => false]]);
        $geometry = $factory->createMultiLineString();
        $this->expectException(CoordinateException::class);
        $geometry->locateAlong(10);
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::locateAlong
     */
    public function testLocateAlongPolygonal()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class, [['measured' => false]]);
        $geometry = $factory->createPolygon();
        $this->expectException(\InvalidArgumentException::class);
        $geometry->locateAlong(10);
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::locateBetween
     */
    public function testLocateBetween()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class, [['measured' => true]]);
        $expected = $factory->createMultiPoint();
        $factory->expects($this->atLeastOnce())
                ->method('locateBetween')
                ->willReturn($expected);
        $geometry = $factory->createMultiLineString();
        $this->assertSame($expected, $geometry->locateBetween(10, 20));
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::locateBetween
     */
    public function testLocateBetweenNotMeasured()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class, [['measured' => false]]);
        $geometry = $factory->createMultiLineString();
        $this->expectException(CoordinateException::class);
        $geometry->locateBetween(1, 20);
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::locateBetween
     */
    public function testLocateBetweenPolygonal()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class, [['measured' => false]]);
        $geometry = $factory->createPolygon();
        $this->expectException(\InvalidArgumentException::class);
        $geometry->locateBetween(10, 20);
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::distance
     */
    public function testDistance()
    {
        $expected = (float) rand(1000, 9999); // some random number
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $factory->expects($this->atLeastOnce())
                ->method('distance')
                ->willReturn($expected);
        $geometry = $factory->createPoint();
        $other = $factory->createPoint();
        $this->assertEquals($expected, $geometry->distance($other));
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::buffer
     */
    public function testBuffer()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $expected = $factory->createPolygon();
        $factory->expects($this->atLeastOnce())
                ->method('buffer')
                ->willReturn($expected);
        $geometry = $factory->createPoint();
        $this->assertSame($expected, $geometry->buffer(10));
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::convexHull
     */
    public function testConvexHull()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $expected = $factory->createPolygon();
        $factory->expects($this->atLeastOnce())
                ->method('convexHull')
                ->willReturn($expected);
        $geometry = $factory->createPolygon();
        $this->assertSame($expected, $geometry->convexHull());
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::intersection
     */
    public function testIntersection()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $expected = $factory->createPolygon();
        $factory->expects($this->atLeastOnce())
                ->method('intersection')
                ->willReturn($expected);
        $geometry = $factory->createPolygon();
        $other = $factory->createPolygon();
        $this->assertSame($expected, $geometry->intersection($other));
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::union
     */
    public function testUnion()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $expected = $factory->createPolygon();
        $factory->expects($this->atLeastOnce())
                ->method('union')
                ->willReturn($expected);
        $geometry = $factory->createPolygon();
        $other = $factory->createPolygon();
        $this->assertSame($expected, $geometry->union($other));
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::difference
     */
    public function testDifference()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $expected = $factory->createPolygon();
        $factory->expects($this->atLeastOnce())
                ->method('difference')
                ->willReturn($expected);
        $geometry = $factory->createPolygon();
        $other = $factory->createPolygon();
        $this->assertSame($expected, $geometry->difference($other));
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::symDifference
     */
    public function testSymDifference()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $expected = $factory->createPolygon();
        $factory->expects($this->atLeastOnce())
                ->method('symDifference')
                ->willReturn($expected);
        $geometry = $factory->createPolygon();
        $other = $factory->createPolygon();
        $this->assertSame($expected, $geometry->symDifference($other));
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::transform
     */
    public function testTransformSameSridAKAClone()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $transform = $this->getMockForAbstractClass(AbstractGeometryFactory::class);
        $expected = $transform->createPolygon();
        $factory->expects($this->atLeastOnce())
                ->method('transform')
                ->willReturn($expected);
        $geometry = $factory->createPolygon();
        $transformGeometry = $geometry->transform($transform);
        $this->assertSame($expected, $transformGeometry);
        $this->assertSame($transform, $transformGeometry->getFactory());
    }

    /**
     * @test
     * @covers \Nasumilu\Spatial\Geometry\Geometry::transform
     */
    public function testTransformDiffSrid()
    {
        $factory = $this->getMockForAbstractClass(AbstractGeometryFactory::class, [['srid' => 3857]]);
        $transform = $this->getMockForAbstractClass(AbstractGeometryFactory::class, [['srid' => 2387]]);
        $expected = $transform->createPolygon();
        $factory->expects($this->atLeastOnce())
                ->method('transform')
                ->willReturn($expected);
        $geometry = $factory->createPolygon();
        $transformGeometry = $geometry->transform($transform);
        $this->assertSame($expected, $transformGeometry);
        $this->assertSame($transform, $transformGeometry->getFactory());
    }

    public function dataProvider(): array
    {
        return require __DIR__ . '/Resources/php/factory_options.php';
    }

}
