<?php
/**
 * Created by PhpStorm.
 * User: "Danut"
 * Date: 2019-03-21
 * Time: 7:18 PM
 */
namespace Application\Controller\SinglePage;

use Concrete\Core\Page\Controller\PageController;
use Doctrine\DBAL\Query\QueryBuilder;

class Orders extends PageController
{
    public function orders()
    {
        $conn = \Database::connection();
        /** @var QueryBuilder $qb */
        $qb = $conn->createQueryBuilder();

        $orders = $qb->select('o.*', 'SUM(p.btPrice) AS total')
                    ->from('btOrders', 'o')
                    ->join('o', 'btOrderProduct', 'op', 'o.bID = op.bID')
                    ->join('op', 'btProducts', 'p', 'p.bID = op.bID')
                    ->groupBy('o.bID')
                    ->execute();

        $this->set('orders', $orders);
    }

    public function order($orderID = null)
    {
        $conn = \Database::connection();
        /** @var QueryBuilder $qb */
        $qb = $conn->createQueryBuilder();

        $order = $qb->select('o.btName', 'o.btEmail', 'o.btComment', 'p.btImage', 'p.btDescription', 'p.btPrice')
                    ->from('btOrders', 'o')
                    ->join('o', 'btOrderProduct', 'op', 'o.bID = op.bID')
                    ->join('op', 'btProducts', 'p', 'p.bID = op.bID')
                    ->where('o.bID = ?')
                    ->setParameter(0, $orderID)
                    ->execute();

        $this->set('order', $order->fetchAll());
    }
}
