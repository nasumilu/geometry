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

/**
 * Description of AbstractGeometryTest
 */
abstract class AbstractGeometryTest extends TestCase
{
    
    public function factoryOptions(): array
    {
        $options = ['srid' => 3857, 'is_3d' => false, 'is_measured' => false];
        return [
            'xy' => [$options],
            'xyz' => [array_merge($options, ['is_3d'=>true])],
            'xym' => [array_merge($options, ['is_measured' => true])],
            'xyzm' => [array_merge($options, ['is_3d'=>true, 'is_measured'=>true])]
        ];
    }
    
    public abstract function testGetDimension();
    
    public abstract function testGetGeometryType();
    
}
