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
class Curency
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=10)
     * @Flow\Validate(type="StringLength", options={"minimum"=1, "maximum"=10})
     */
    protected $name;


    /**
     * @var string
     * @ORM\Column(type="decimal", scale=5)
     * @Flow\Validate(type="NotEmpty")
     */
    protected $exchangeRates;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Curency
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set exchangeRates
     *
     * @param string $exchangeRates
     * @return Curency
     */
    public function setExchangeRates($exchangeRates)
    {
        $this->exchangeRates = $exchangeRates;

        return $this;
    }

    /**
     * Get exchangeRates
     *
     * @return string 
     */
    public function getExchangeRates()
    {
        return $this->exchangeRates;
    }


    /**
     * Add products
     *
     * @param Product $products
     * @return Category
     */
    public function addProduct(Product $products)
    {
        $this->products[] = $products;

        return $this;
    }
}
