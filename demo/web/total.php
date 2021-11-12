<?php

// TODO: need validate input form data
// In a full-fledged web application it has to do validator classes

/*
 * Simple autoloader for basket classes.
 *
 * If we use composer we have to use
 * require_once dirname(__DIR__).'/vendor/autoload.php';
 */
require(__DIR__ . '/../../Autoloader.php');

use basket\Basket;
use basket\offer\OfferType;
use basket\deliveryRule\DeliveryRuleType;
use basket\exception\BasketException;

// Catalog
$productCatalog = [
    'R01'   =>  (float)$_POST['priceR01'],
    'G01'   =>  (float)$_POST['priceG01'],
    'B01'   =>  (float)$_POST['priceB01'],
];

// Delivery. Now delivery rule can be only on. If we'll have some rules, we can use same format as for offers
$deliveryChargeRules = [
    'deliveryRuleType' => DeliveryRuleType::BASED_ON_AMOUNT_TABLE,
    'params' => []
];

/**
 * Check delivery rule table format. Write warning if something wrong
 * @param int $index number of rule
 */
function checkAndAddDeliveryRule($index)
{
    global $deliveryChargeRules;

    if ($_POST['amount' . $index] != '' && $_POST['cost' . $index] != '') {
        $deliveryChargeRules['params'][] = [
            'amount' => (float)$_POST['amount' . $index],
            'cost' => (float)$_POST['cost' . $index]
        ];
    } else {
        echo "Warning: Invalid delivery Rule $index format, it's ignoring";
    }
}
checkAndAddDeliveryRule('1');
checkAndAddDeliveryRule('2');
checkAndAddDeliveryRule('3');

// Special offers
$offers = [];
if (isset($_POST['offerR01'])) {
    $offers[] = ['offerType' => OfferType::DOUBLE_PRODUCT_OFFER, 'params' => 'R01'];
}
if (isset($_POST['offerG01'])) {
    $offers[] = ['offerType' => OfferType::DOUBLE_PRODUCT_OFFER, 'params' => 'G01'];
}
if (isset($_POST['offerB01'])) {
    $offers[] = ['offerType' => OfferType::DOUBLE_PRODUCT_OFFER, 'params' => 'B01'];
}

$result = '';
// Basket
try {
    $basket = new Basket($productCatalog, $deliveryChargeRules, $offers);

    if (isset($_POST['countR01']) && $_POST['countR01'] > 0) {
        $basket->addProduct('R01', $_POST['countR01']);
    }
    if (isset($_POST['countG01']) && $_POST['countG01'] > 0) {
        $basket->addProduct('G01', $_POST['countG01']);
    }
    if (isset($_POST['countB01']) && $_POST['countB01'] > 0) {
        $basket->addProduct('B01', $_POST['countB01']);
    }

    $result = $basket->total();
}
catch(BasketException $e) {
    $result = "Problem in basket work. " . $e->getMessage();
}
catch(Exception $e){
    $result = "Unexpected problem. " . $e->getMessage();
}

require 'index.php';