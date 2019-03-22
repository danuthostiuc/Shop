<?php
/**
 * Created by PhpStorm.
 * User: "Danut"
 * Date: 2019-03-21
 * Time: 2:06 PM
 */

namespace Application\Controller\SinglePage;

use Concrete\Core\Mail\Service;
use Concrete\Core\Page\Controller\PageController;
use Doctrine\DBAL\Query\QueryBuilder;

class Shop extends PageController
{
    private $session;

    public function on_start()
    {
        $this->session = \Core::make('session');
    }

    public function view($productID = null)
    {
        $cart = is_array($this->session->get('cart')) ? $this->session->get('cart') : [];
        if ($productID) {
            if (is_array($cart) && !in_array($productID, $cart)) {
                array_push($cart, $productID);
                $this->session->set('cart', $cart);
            }
        }

        $conn = \Database::connection();
        /** @var QueryBuilder $qb */
        $qb = $conn->createQueryBuilder();
        $values = $cart;
        $placeholders = implode(', ', array_fill(0, count($values), '?'));

        if ($values) {
            $qb = $qb->select('*')
                ->from('btProducts', 'p')
                ->where("p.bID NOT IN ({$placeholders})")
                ->setParameters($values)
                ->execute();
        } else {
            $qb = $qb->select('*')
                ->from('btProducts', 'p')
                ->execute();
        }

        $this->set('qb', $qb);
    }

    public function cart($productID = null)
    {
        if ($productID) {
            $cart = is_array($this->session->get('cart')) ? $this->session->get('cart') : [];
            if (($key = array_search($productID, $cart)) !== false) {
                array_splice($cart, $key, 1);
            }
            $this->session->set('cart', $cart);
        }

        $this->set('qb', $this->getCart());
    }

    private function getCart()
    {
        $conn = \Database::connection();
        /** @var QueryBuilder $qb */
        $qb = $conn->createQueryBuilder();
        $values = is_array($this->session->get('cart')) ? $this->session->get('cart') : [];
        $placeholders = implode(', ', array_fill(0, count($values), '?'));

        if ($values) {
            $qb = $qb->select('*')
                ->from('btProducts', 'p')
                ->where("p.bID IN ({$placeholders})")
                ->setParameters($values)
                ->execute();
        }

        return $qb;
    }

    public function checkout()
    {
        $protocol = $_SERVER["HTTPS"] === "on" ? "https://" : "http://";
        $name = $this->post('name');
        $to = $this->post('contact');
        $comment = $this->post('comment');
        $subject = t("Test");
        $message =
            '<html>' .
                '<body>' .
                    '<h1>' .
                        t("Cart") .
                    '</h1>' .
                    '<table>';
                    foreach ($this->getCart() as $row) {
                        $message .= '<tr>' .
                                        '<td>' .
                                            '<img src="' . $protocol .
                                                    $_SERVER["HTTP_HOST"] .
                                                    '/application/files/img/' .
                                                    $row['btImage'] .
                                                    '" width="600" border="0" style="display:block;"/>' .
                                        '</td>' .
                                        '<td>' .
                                            '<ul>' .
                                                '<li>' .
                                                    $row['btTitle'] .
                                                '</li>' .
                                                '<li>' .
                                                    $row['btDescription'] .
                                                '</li>' .
                                                '<li>' .
                                                    $row['btPrice'] .
                                                '</li>' .
                                            '</ul>' .
                                        '</td>' .
                                    '</tr>';
                    }
                    $message .= '</table>'.
                '</body>' .
            '</html>';

        $headers = "MIME-Version: 1.0" . "\r\n" . "Content-type:text/html;charset=UTF-8" . "\r\n";

        /** @var Service $mailService */
        $mailService = \Core::make('mail');
        $mailService->setTesting(false);
        $mailService->to($to, $name);
        $mailService->setSubject($subject);
        $mailService->addParameter('message', $message);
        $mailService->setAdditionalHeaders(array($headers));
        try {
            $mailService->sendMail();
        } catch (\Exception $e) {
            $this->set('mail', $e);
        }


        $conn = \Database::connection();
        /** @var QueryBuilder $qb */
        $qb = $conn->createQueryBuilder();

        $qb->insert('btOrders')
            ->values(array('btName' => '?', 'btEmail' => '?', 'btComment' => '?'))
            ->setParameter(0, $name)
            ->setParameter(1, $to)
            ->setParameter(2, $comment)
            ->execute();

        $last_insert_id = $conn->lastInsertId();

        /** @var QueryBuilder $qb */
        $qb = $conn->createQueryBuilder();
        foreach ($this->session->get('cart') as $productID) {
            $qb->insert('btOrderProduct')
                ->values(array('btOrderID' => '?', 'btProductID' => '?'))
                ->setParameter(0, $last_insert_id)
                ->setParameter(1, $productID)
                ->execute();
        }

        $this->session->remove('cart');

        /** @var QueryBuilder $qb */
        $qb = $conn->createQueryBuilder();

        $products = $qb->select('*')
                    ->from('btProducts');

        $this->set('qb', $products);
        $this->render('/shop');
    }
}
