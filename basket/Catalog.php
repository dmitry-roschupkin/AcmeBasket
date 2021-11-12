<?php

namespace basket;

use basket\exception\CatalogException;

/**
 * The Catalog class
 * It works with product catalog and product prices. Now catalog it stored into it own member, but it can be storing in
 * DB or other storage in future
 *
 * Catalog is a singleton (for demonstrate work with patterns).
 */
class Catalog
{
    /**
     * @var Catalog for storing the only one object of singleton Catalog class
     */
    private static $catalog = null;

    /**
     * @var array associative array 'code' => 'price'
     */
    private $productPrices = null;

    /**
     * Constructor
     */
    private function __construct()
    {
    }

    /**
     * Initialize catalog with products
     * @param $productPrices
     * @throws CatalogException
     */
    public function setProductPrices($productPrices)
    {
        if (!is_array($productPrices)) {
            throw new CatalogException("Invalid product catalogue data format");
        }

        foreach ($productPrices as $price) {
            if (!is_numeric($price) || $price < 0) {
                throw new CatalogException("Product catalogue prises can't be < 0");
            }
        }

        $this->productPrices = $productPrices;
    }

    /**
     * Get a single Catalog object
     * @return Catalog objects of Catalog class
     */
    final public static function getCatalog()
    {
        if (!self::$catalog) {
            static::$catalog = new Catalog();
        }

        return static::$catalog;
    }

    /**
     * Get catalog product price
     * @param string $code product code
     * @return float product price, if product found, otherwise false
     */
    public function getProductPrice($code)
    {
        if (isset($this->productPrices[$code])) {
            return (float)$this->productPrices[$code];
        }

        return false;
    }
}