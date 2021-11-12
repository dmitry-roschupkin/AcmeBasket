<?php

namespace basket\offer;

use basket\exception\OfferException;
use basket\offer\base\OfferInterface;

/**
 * DoubleProductOffer is the special offer implementation class, for example "buy one red widget,
 * get the second half price"
 */
class DoubleProductOffer implements OfferInterface
{
    /**
     * @var string offer product code
     */
    private $product;

    /**
     * Constructor for initialization
     * @param string $product offer product code
     */
    public function __construct($product)
    {
        $this->product = $product;
    }

    /**
     * {@inheritdoc}
     * @throws OfferException
     */
    public function apply($products)
    {
        $discount = 0.00;

        if (isset($products[$this->product])) {
            if (!isset($products[$this->product]['count'])
                || !isset($products[$this->product]['price'])
                || !is_numeric($products[$this->product]['price'])
                || $products[$this->product]['price'] < 0
            ) {
                throw new OfferException("Invalid product data format");
            }

            $productHalfPrice = round($products[$this->product]['price'] / 2, 2, PHP_ROUND_HALF_DOWN);
            $productDiscount = $products[$this->product]['price'] - $productHalfPrice;

            $discount = (int)($products[$this->product]['count'] / 2) * $productDiscount;
        }

        return $discount;
    }
}