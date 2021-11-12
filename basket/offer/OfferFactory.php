<?php

namespace basket\offer;

use basket\exception\OfferException;
use basket\offer\base\OfferInterface;

/**
 * Class factory for creating objects implements OfferInterface
 */
class OfferFactory
{
    /**
     * Create offer object
     * @param int $offer implemented offer type
     * @param null $params options for special offer type class object
     * @return OfferInterface object implements of OfferInterface interface
     * @throws OfferException
     */
    public static function createOffer($offer, $params)
    {
        switch ($offer) {
            case OfferType::DOUBLE_PRODUCT_OFFER:
                return new DoubleProductOffer($params);
            default:
                throw new OfferException("Can't create offer object of type \"" . $offer . "\"");
        }
    }
}