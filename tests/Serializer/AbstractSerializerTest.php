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

use Nasumilu\Spatial\Tests\Geometry\AbstractGeometryTest;
use Nasumilu\Spatial\Geometry\{
    Geometry,
    GeometryFactory,
    Point,
    LineString,
    Polygon,
    MultiPoint,
    MultiLineString,
    MultiPolygon,
    GeometryCollection
};

/**
 * AbstractSerializerTest
 */
abstract class AbstractSerializerTest extends AbstractGeometryTest
{

    /**
     * Gets the serialize format for the test data
     * 
     * @return string
     */
    protected abstract function getFormat(): string;

    /**
     * Gets the file extension (without the dot)
     * 
     * @return string
     */
    protected abstract function getExtension(): string;

    /**
     * Gets the serialize context parameters (default: is an empty array)
     * @return array
     */
    protected function getContext(): array
    {
        return [];
    }

    /**
     * @covers \Nasumilu\Spatial\Serializer\Encoder\WkbEncoder
     * @covers \Nasumilu\Spatial\Serializer\Decoder\WkbDecoder
     * @covers \Nasumilu\Spatial\Serializer\Encoder\WktEncoder
     * @covers \Nasumilu\Spatial\Serializer\Decoder\WktDecoder
     * @covers \Nasumilu\Spatial\Serializer\Normalizer\GeometryNormalizer
     * 
     * @dataProvider dataProvider
     * @param array $data
     * @param string $expected
     * @param GeometryFactory $factory
     */
    public function testSerialize(array $data, string $expected, GeometryFactory $factory)
    {
        $geometry = $factory->create($data);
        $wkt = self::getSerializer()->serialize($geometry, $this->getFormat(), $this->getContext());
        $this->assertEquals($expected, $wkt);
        $deserialize = self::getSerializer()->deserialize($wkt, Geometry::class, $this->getFormat(), ['factory' => $factory]);
        $this->assertInstanceOf(get_class($geometry), $deserialize);
        $this->assertInstanceOf(get_class($geometry), $factory->create($expected));
    }

    /**
     * Gets test data from known good data (resources)
     * @return array
     */
    public function dataProvider(): array
    {
        $resource = __DIR__ . '/../Resources';
        $factoryOptions = $this->factoryOptions();
        $format = $this->getFormat();
        $extension = $this->getExtension();
        $data = [];
        foreach ([Point::WKT_TYPE, LineString::WKT_TYPE, Polygon::WKT_TYPE,
    MultiPoint::WKT_TYPE, MultiLineString::WKT_TYPE, MultiPolygon::WKT_TYPE,
    GeometryCollection::WKT_TYPE] as $type) {
            foreach ($factoryOptions as $key => $options) {
                $expected = "$resource/$format/$key/$type.$extension";
                $data["$type-$format-$key"] = [
                    require "$resource/php/$type.php",
                    file_get_contents($expected),
                    $this->getMockGeometryFactory($options[0])
                ];
            }
        }
        return $data;
    }

}
