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

namespace Nasumilu\Spatial\Geometry\Builder;

/**
 * Description of GeometryBuilderRegistry
 */
interface GeometryBuilderRegistry
{

    /**
     * Registers a GeometryBuilder
     * @param GeometryBuilder $builders
     */
    public function registerBuilder(GeometryBuilder ...$builders);

    /**
     * Unregister a GeometryBuilder
     * @param GeometryBuilder $builers
     */
    public function unregisterBuilder(GeometryBuilder ...$builers);
    
    /**
     * Indicates whether a GeometryBuilder has been registered or not
     * 
     * @param GeometryBuilder $builder
     * @return bool
     */
    public function hasBuilder(GeometryBuilder $builder): bool;
}
