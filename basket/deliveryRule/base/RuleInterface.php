<?php

namespace basket\deliveryRule\base;

/**
 * Interface for delivery rules classes
 */
interface RuleInterface
{
    /**
     * Calculate delivery cost
     * @param float $amount amount for calculate delivery
     * @return float|false delivery cost if all ok, otherwise false.
     */
    public function calculate($amount);
}