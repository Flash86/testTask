<?php
/**
 * Created by PhpStorm.
 * User: zemfr
 * Date: 07.07.15
 * Time: 16:24
 */

namespace Acme\Ecommerce\Components\Cart;


/**
 * Interface CartInteface
 * @package Acme\Ecommerce\Components\Cart
 */
interface CartInteface
{
    /**
     * @return mixed
     */
    public function getProducts();

    /**
     * @param int $productId
     * @param int $count
     * @return mixed
     */
    public function addProduct($productId, $count);

    /**
     * @param int $productId
     * @return mixed
     */
    public function removeProduct($productId);

    /**
     * @return mixed
     */
    public function clear();
}