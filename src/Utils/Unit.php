<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/31
 * Time: 17:33
 */

namespace Nebulas\Utils;

use Litipk\BigNumbers\Decimal;

define("BNScale",18);

class Unit
{

     const UnitMap = array (
        'none'=>       '0',
        'None'=>       '0',
        'wei'=>        '1',
        'Wei'=>        '1',
        'kwei'=>       '1000',
        'Kwei'=>       '1000',
        'mwei'=>       '1000000',
        'Mwei'=>       '1000000',
        'gwei'=>       '1000000000',
        'Gwei'=>       '1000000000',
        'nas'=>        '1000000000000000000',
        'NAS'=>        '1000000000000000000',
     );

     private static function unitValue(string $unit = 'nas'){
         $unit = strtolower($unit);
         if(empty(Unit::UnitMap[$unit]))
             throw new \Exception('The unit undefined, please use the following units:' . PHP_EOL . Utils::JsonEncode(Unit::UnitMap, JSON_PRETTY_PRINT));
         return Unit::UnitMap[$unit];
     }

    /**
     * Change number from unit to wei.
     * For example:
     * $wei = Unit::toBasic('1', 'kwei'); // $wei = 1000
     *
     * @param string $number
     * @param string $unit
     * @return string the result value in unit of wei
     * @throws \Exception
     */
    static function toBasic(string $number, string $unit){
        $value = Decimal::fromString($number);
        $unitValue = Decimal::fromString(static::unitValue($unit));
        $result = $value->mul($unitValue, 0);   //remove decimal
        return $result->__toString();

     }

    /**
     * @param string $number
     * @return string
     * @throws \Exception
     */
    static function nasToBasic(string $number){
         return static::toBasic($number, 'nas');
     }

    /**
     * Change number from wei to unit.
     * For example:
     * $result = Unit::fromBasic('1010', 'kwei'); // $result = '1.01'
     *
     * @param string $number
     * @param string $unit
     * @return string
     * @throws \Exception
     */
    static function fromBasic(string $number, string $unit){
        $value = Decimal::fromString($number);
        $unitValue = Decimal::fromString(static::unitValue($unit));
        $result = $value->div($unitValue, BNScale);
        return $result->__toString();
    }

    /**
     * @param string $number
     * @param string $unit
     * @return string
     * @throws \Exception
     */
     static function toNas(string $number, string $unit){
         $wei = static::toBasic($number,$unit);
         return static::fromBasic($wei,'nas');

     }


}