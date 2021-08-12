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

namespace Nasumilu\Spatial\Serializer\Decoder;

use Symfony\Component\Serializer\Encoder\ChainDecoder;

/**
 * WktDecoder is used to convert well-known text into a normalized geometry
 * <br>
 * <strong>Supported Formats</strong>
 * <ul>
 *  <li><em>wkt11</em> - {@link https://portal.ogc.org/files/?artifact_id=13227 Well-known Text v1.1.0}</li>
 *  <li><em>wkt</em> - {@link https://portal.ogc.org/files/?artifact_id=18241 Well-Known Text v1.2.0}</li>
 *  <li><em>ewkt</em> - {@link https://postgis.net/docs/ST_GeomFromEWKT.html Extended Well-Known Text}</li>
 * </ul>
 */
class WktDecoder extends ChainDecoder
{
 
    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct([
            new Wkt\Wkt11Decoder(),
            new Wkt\Wkt12Decoder(),
            new Wkt\EwktDecoder()
        ]);
    }
    
}
