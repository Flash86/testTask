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
class Invoice
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
     * @var User
     * @ORM\ManyToOne(targetEntity="Acme\Ecommerce\Domain\Model\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @Flow\Validate(type="NotEmpty")
     */
    protected $user;

    /**
     * @var Curency
     * @ORM\ManyToOne(targetEntity="Acme\Ecommerce\Domain\Model\Curency")
     * @ORM\JoinColumn(name="curency_id", referencedColumnName="id", nullable=false)
     * @Flow\Validate(type="NotEmpty")
     */
    protected $curency;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection<Acme\Ecommerce\Domain\Model\InvoiceItem>
     * @ORM\OneToMany(targetEntity="Acme\Ecommerce\Domain\Model\InvoiceItem", mappedBy="invoice", cascade={"persist"})
     */
    protected $items;

    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * @param $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Add item
     *
     * @param InvoiceItem $item
     * @return InvoiceItem
     */
    public function addItem(InvoiceItem $item)
    {
        $item->setInvoice($this);
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove items
     *
     * @param InvoiceItem $item
     */
    public function removeItem(InvoiceItem $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection<Acme\Ecommerce\Domain\Model\Product>
     */
    public function getItems()
    {
        return $this->items;
    }


}
