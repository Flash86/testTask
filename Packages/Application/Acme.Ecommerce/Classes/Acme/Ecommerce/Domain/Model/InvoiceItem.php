<?php
/**
 * Created by PhpStorm.
 * User: zemfr
 * Date: 04.07.15
 * Time: 17:09
 */

namespace Acme\Ecommerce\Domain\Model;

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class InvoiceItem
{

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Invoice
     * @ORM\ManyToOne(targetEntity="Acme\Ecommerce\Domain\Model\Invoice", inversedBy="items")
     */
    protected $invoice;

    /**
     * @var \Acme\Ecommerce\Domain\Model\Product>
     * @ORM\OneToOne(targetEntity="Acme\Ecommerce\Domain\Model\Product")
     **/
    protected $product;

    /**
     * @var Curency
     * @ORM\ManyToOne(targetEntity="Acme\Ecommerce\Domain\Model\Curency")
     * @ORM\JoinColumn(name="curency_id", referencedColumnName="id", nullable=false)
     * @Flow\Validate(type="NotEmpty")
     */
    protected $curency;

    /**
     * @var string
     * @ORM\Column(type="decimal", scale=2)
     * @Flow\Validate(type="NotEmpty")
     */
    protected $price;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=false)
     * @Flow\Validate(type="Number")
     * @Flow\Validate(type="NotEmpty")
     */
    protected $quantity;

    /**
     * @param Product $product
     * @param int $count
     */
    function __construct(Product $product, $count = 1)
    {
        $this->product = $product;
        $this->curency = $product->getCurency();
        $this->price = $product->getPrice();
        $this->quantity = $count;
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Curency
     */
    public function getCurency()
    {
        return $this->curency;
    }


    /**
     * @param $curency
     * @return $this
     */
    public function setCurency($curency)
    {
        $this->curency = $curency;
        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function setProduct($product)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @param Invoice $invoice
     * @return $this
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }


}
