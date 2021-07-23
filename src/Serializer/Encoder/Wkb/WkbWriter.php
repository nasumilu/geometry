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

namespace Nasumilu\Spatial\Serializer\Encoder\Wkb;

/**
 * Description of WkbWriter
 */
class WkbWriter implements WkbFormat
{

    /**  
     * A normalized geometry value
     * @var array
     */
    private $input;
    private $byteorder;
    private $extended;

    /**
     * @param array $input
     * @param int $byteorder
     * @param bool $extended
     */
    public function __construct(array $input, int $byteorder, bool $extended = false)
    {
        $this->input = $input;
        $this->byteorder = $byteorder;
        $this->extended = $extended;
    }

    public function write(): string
    {
        $type = $this->input['type'];
        $wkb = $this->packUChar((string) $this->byteorder);
        $wkb .= $this->writeGeometryType();
        $method = "write{$type}Coordinates";
        if (!method_exists($this, $method)) {
            throw new \RuntimeException("Unable to write coordinates for Geometry $type!");
        }
        $wkb .= $this->{$method}($this->input['coordinates'] ?? $this->input['geometries'] ?? []);
        return $wkb;
    }

    private function writeGeometryType(): string
    {
        if(false === $type = array_search($this->input['type'], self::WKB_TYPES, true)) {
            throw new \InvalidArgumentException("Unable to find wkb type for {$this->input['type']}!");
        }
        
        if ($this->extended) {
            return $this->writeExtendedGeometryType($type);
        }
        if ($this->input['crs']['3d']) {
            $type += 1000;
        }
        if ($this->input['crs']['measured']) {
            $type += 2000;
        }
        return $this->packUInt($type);
    }

    private function writeExtendedGeometryType(int $type): string
    {
        if ($this->input['crs']['3d']) {
            $type |= self::EWKBZ;
        }
        if ($this->input['crs']['measured']) {
            $type |= self::EWKBM;
        }
        if (-1 !== $srid = $this->input['crs']['srid']) {
            $type |= self::EWKB_SRID;
            return $this->packUInt($type, $srid);
        }
        return $this->packUInt($type);
    }

    private function writePointCoordinates(array $values): string
    {
        return $this->packDouble(...$values);
    }

    private function writeLineStringCoordinates(array $values): string
    {
        $wkb = $this->packUInt(count($values));
        foreach ($values as $point) {
            $wkb .= $this->writePointCoordinates($point);
        }
        return $wkb;
    }

    private function writePolygonCoordinates(array $values): string
    {
        $wkb = $this->packUInt(count($values));
        foreach ($values as $linestring) {
            $wkb .= $this->writeLineStringCoordinates($linestring);
        }
        return $wkb;
    }

    private function writeMultiPointCoordinates(array $values): string
    {
        $wkb = $this->packUInt(count($values));
        foreach ($values as $point) {
            $wkb .= $this->write(['type' => 'point',
                'coordinates' => $point,
                'crs' => $this->input['crs']]);
        }
        return $wkb;
    }

    public function writeMultiLineStringCoordinates(array $values): string
    {
        $wkb = $this->packUInt(count($values));
        foreach ($values as $linestring) {
            $wkb .= $this->write(['type' => 'linestring',
                'coordinates' => $linestring,
                'crs' => $this->input['crs']]);
        }
        return $wkb;
    }

    public function writeMultiPolygonCoordinates(array $values): string
    {
        $wkb = $this->packUInt(count($values));
        foreach ($values as $polygon) {
            $wkb .= $this->write(['type' => 'polygon',
                'coordinates' => $polygon,
                'crs' => $this->input['crs']]);
        }
        return $wkb;
    }

    public function writeGeometryCollectionCoordinates(array $values): string
    {
        $wkb = $this->packUInt(count($values));
        foreach ($values as $geometry) {
            $wkb .= $this->write($geometry);
        }
        return $wkb;
    }


    public function packUChar(string ...$values): string
    {
        return pack('C*', ...$values);
    }

    public function packUInt(int ...$values): string
    {
        return pack((bool) $this->byteorder ? 'V*' : 'N*', ...$values);
    }

    public function packDouble(float ...$values): string
    {
        return pack((bool) $this->byteorder ? 'e*' : 'E*', ...$values);
    }

}
