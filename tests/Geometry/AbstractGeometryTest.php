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

use function array_merge;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\{
    Serializer,
    SerializerInterface
};
use Nasumilu\Spatial\Serializer\{
    Normalizer\GeometryNormalizer,
    Encoder\WktEncoder,
    Decoder\WktDecoder,
    Encoder\WkbEncoder,
    Decoder\WkbDecoder
};
use Nasumilu\Spatial\Geometry\{
    AbstractGeometryFactory,
    GeometryFactory
};

/**
 * Description of AbstractGeometryTest
 */
abstract class AbstractGeometryTest extends TestCase
{

    protected static $serializer;

    public function factoryOptions(): array
    {
        return require __DIR__ . '/../Resources/php/factory_options.php';
    }

    protected function getMockGeometryFactory(array $options = []): GeometryFactory
    {
        return $this->getMockForAbstractClass(AbstractGeometryFactory::class,
                        [array_merge(['serializer' => self::getSerializer()], $options)]);
    }

    public function factoryProvider(): array
    {
        $data = [];
        foreach ($this->factoryOptions() as $key => $options) {
            $data[$key] = [$this->getMockGeometryFactory($options)];
        }
        return $data;
    }

    protected static function getSerializer(): SerializerInterface
    {
        if (null === self::$serializer) {
            self::$serializer = new Serializer([new GeometryNormalizer()], [
                new WktEncoder(), new WkbEncoder(), new WktDecoder(), new WkbDecoder()
            ]);
        }
        return self::$serializer;
    }

}
