<?php
namespace Acme\Ecommerce\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Acme.Ecommerce".        *
 *                                                                        *
 *                                                                        */

use Acme\Ecommerce\Domain\Model\Category;
use Acme\Ecommerce\Domain\Model\Curency;
use Acme\Ecommerce\Domain\Model\Product;
use Acme\Ecommerce\Helpers\ResponseHelp;
use TYPO3\Flow\Mvc\Controller\ActionController;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\PersistenceManagerInterface;
use Acme\Ecommerce\Domain\Model\Image;

/**
 * Class ProductController
 * @package Acme\Ecommerce\Controller
 */
class ProductController extends ActionController
{
    /**
     * @var string
     */
    protected $defaultViewObjectName = 'TYPO3\Flow\Mvc\View\JsonView';

    /**
     * @Flow\Inject
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $entityManager;

    public function indexAction()
    {
        $this->view->assign('value', ResponseHelp::getSuccesMessage('ok'));
    }

    /**
     * @param integer $productId
     */
    public function getProductAction($productId)
    {
        $product = $this->entityManager->find('\Acme\Ecommerce\Domain\Model\Product', $productId);
        if ($product instanceof Product) {
            $this->view->assign('value', ResponseHelp::getSuccesMessage(
                $this->getProductInfo($product)
            ));
        } else {
            $this->view->assign('value', ResponseHelp::getErrorMessage('Product not found'));
        }
    }

    /**
     * @param integer $productId
     */
    public function getPhotosOfProductAction($productId)
    {
        $product = $this->entityManager->find('\Acme\Ecommerce\Domain\Model\Product', $productId);
        if ($product instanceof Product) {
            /** @var Image[] $images */
            $images = $product->getImages();
            $productImage = array();
            foreach ($images as $image) {
                $productImage[] = array(
                    'url' => $image->getImageUrl(),
                    'title' => $image->getTitle()
                );
            }
            $this->view->assign('value', ResponseHelp::getSuccesMessage(
                $productImage
            ));
        } else {
            $this->view->assign('value', ResponseHelp::getErrorMessage('Product not found'));
        }
    }

    /**
     * @param integer $categoryId
     * @param integer $offset
     * @param integer $limit
     * @param string $order
     */
    public function getByCategoryAction($categoryId, $offset = 0, $limit = 5, $order = 'title')
    {
        if (!$categoryId) {
            $this->view->assign('value', ResponseHelp::getErrorMessage('Please, set categoryId'));
            return;
        }

        $availableOrders = array('title', 'price', 'quantity');
        if (array_search($order, $availableOrders) === FALSE) {
            $this->view->assign('value', ResponseHelp::getErrorMessage('Please, set order (available title, price, quantity)'));
            return;
        }

        /** @var \Doctrine\ORM\QueryBuilder $query */
        $query = $this->entityManager->createQueryBuilder();

        $query
            ->select(array('p', 'c.name', 'c.id'))
            ->from('Acme\Ecommerce\Domain\Model\Product', 'p')
            ->where('p.category = ?1')
            ->setParameter(1, $categoryId);

        $query->join('Acme\Ecommerce\Domain\Model\Curency', 'c', 'WITH', 'p.curency = c.id');

        if($order == 'title'){
            $query->orderBy('p.title', 'ASC');
        }elseif($order == 'quantity'){
            $query->orderBy('p.quantity', 'ASC');
        }elseif($order == 'price'){
            $query->addSelect('c.exchangeRates*p.price defaultPrice');
            $query->addOrderBy('defaultPrice', 'ASC');
        }

        $query = $query->getQuery();
        if ($offset) {
            $query->setFirstResult($offset);
        }
        if ($limit) {
            $query->setMaxResults($limit);
        }
        $result = $query->getArrayResult();


        $answer = array();
        foreach($result as $product){
            $answer[] = array_merge($product[0], array(
                'curencyId'=>$product['id'],
                'curencyName'=>$product['name'],
            ));
        }
        $this->view->assign('value', ResponseHelp::getSuccesMessage($answer));
    }

    private function getProductInfo(Product $product)
    {
        return array(
            'id' => $product->getId(),
            'title' => $product->getTitle(),
            'description' => $product->getDescription(),
            'quantity' => $product->getQuantity(),
            'price' => $product->getPrice(),
            'curency' => $product->getCurency()->getName(),
            'category' => $product->getCategory()->getName()
        );
    }

}