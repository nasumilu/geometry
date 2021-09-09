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

use PHPUnit\Framework\TestCase;
use Nasumilu\Spatial\Geometry\PrecisionModel;

/**
 * PrecisionModelTest
 * @covers \Nasumilu\Spatial\Geometry\PrecisionModel
 */
class PrecisionModelTest extends TestCase
{
    
    public function testConstructor() {
        $precisionModel = new PrecisionModel();
        $this->assertEquals(6, $precisionModel->getPrecision());
        $this->assertEquals(PrecisionModel::ROUND_UP, $precisionModel->getMode());
    }
    
    /**
     * @testWith [29.12345697842, 29.123457]
     *           [-85.12875423, -85.128754]
     *           [0.01299999999999, 0.0130000000000]
     *           ["Not A Number", -1]
     * @param float $value
     */
    public function testMakePrecise($value, float $expected) {
        $precisionModel = new PrecisionModel();
        $precise = $precisionModel->makePrecise($value);
        if(-1 == $expected) {
            $this->assertNan($precise);
        } else {
            $this->assertEquals($expected, $precise);
        }
        
    }
    
    /**
     * @testWith ["String Value", true]
     *           [12.3, false]
     *           ["10", false]
     *           ["Ten", true]
     */
    public function testIsNan($value, bool $is_nan) {
        $precisionModel = new PrecisionModel();
        $precise = $precisionModel->makePrecise($value);
        if($is_nan) {
            $this->assertIsFloat($precise);
            $this->assertNan($precise);
        } else {
            $this->assertFalse(is_nan($precise));
            $this->assertIsFloat($precise);
        }
    }
    
    public function testPrecisionGreaterThanIniPrecision() {
        $this->expectException(\InvalidArgumentException::class);
        $precisionModel = new PrecisionModel(99);
    }
    
    public function testSetMode() {
        $this->expectException(\InvalidArgumentException::class);
        $precisionModel = new PrecisionModel(8, PrecisionModel::ROUND_DOWN + 1);
    }
}
