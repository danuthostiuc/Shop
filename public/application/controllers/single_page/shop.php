<?php
/**
 * Created by PhpStorm.
 * User: "Danut"
 * Date: 2019-03-21
 * Time: 2:06 PM
 */

namespace Application\Controller\SinglePage;

use Concrete\Core\Page\Controller\PageController;

class Shop extends PageController
{
    public $session;

    public function on_start()
    {
        if (!$this->session) {
            $this->session = \Core::make('session');
            $this->session->set('cart', []);
        }
    }

    public function view($productID = null)
    {
//        $c = \Page::getCurrentPage();
//        if ($c->getCollectionID()) {
        if ($productID) {
//            if (!$this->session->get('cart')) {
//                $this->session->set('cart', []);
//            }
            $cart = $this->session->get('cart');
            /*array_push($cart, $c->getCollectionID());*/
            array_push($cart, $productID);
            $this->session->remove('cart');
            $this->session->set('cart', $cart);
            //$this->set('cart', $this->session->get('cart'));
        }

        $conn = \Database::connection();
        $qb = $conn->createQueryBuilder()
            ->select('*')
            ->from('btProducts', 'p')
            ->where('p.bID NOT IN(:productIDS)')
            ->setParameter('productIDS', $this->session->get('cart'))
            ->execute();

        $this->set('qb', $qb);
    }

    public function cart($productID = null)
    {
        if ($productID) {
            $cart = $this->session->get('cart');
            $this->session->remove('cart');
            if (($key = array_search($productID, $cart)) !== false) {
                array_splice($cart, $key, 1);
            }
            $this->session->set('cart', $cart);
            //$this->set('cart', $this->session->get('cart'));
        }

        $conn = \Database::connection();
        $qb = $conn->createQueryBuilder()
            ->select('*')
            ->from('btProducts', 'p')
            ->where('p.bID IN(:productIDS)')
            ->setParameter('productIDS', $this->session->get('cart'))
            ->execute();

        $this->set('qb', $qb);
    }
}
