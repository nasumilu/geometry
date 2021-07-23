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

namespace Nasumilu\Spatial\Serializer\Tests;

use InvalidArgumentException;
use function file_get_contents;
use Nasumilu\Spatial\Tests\Geometry\AbstractGeometryTest;
use Nasumilu\Spatial\Geometry\{
    Geometry,
    Point,
    LineString,
    Polygon,
    MultiPoint,
    MultiLineString,
    MultiPolygon
};

/**
 * GeometrySerializerTest
 */
class GeometrySerializerTest extends AbstractGeometryTest
{

    /**
     * @dataProvider dataProvider
     */
    public function testSerializeGeometry(array $args, string $wkt, string $format)
    {
        $factory = $this->getMockGeometryFactory([
            'srid' => 4326,
            '3d' => true,
            'measured' => true
        ]);
        $geometry = $factory->create($args);
        $serializer = self::getSerializer()->serialize($geometry, $format);
        $this->assertEquals($serializer, $wkt);
        $deserialize = self::getSerializer()->deserialize($serializer, Geometry::class, $format, ['factory' => $factory]);
        $this->assertEquals($geometry, $deserialize);
        $this->expectException(InvalidArgumentException::class);
        self::getSerializer()->deserialize($serializer, Geometry::class, $format);
    }

    /**
     * @testWith ["point", "POINT EMPTY", {"srid":4326}]
     *           ["linestring", "LINESTRING EMPTY", {"srid":4326}]
     *           ["polygon", "POLYGON EMPTY", {"srid":4326}]
     *           ["multipoint", "MULTIPOINT EMPTY", {"srid":4326}]
     *           ["multilinestring", "MULTILINESTRING EMPTY", {"srid":4326}]
     *           ["multipolygon", "MULTIPOLYGON EMPTY", {"srid":4326}]
     */
    public function testSerializeEmptyGeometry(string $type, string $wkt, array $options)
    {
        $factory = $this->getMockGeometryFactory($options);
        $geometry = $factory->create(['type' => $type]);
        $this->assertTrue($geometry->isEmpty());
        $serializer = self::getSerializer()->serialize($geometry, 'wkt');
        $this->assertEquals($serializer, $wkt);
        $deserialize = self::getSerializer()->deserialize($serializer, Geometry::class, 'wkt', ['factory' => $factory]);

        $this->assertTrue($deserialize->isEmpty());
    }

    public function dataProvider()
    {
        $resource = __DIR__ . '/../Resources/';
        $data = [];
        foreach ([
            Point::WKT_TYPE,
            LineString::WKT_TYPE,
            Polygon::WKT_TYPE,
            MultiPoint::WKT_TYPE,
            MultiLineString::WKT_TYPE,
            MultiPolygon::WKT_TYPE] as $type) {
            foreach (array_merge(WktEncoder::FORMATS, WkbEncoder::FORMATS) as $format) {
                $data["$type-$format"] = [
                    require $resource . "/php/$type.php",
                    file_get_contents($resource . "$format/$type.$format"),
                    $format];
            }
        }
        return $data;
    }

}
