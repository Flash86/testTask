<?php
namespace Acme\Ecommerce\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Acme.Ecommerce".        *
 *                                                                        *
 *                                                                        */

use Acme\Ecommerce\Components\Auth\UserAuthSession;
use Acme\Ecommerce\Domain\Model\Curency;
use Acme\Ecommerce\Domain\Model\Invoice;
use Acme\Ecommerce\Domain\Model\InvoiceItem;
use Acme\Ecommerce\Domain\Model\Product;
use Acme\Ecommerce\Helpers\ResponseHelp;
use TYPO3\Flow\Mvc\Controller\ActionController;
use TYPO3\Flow\Annotations as Flow;
use Acme\Ecommerce\Domain\Model\User;

/**
 * Class CartController
 * @package Acme\Ecommerce\Controller
 */
class CartController extends ActionController
{
    /**
     * @Flow\Inject
     * @var \Acme\Ecommerce\Components\Cart\CartManager
     */
    protected $cartManager;

    /**
     * @Flow\Inject
     * @var UserAuthSession
     */
    protected $userAuthSession;

    /**
     * @var string
     */
    protected $defaultViewObjectName = 'TYPO3\Flow\Mvc\View\JsonView';

    /**
     * @Flow\Inject
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $entityManager;

    /**
     * @param integer $curencyId
     */
    public function indexAction($curencyId)
    {
        $productInCart = $this->cartManager->getProducts();

        if ($productInCart) {
            /** @var \Acme\Ecommerce\Domain\Model\Curency $curency */
            $curency = $this->entityManager->find('\Acme\Ecommerce\Domain\Model\Curency', $curencyId);
            if (!($curency instanceof Curency)) {
                $this->view->assign('value', ResponseHelp::getErrorMessage("Curency not found"));
                return;
            }

            $products = array();
            foreach ($productInCart as $id => $count) {
                /** @var Product $product */
                $product = $this->entityManager->find('\Acme\Ecommerce\Domain\Model\Product', $id);
                if ($product instanceof Product) {

                    $productCurency = $product->getCurency();
                    $productPrice = $product->getPrice();

                    if ($productCurency->getId() != $curency->getId()) {
                        $productPrice = $productPrice / $productCurency->getExchangeRates() * $curency->getExchangeRates();
                    }

                    $products[] = array(
                        'id' => $product->getId(),
                        'title' => $product->getTitle(),
                        'quantity' => $count,
                        'price' => round($productPrice, 2)
                    );
                }
            }

            $this->view->assign('value', ResponseHelp::getSuccesMessage(
                array(
                    'cart' => [
                        'curency' => $curency->getName(),
                        'products' => $products
                    ]
                )
            ));
        } else {
            $this->view->assign('value', ResponseHelp::getErrorMessage('Card is empty'));
        }
    }

    /**
     * @param integer $productId
     * @param integer $count
     */
    public function addAction($productId, $count = 1)
    {
        //I can write Acme\Ecommerce\Domain\Model\Product::class for parameter, but it available only php 5.5
        $product = $this->entityManager->getRepository('Acme\Ecommerce\Domain\Model\Product')->find($productId);
        if ($product instanceof Product) {
            $quantity = $product->getQuantity();

            $productInCart = $this->cartManager->getProducts();
            if (isset($productInCart[$product->getId()])) {
                $quantity += $productInCart[$product->getId()];
            }

            if ($quantity >= $count) {
                if ($this->cartManager->addProduct($product->getId(), $count)) {
                    $product->setQuantity($quantity - $count);
                    $this->entityManager->flush($product);
                    $this->view->assign('value', ResponseHelp::getSuccesMessage('Product was added'));
                } else {
                    $this->view->assign('value', ResponseHelp::getErrorMessage('Please, set correct count'));
                }
            } elseif ($quantity > 0) {
                $this->view->assign('value', ResponseHelp::getErrorMessage('Sorry, but we have only ' . $quantity . ' units of this products'));
            } else {
                $this->view->assign('value', ResponseHelp::getErrorMessage('Sorry, product not available'));
            }
        } else {
            $this->view->assign('value', ResponseHelp::getErrorMessage('Sorry, product not found'));
        }
    }

    /**
     * @param int $productId
     */
    public function removeAction($productId)
    {
        $product = $this->entityManager->find('\Acme\Ecommerce\Domain\Model\Product', (int)$productId);
        if ($product instanceof Product) {
            $quantity = $product->getQuantity();

            $countInCart = $this->cartManager->removeProduct($product->getId());
            if ($countInCart) {
                $product->setQuantity($quantity + $countInCart);
                $this->entityManager->flush($product);
            }

            $this->view->assign('value', ResponseHelp::getSuccesMessage('Product was removed'));
        } else {
            $this->view->assign('value', ResponseHelp::getErrorMessage('Sorry, product not found'));
        }
    }

    public function clearAction()
    {
        $productIds = $this->cartManager->getProducts();
        foreach ($productIds as $id => $count) {
            $product = $this->entityManager->find('\Acme\Ecommerce\Domain\Model\Product', $id);
            if ($product instanceof Product) {
                $quantity = $product->getQuantity();
                $product->setQuantity($quantity + $count);
                $this->entityManager->persist($product);
            }
        }
        $this->entityManager->flush();

        $this->view->assign('value', ResponseHelp::getSuccesMessage('Cart is empty'));
    }

    /**
     * @param integer $curencyId
     */
    public function orderAction($curencyId)
    {
        $productInCart = $this->cartManager->getProducts();
        if ($productInCart) {
            $userId = $this->userAuthSession->getUserId();
            if (!$userId) {
                $this->view->assign('value', ResponseHelp::getErrorMessage("You need login"));
                $this->response->setStatus(403);
                return;
            }

            $user = $this->entityManager->find('\Acme\Ecommerce\Domain\Model\User', $userId);

            if (!($user instanceof User)) {
                $this->view->assign('value', ResponseHelp::getErrorMessage("Cant search user"));
                return;
            }
            $curency = $this->entityManager->find('\Acme\Ecommerce\Domain\Model\Curency', $curencyId);
            if (!($curency instanceof Curency)) {
                $this->view->assign('value', ResponseHelp::getErrorMessage("Curency not found"));
                return;
            }

            $invoice = new Invoice();
            $invoice->setUser($user);
            $invoice->setCurency($curency);

            foreach ($productInCart as $id => $count) {
                /** @var Product $product */
                $product = $this->entityManager->find('\Acme\Ecommerce\Domain\Model\Product', $id);
                if ($product instanceof Product) {
                    $invoiceItem = new InvoiceItem($product, $count);
                    $invoice->addItem($invoiceItem);
                }
            }

            $this->entityManager->persist($invoice);
            $this->entityManager->flush();

            $this->cartManager->clear();

            $this->view->assign('value', ResponseHelp::getSuccesMessage('Products ordered successfully. OrderId ' . $invoice->getId()));
        } else {
            $this->view->assign('value', ResponseHelp::getErrorMessage('Card is empty'));
        }

    }

    /**
     * @param integer $orderId
     */
    public function getInfoiceAction($orderId)
    {
        $userId = $this->userAuthSession->getUserId();
        $user = $this->entityManager->find('\Acme\Ecommerce\Domain\Model\User', $userId);
        if (!($user instanceof User)) {
            $this->view->assign('value', ResponseHelp::getErrorMessage("Please login"));
            return;
        }

        $invoice = $this->entityManager->find('\Acme\Ecommerce\Domain\Model\Invoice', $orderId);
        if (!($invoice instanceof Invoice) || $invoice->getUser()->getId() != $userId) {
            $this->view->assign('value', ResponseHelp::getErrorMessage("Invoice not found"));
            return;
        }

        $price = 0;
        $products = [];
        /** @var InvoiceItem[] $invoiceItems */
        $invoiceItems = $invoice->getItems();
        $invoiceCurency = $invoice->getCurency();
        foreach ($invoiceItems as $item) {
            $product = $item->getProduct();

            $itemCurency = $item->getCurency();
            $itemPrice = $item->getPrice();
            if ($itemCurency->getId() != $invoiceCurency->getId()) {
                $itemPrice = $itemPrice / $itemCurency->getExchangeRates() * $invoiceCurency->getExchangeRates();
            }

            $price += $itemPrice * $item->getQuantity();

            $products[] = array(
                'id' => $product->getId(),
                'title' => $product->getTitle(),
                'quantity' => $item->getQuantity(),
                'price' => round($itemPrice, 2)
            );
        }
        $this->view->assign('value', ResponseHelp::getSuccesMessage(array(
            'invoiceId' => $invoice->getId(),
            'priceSum' => round($price, 2),
            'curency' => $invoiceCurency->getName(),
            'products' => $products
        )));

    }
}