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
 * Wkb11SerializerTest test well-known binary version 1.1.0 fixtures
 * @covers \Nasumilu\Spatial\Serializer\Encoder\Wkb\Wkb11Encoder
 * @covers \Nasumilu\Spatial\Serializer\Decoder\Wkb\Wkb11Decoder
 */
class Wkb11SerializerTest extends AbstractWkbSerializerTest
{

    /**
     * {@inheritDoc}
     */
    protected function getFormat(): string
    {
        return 'wkb11';
    }

}
