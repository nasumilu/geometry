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
 * AbstractWktSerializerTest test all of the fixtures with a 'wkb' file
 * extension
 */
abstract class AbstractWkbSerializerTest extends AbstractSerializerTest
{

    /**
     * {@inheritDoc}
     */
    protected function getExtension(): string
    {
        return 'wkb';
    }

    /**
     * {@inheritDoc}
     * @internal passes the context parameter to convert the wkb to a hex string
     */
    protected function getContext(): array
    {
        return ['hex_str' => true];
    }
    
}