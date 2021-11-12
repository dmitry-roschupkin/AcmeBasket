<?php

namespace basket\deliveryRule;

use basket\deliveryRule\base\RuleInterface;
use basket\exception\DeliveryRuleException;

/**
 * Class factory for creating objects implements RuleInterface
 */
class DeliveryRuleFactory
{
    /**
     * Create delivery rule object
     * @param int $deliveryRule implemented delivery rule type
     * @param null $params options for special delivery rule type class object
     * @return RuleInterface object implements of RuleInterface interface
     * @throws DeliveryRuleException
     */
    public static function createDelivery($deliveryRule, $params = null)
    {
        switch ($deliveryRule) {
            case DeliveryRuleType::BASED_ON_AMOUNT_TABLE:
                return new BasedOnAmountTable($params);
            default:
                throw new DeliveryRuleException("Can't create delivery rule object of type \"" .
                    $deliveryRule . "\"");
        }
    }
}