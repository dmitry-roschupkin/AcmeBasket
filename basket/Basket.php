<?php

namespace basket;

use basket\deliveryRule\base\RuleInterface;
use basket\deliveryRule\DeliveryRuleFactory;
use basket\exception\BasketException;
use basket\exception\CatalogException;
use basket\exception\DeliveryRuleException;
use basket\exception\OfferException;
use basket\offer\base\OfferInterface;
use basket\offer\OfferFactory;

/**
 * Basket implementation class
 */
class Basket
{
    /**
     * @var RuleInterface delivery charge rule object
     */
    private $deliveryChargeRules = null;

    /**
     * @var array of OfferInterface objects
     */
    private $offers = [];

    /**
     * @var array associative array with products, format example:
     * [
     *      ['R01'] => [ ['price'] => 5.57, ['count'] => 1 ],
     *      ...
     * ]
     */
    private $products = [];

    /**
     * Constructor for initializing
     * @param array $productCatalog 'product code' => 'price' associative array
     * @param array $deliveryChargeRules delivery rules data, format example:
     * [
     *      'deliveryRuleType' => DeliveryRuleType::BASED_ON_AMOUNT_TABLE, // const of one of implemented rules
     *       // params for implemented rule
     *      'params' => [
     *           ['amount' => 90, 'cost' => 0.00],
     *           ['amount' => 50, 'cost' => 2.95],
     *           ['amount' => 0,  'cost' => 4.95],
     *      ]
     * ]
     * @param array $offers list of implemented offers (it's constants) which need to apply
     * @throws BasketException
     */
    public function __construct($productCatalog, $deliveryChargeRules, $offers)
    {
        $this->init($productCatalog, $deliveryChargeRules, $offers);
    }

    /**
     * Initializing function. Parameters format are the same on constructor
     * @param array $productCatalog
     * @param array$deliveryChargeRules
     * @param array $offers
     * @throws BasketException
     */
    private function init($productCatalog, $deliveryChargeRules, $offers)
    {
        $this->initCatalog($productCatalog);
        $this->initDelivery($deliveryChargeRules);
        $this->initOffers($offers);
    }

    /**
     * Initializing catalog. Parameters format are the same on constructor
     * @param $productCatalog
     * @throws BasketException
     */
    private function initCatalog($productCatalog)
    {
        try {
            Catalog::getCatalog()->setProductPrices($productCatalog);
        } catch (CatalogException $e) {
            throw new BasketException("Invalid product catalogue data. " . $e->getMessage());
        }
    }

    /**
     * Initializing delivery. Parameters format are the same on constructor
     * @param array $deliveryChargeRules
     * @throws BasketException
     */
    private function initDelivery($deliveryChargeRules)
    {
        if (!is_array($deliveryChargeRules)
            || !isset($deliveryChargeRules['deliveryRuleType'])
            || !isset($deliveryChargeRules['params'])
        )
        {
            throw new BasketException("Invalid delivery charge rules data format");
        }

        try {
            $this->deliveryChargeRules = DeliveryRuleFactory::createDelivery(
                $deliveryChargeRules['deliveryRuleType'],
                $deliveryChargeRules['params']
            );
        }
        catch (DeliveryRuleException $e) {
            throw new BasketException("Invalid delivery charge rules data format. " . $e->getMessage());
        }
    }

    /**
     * Initializing offer. Parameters format are the same on constructor
     * @param array $offers
     * @throws BasketException
     */
    private function initOffers($offers)
    {
        if (!is_array($offers))
        {
            throw new BasketException("Invalid special offers data format");
        }

        try {
            foreach ($offers as $offer) {
                if (!is_array($offer)
                || !isset($offer['offerType'])
                || !isset($offer['params'])
                )
                {
                    throw new BasketException("Invalid special offers data format");
                }

                $this->offers[] = OfferFactory::createOffer($offer['offerType'], $offer['params']);
            }
        }
        catch (OfferException $e) {
            throw new BasketException("Invalid special offers data format. " . $e->getMessage());
        }
    }

    /**
     * Adding product into cart
     * @param string $code product code
     * @param int $count product count
     * @return bool true of all ok
     * @throws BasketException
     */
    public function addProduct($code, $count = 1)
    {
        if (isset($this->products[$code])) {
            $this->products[$code]['count']+= $count;
        } else {
            $productPrice = Catalog::getCatalog()->getProductPrice($code);

            if ($productPrice === false) {
                throw new BasketException("Adding product not found in catalog");
            }

            $this->products[$code] = [
                'price' => $productPrice,
                'count' => $count
            ];
        }

        return true;
    }

    /**
     * Calculate basket total amount
     * @return float total amount
     * @throws BasketException
     */
    public function total()
    {
        $productSum = 0;
        foreach ($this->products as $productData) {
            $productSum += $productData['price'] * $productData['count'];
        }

        $offersDiscount = $this->calculateOffers();
        $delivery = $this->calculateDelivery($productSum - $offersDiscount);

        return $productSum - $offersDiscount + $delivery;
    }

    /**
     * Calculate offers discount
     * @return float offers discount
     */
    private function calculateOffers()
    {
        $offersDiscount = 0;
        foreach ($this->offers as $offer) {
            /**
             * @var OfferInterface $offer
             */
            $offersDiscount += $offer->apply($this->products);
            // I am not catch exception there, because I form $this->products myself and format will be correct
            // It will be cached in up level if something will wrong
        }
        return $offersDiscount;
    }

    /**
     * Calculate delivery cost
     * @param float $amount basket amount include all offers
     * @return float delivery cost
     * @throws BasketException
     */
    private function calculateDelivery($amount)
    {
        /**
         * @var RuleInterface $this->deliveryChargeRules
         */
        $result = $this->deliveryChargeRules->calculate($amount);
        if ($result === false) {
            throw new BasketException("Can't calculate delivery");
        }

        return $result;
    }
}
