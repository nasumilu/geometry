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

use InvalidArgumentException;
use function ini_get;
use function round;
use function is_numeric;
use function is_nan;
use function floatval;

/**
 * The precision model for Coordinate values.
 */
final class PrecisionModel
{

    /**
     * Round a value up away from zero, when it is half way there.
     */
    public const ROUND_UP = PHP_ROUND_HALF_UP;

    /**
     * Round a value down towards zero, when it is half way there.
     */
    public const ROUND_DOWN = PHP_ROUND_HALF_DOWN;

    /**
     * The number of decimal digits (Default = 6). 
     */
    private $precision;

    /**
     * The mode used by this precision model (Default = ROUND_UP.
     */
    private $mode;

    /**
     * Creates a PrecisionModel with a precision of <code>$precision<code> using 
     * the rounding mode <code>$mode</code>.
     * 
     * If the <code>$precision</code> argument is greater than the php.ini 
     * <code>precision</code> setting than an \InvalidArgumentException is raised.
     * 
     * If the <code>$mode</code> argument is not valid (1 or 2) than an
     * \InvalidArgumentException is raised.
     * 
     * @link https://www.php.net/manual/en/ini.core.php#ini.precision php.ini precision
     * @link https://www.php.net/manual/en/function.round.php php round function
     * @param int $precision The number decimal digits
     * @param int $mode The rounding mode. 
     * @throws InvalidArgumentException when precision is greater than the php.ini
     * precision value or the mode is not valid.
     */
    public function __construct(int $precision = 6, int $mode = self::ROUND_UP)
    {
        $this->setPrecision($precision);
        $this->setMode($mode);
    }

    //Sets the models precision
    private function setPrecision(int $precision)
    {
        if (ini_get('precision') < $precision) {
            throw new InvalidArgumentException(sprintf('Invalid precision! '
                                    . 'The core php.ini `precision` setting is %d, less than this '
                                    . 'model\'s precision of %d.', ini_get('precision'), $precision));
        }
        $this->precision = $precision;
    }

    //Sets the models reound mode
    private function setMode(int $mode)
    {
        if ($mode != self::ROUND_DOWN && $mode !== self::ROUND_UP) {
            throw new InvalidArgumentException(sprintf('Invalid mode! '
                                    . 'The precision model\'s mode must be either %d or %d, '
                                    . 'found %d.', self::ROUND_UP, self::ROUND_DOWN, $mode));
        }
        $this->mode = $mode;
    }

    /**
     * Gets the PrecisionModel's precision value.
     * 
     * The precision value is the number digits after a decimal place. 
     * (e.g. Decimal Digits)
     * @return int The number of digits after the decimal place.
     */
    public function getPrecision(): int
    {
        return $this->precision;
    }

    /**
     * Get the PrecisionModel's rounding mode. 
     * 
     * The mode tells the PrecisionModel to either round the number half up or
     * down.
     *
     * @return int
     */
    public function getMode(): int
    {
        return $this->mode;
    }

    public function makePrecise($value): float
    {
        if (is_string($value) && !is_numeric($value)) {
            return NAN;
        }

        return round(floatval($value), $this->precision, $this->mode);
    }

}
