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

namespace Nasumilu\Spatial\Serializer\Encoder\Wkt;

/**
 * Description of EwktEncoder
 */
class EwktEncoder extends Wkt12Encoder
{

    public const FORMAT = 'ewkt';

    /**
     * Encodes a geometry type as well-known text
     * 
     * @param array $data
     * @return string
     */
    protected function encodeGeometryType(array $data): string
    {
        $wkt = parent::encodeGeometryType($data);
        if (-1 !== $data['crs']['srid'] ?? -1) {
            $wkt = "SRID={$data['crs']['srid']};" . $wkt;
        }
        return $wkt;
    }

    /**
     * {@inheritDoc}
     */
    public function encodeGeometryCollection(array $data, array $context = []): string
    {
        $wkt = '';
        foreach ($data['geometries'] as $geometry) {
            $wkt .= parent::encodeGeometryType($geometry);
            if (!isset($data['coordinates']) && !isset($data['geometries'])) {
                $wkt .= " EMPTY";
                continue;
            }
            $wkt .= '(' . call_user_func([$this, "encode{$geometry['type']}"], $geometry, $context) . '),';
        }
        return rtrim($wkt, ',');
    }

}
