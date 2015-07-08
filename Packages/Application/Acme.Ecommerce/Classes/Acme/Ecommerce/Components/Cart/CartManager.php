<?php
/**
 * Created by PhpStorm.
 * User: zemfr
 * Date: 07.07.15
 * Time: 16:35
 */

namespace Acme\Ecommerce\Components\Cart;

use TYPO3\Flow\Annotations as Flow;

/**
 * Class CartManager
 * @package Acme\Ecommerce\Components\Cart
 * @Flow\Scope("session")
 */
class CartManager implements CartInteface
{
    protected $products = array();

    /**
     * @return array
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param integer $productId
     * @param $count
     * @return bool
     * @Flow\Session(autoStart = TRUE)
     */
    public function addProduct($productId, $count)
    {
        if ((int)$count > 0) {
            $this->products[(int)$productId] = (int)$count;
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int $productId
     * @return integer
     */
    public function removeProduct($productId)
    {
        $productCount = 0;
        if (isset($this->products[$productId])) {
            $productCount = $this->products[$productId];
            unset($this->products[$productId]);
        }
        return $productCount;
    }

    public function clear()
    {
        $this->products = [];
    }

}