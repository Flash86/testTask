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
class Image
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
     * @ORM\Column(type="string",nullable=false)
     * @Flow\Validate(type="NotEmpty")
     */
    protected $imageUrl;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=100})
     */
    protected $title;

    /**
     * @var Product
     * @ORM\ManyToOne(targetEntity="Acme\Ecommerce\Domain\Model\Product", inversedBy="images")
     *
     */
    protected $product;

    function __construct($imageUrl = null, $title = null)
    {
        $this->title = $title;
        $this->imageUrl = $imageUrl;
    }

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
     * Set imageUrl
     *
     * @param string $imageUrl
     * @return Image
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * Get imageUrl
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Image
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set product
     *
     * @param Product $product
     * @return Image
     */
    public function setProduct(Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
