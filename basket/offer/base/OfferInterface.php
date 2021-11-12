<?php

namespace basket\offer\base;

/**
 * Interface for offers classes
 */
interface OfferInterface
{
    /**
     * Calculate offer discount
     * @param array $products basket products. See Basket calls $products member format for more detail
     * @return float discount
     */
    public function apply($products);
}
