<?php
/**
 * Calculator logic.
 *
 * @package Freight_Cost_Calculator
 */

defined( 'ABSPATH' ) || exit;

class FCC_Calculator {

    /**
     * Convert weight to kilograms.
     *
     * @param float  $value Weight value.
     * @param string $unit  Weight unit.
     * @return float
     */
    public static function to_kg( $value, $unit ) {
        $value = (float) $value;

        switch ( $unit ) {
            case 'g':
                return $value / 1000;

            case 'lb':
                return $value * 0.45359237;

            case 'oz':
                return $value * 0.028349523125;

            case 'kg':
            default:
                return $value;
        }
    }

    /**
     * Convert dimension to centimeters.
     *
     * @param float  $value Dimension value.
     * @param string $unit  Dimension unit.
     * @return float
     */
    public static function to_cm( $value, $unit ) {
        $value = (float) $value;

        if ( 'in' === $unit ) {
            return $value * 2.54;
        }

        return $value;
    }

    /**
     * Calculate volumetric weight.
     *
     * @param float $length  Length in centimeters.
     * @param float $width   Width in centimeters.
     * @param float $height  Height in centimeters.
     * @return float
     */
    public static function volumetric_weight( $length, $width, $height ) {
        return ( $length * $width * $height ) / 6000;
    }

    /**
     * Get chargeable weight.
     *
     * @param float $actual_weight     Actual weight in kilograms.
     * @param float $volumetric_weight Volumetric weight in kilograms.
     * @return float
     */
    public static function chargeable_weight( $actual_weight, $volumetric_weight ) {
        return max( $actual_weight, $volumetric_weight );
    }

    /**
     * Calculate final shipping cost.
     *
     * @param float $chargeable_weight Chargeable weight.
     * @param float $shipping_rate     Shipping rate per kilogram.
     * @return float
     */
    public static function shipping_cost( $chargeable_weight, $shipping_rate ) {
        return $chargeable_weight * $shipping_rate;
    }
}
