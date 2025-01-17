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

namespace Nasumilu\Spatial\Serializer\Normalizer;

use function is_subclass_of;
use function array_merge;
use function get_class;
use ArrayObject;
use Symfony\Component\Serializer\Normalizer\{
    NormalizerInterface,
    DenormalizerInterface
};
use Nasumilu\Spatial\Geometry\{
    Geometry,
    Point,
    LineString,
    Polygon,
    MultiPoint,
    MultiLineString,
    MultiPolygon,
    GeometryCollection
};

/**
 * GeometryNormalizer (de)normalizes a Geometry object.
 *
 * [
 *  'type' => &lt;string|int&gt;,
 *  'crs'  => [
 *      'srid'     => &lt;integer&gt;,
 *      '3d'       => &lt;boolean&gt;,
 *      'measured' => &lt;boolean&gt;
 *  ],
 *  'coordinates | geometries'] => &lt;array&gt;
 * ]
 */
class GeometryNormalizer implements NormalizerInterface, DenormalizerInterface
{
    
    public const FACTORY = 'factory';
    
    /**
     * {@inheritDoc}
     */
    public function normalize(mixed $object, ?string $format = null, array $context = array()): ArrayObject|array|string|int|float|bool|null
    {
        $crs = $object->getFactory()->getCoordianteSystem();
        return array_filter(array_merge([
            'type' => $object->getGeometryType(),
            'binary_type' => constant(get_class($object)."::WKB_TYPE"),
            'crs' => [
                'srid' => $crs->getSrid(),
                '3d' => $crs->is3D(),
                'measured' => $crs->isMeasured(),
                'dimension' => $crs->getCoordinateDimension()]
                        ],
                        $this->normalizeCoordinates($object)
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Geometry;
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): Geometry
    {
        if (null === $factory = $context['factory'] ?? null) {
            throw new \InvalidArgumentException("Must have a geomtry factory in context!");
        }
        return $factory->create($data);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === Geometry::class || is_subclass_of($type, Geometry::class, true);
    }

    /**
     * Normalizes a Geometry objects coordinates values
     *
     * @param Geometry $geometry
     * @return array
     */
    public function normalizeCoordinates(Geometry $geometry): array
    {
        if ($geometry->isEmpty()) {
            return [];
        }
        $type = $geometry->getGeometryType();
        $coordinates = call_user_func([$this, "normalize$type"], $geometry);
        $key = GeometryCollection::WKT_TYPE === $type ? 'geometries' : 'coordinates';
        return [ $key => $coordinates ];
    }

    /**
     * Normalizes a Point objects coordinate values
     * @param Point $point
     * @return array
     */
    public function normalizePoint(Point $point): array
    {
        $coordinates = [$point->getX(), $point->getY()];
        if ($point->is3D()) {
            $coordinates[] = $point->getZ();
        }
        if ($point->isMeasured()) {
            $coordinates[] = $point->getM();
        }
        return $coordinates;
    }

    /**
     * Normalizes a LineString objects coordinate values
     * @param LineString $linestring
     * @return array
     */
    public function normalizeLineString(LineString $linestring): array
    {
        $coordinates = [];
        foreach ($linestring as $point) {
            $coordinates[] = $this->normalizePoint($point);
        }
        return $coordinates;
    }

    /**
     * Normalizes a Polygon objects coordinate values
     * @param Polygon $polygon
     * @return array
     */
    public function normalizePolygon(Polygon $polygon): array
    {
        $coordinates = [];
        foreach ($polygon as $linestring) {
            $coordinates[] = $this->normalizeLineString($linestring);
        }
        return $coordinates;
    }

    /**
     * Normalizes a MultiPoint objects coordinate values
     * @param MultiPoint $polygon
     * @return array
     */
    public function normalizeMultiPoint(MultiPoint $multipoint): array
    {
        $coordinates = [];
        foreach ($multipoint as $point) {
            $coordinates[] = $this->normalizePoint($point);
        }
        return $coordinates;
    }

    /**
     * Normalizes a MultiLineString objects coordinate values
     * @param MultiLineString $polygon
     * @return array
     */
    public function normalizeMultiLineString(MultiLineString $multilinestring): array
    {
        $coordinates = [];
        foreach ($multilinestring as $linestring) {
            $coordinates[] = $this->normalizeLineString($linestring);
        }
        return $coordinates;
    }

    /**
     * Normalizes a MultiPolygon objects coordinate values
     * @param MultiPolygon $multipolygon
     * @return array
     */
    public function normalizeMultiPolygon(MultiPolygon $multipolygon): array
    {
        $coordinates = [];
        foreach ($multipolygon as $polygon) {
            $coordinates[] = $this->normalizePolygon($polygon);
        }
        return $coordinates;
    }

    public function normalizegeometrycollection(GeometryCollection $collection): array
    {
        $geometries = [];
        foreach($collection as $geometry) {
            $geometries[] = $this->normalize($geometry);
        }
        return $geometries;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Geometry::class => true,
            Point::class => true,
            LineString::class => true,
            Polygon::class => true,
            MultiPoint::class => true,
            MultiLineString::class => true,
            MultiPolygon::class => true,
            GeometryCollection::class => true
        ];
    }
}
