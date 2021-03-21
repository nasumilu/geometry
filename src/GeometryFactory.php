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

namespace Nasumilu\Spatial\Geometry;

/**
 * Used to build Geometry objects
 */
interface GeometryFactory
{

    /**
     * Create a Geometry from an scalar argument
     * 
     * @param mixed $args
     * @return Geometry
     */
    public function create($args): Geometry;
    
    /**
     * Create a Point from an array of float values
     * @param float[] $coordinates
     * @return Point
     */
    public function createPoint(array $coordinates): Point;
    
    /**
     * Create a LineString from a multidimensional array of float values
     * @param array[] $coordinates
     * @return LineString
     */
    public function createLineString(array $coordinates): LineString;
    
    /**
     * Create a Polygon from a multidimensional array of float values
     * @param array $linestrings
     * @return Polygon
     */
    public function createPolygon(array $linestrings): Polygon;
    
    /**
     * Create a MultiPolygon from a multidimensional array of float values
     * @param array $coordinates
     * @return MultiPoint
     */
    public function createMultiPoint(array $coordinates): MultiPoint;
    
    /**
     * Create a MulitLineString from a multidimensional array of float values
     * @param array $linestrings
     * @return MultiLineString
     */
    public function createMultiLineString(array $linestrings): MultiLineString;
    
    /**
     * Create a MultiPolygon from a multidimensional array of float values
     * @param array $polygons
     * @return MultiPolygon
     */
    public function createMultiPolygon(array $polygons): MultiPolygon;
    
    /**
     * Create a GeometryCollection from an array
     * @param array $geometries
     * @return GeometryCollection
     */
    public function createGeometryCollection(array $geometries): GeometryCollection;
    
    /**
     * Get the GeometryFactory objects PrecisionModel
     * @return PrecisionModel
     */
    public function getPrecisionModel(): PrecisionModel;
    
    /**
     * Gets the GeometryFactory objects CoordianteSystem
     * @return CoordinateSystem
     */
    public function getCoordianteSystem(): CoordinateSystem;
    
    /**
     * Get the GeometryFacotry objects SpatialEnginez
     * @return SpatialEngine
     */
    public function getSpatialEngine(): SpatialEngine;
    
}
