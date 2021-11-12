<?php

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

$productCatalog = [
    'R01'   =>  32.95,
    'G01'   =>  24.95,
    'B01'   =>   7.95,
];

$offers = [
    ['offerType' => OfferType::DOUBLE_PRODUCT_OFFER, 'params' => 'R01']
];

// Now delivery rule can be only on. If we'll have some rules, we can use same format as for offers
$deliveryChargeRules = [
    'deliveryRuleType' => DeliveryRuleType::BASED_ON_AMOUNT_TABLE,
    'params' => [
        ['amount' => 90, 'cost' => 0.00],
        ['amount' => 50, 'cost' => 2.95],
        ['amount' => 0,  'cost' => 4.95],
    ]
];

try {
    $basket = new Basket($productCatalog, $deliveryChargeRules, $offers);
    $basket->addProduct('B01');
    $basket->addProduct('G01');
    var_dump($basket->total());

    $basket = new Basket($productCatalog, $deliveryChargeRules, $offers);
    $basket->addProduct('R01');
    $basket->addProduct('R01');
    var_dump($basket->total());

    $basket = new Basket($productCatalog, $deliveryChargeRules, $offers);
    $basket->addProduct('R01');
    $basket->addProduct('G01');
    var_dump($basket->total());

    $basket = new Basket($productCatalog, $deliveryChargeRules, $offers);
    $basket->addProduct('B01');
    $basket->addProduct('B01');
    $basket->addProduct('R01');
    $basket->addProduct('R01');
    $basket->addProduct('R01');
    var_dump($basket->total());
}
catch(BasketException $e) {
    var_dump("Problem in basket work. " . $e->getMessage());
}
catch(Exception $e){
    var_dump("Unexpected problem. " . $e->getMessage());
}