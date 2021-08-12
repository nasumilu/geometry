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

/**
 * Wkt11SerializerTest tests the well-known text v1.1.0 fixtures
 * 
 * @covers \Nasumilu\Spatial\Serializer\Encoder\Wkt\Wkt11Encoder
 */
class Wkt11SerializerTest extends AbstractWktSerializerTest
{

    /**
     * {@inheritDoc}
     */
    protected function getFormat(): string
    {
        return 'wkt11';
    }

}
