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
class Product
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     * @Flow\Validate(type="Text")
     * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=256})
     */
    protected $title;

    /**
     * @var string
     * @ORM\Column(type="text", length=100)
     * @Flow\Validate(type="Text")
     * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=100})
     */
    protected $description;

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
     * @var Category
     * @ORM\ManyToOne(targetEntity="Acme\Ecommerce\Domain\Model\Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false)
     * @Flow\Validate(type="NotEmpty")
     */
    protected $category;


    /**
     * @var \Doctrine\Common\Collections\ArrayCollection<Acme\Ecommerce\Domain\Model\Image>
     * @ORM\OneToMany(targetEntity="Acme\Ecommerce\Domain\Model\Image", mappedBy="product", cascade={"persist"})
     */
    protected $images;

    /**
     * @var Curency
     * @ORM\ManyToOne(targetEntity="Acme\Ecommerce\Domain\Model\Curency")
     * @ORM\JoinColumn(name="curency_id", referencedColumnName="id", nullable=false)
     * @Flow\Validate(type="NotEmpty")
     */
    protected $curency;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return Product
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
     * Set description
     *
     * @param string $description
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return Product
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set category
     *
     * @param Category $category
     * @return Product
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add images
     *
     * @param Image $images
     * @return Product
     */
    public function addImage(Image $images)
    {
        if (count($this->images) <= 5) {
            $this->images[] = $images;
            $images->setProduct($this);
        }
        return $this;
    }

    /**
     * Remove images
     *
     * @param Image $images
     */
    public function removeImage(Image $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set curency
     *
     * @param Curency $curency
     * @return Product
     */
    public function setCurency(Curency $curency)
    {
        $this->curency = $curency;

        return $this;
    }

    /**
     * Get curency
     *
     * @return Curency
     */
    public function getCurency()
    {
        return $this->curency;
    }
}
