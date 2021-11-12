<?php

namespace basket\deliveryRule;

use basket\deliveryRule\base\RuleInterface;
use basket\exception\DeliveryRuleException;

/**
 * BasedOnAmountTable implementing relative delivery cost from amount
 */
class BasedOnAmountTable implements RuleInterface
{
    /**
     * @var array associative array with amounts and cost
     */
    private $tableAmountCost;

    /**
     * Constructor
     * @param array $tableAmountCost associative array with amounts and cost. Example format:
     * [
     *    ['amount' => 90, 'cost' => 0.00],
     *    ['amount' => 50, 'cost' => 2.95],
     *    ['amount' => 0,  'cost' => 4.95],
     * ]
     * @throws DeliveryRuleException
     */
    public function __construct($tableAmountCost)
    {
        if (!is_array($tableAmountCost)) {
            throw new DeliveryRuleException("Invalid amount/cost table data format");
        }

        foreach ($tableAmountCost as $amountCost) {
            if (!isset($amountCost['amount']) || !is_numeric($amountCost['amount']) || $amountCost['amount'] < 0
                || !isset($amountCost['cost']) || !is_numeric($amountCost['cost']) || $amountCost['cost'] < 0
            )
                throw new DeliveryRuleException("Delivery amount/cost table data can't be < 0");
        }

        rsort($tableAmountCost);

        $this->tableAmountCost = $tableAmountCost;
    }

    /**
     * {@inheritdoc}
     */
    public function calculate($amount)
    {
        foreach ($this->tableAmountCost as $amountCost) {
            if ((float)$amount >= (float)$amountCost['amount']) {
                return $amountCost['cost'];
            }
        }

        return false;
    }
}