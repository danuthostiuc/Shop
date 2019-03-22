<?php
/**
 * Created by PhpStorm.
 * User: "Danut"
 * Date: 2019-03-21
 * Time: 7:19 PM
 */
?>

<?php if ($view->controller->getTask() === 'orders'): ?>
    <table>
        <?php foreach ($orders as $row): ?>
            <tr>
                <td>
                    <a href="<?= URL::to('orders/order', $row['bID']) ?>"><?= $row['bID'] ?></a>
                </td>
                <td>
                    <?= $row['btName'] ?>
                </td>
                <td>
                    <?= $row['btEmail'] ?>
                </td>
                <td>
                    <?= $row['btComment'] ?>
                </td>
                <td>
                    <?= $row['total'] ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <table border="1">
        <tr>
            <td rowspan="<?= count($order[0]) + 1 ?>" class="cp_img">
                <?= $order[0]['btName'] ?>
            </td>
            <td rowspan="<?= count($order[0]) + 1 ?>" class="cp_img">
                <?= $order[0]['btEmail'] ?>
            </td>
            <td rowspan="<?= count($order[0]) + 1 ?>" class="cp_img">
                <?= $order[0]['btComment'] ?>
            </td>
        </tr>
        <?php foreach ($order as $row): ?>
        <tr>
            <td class="cp_img">
                <img src="<?= '/application/files/img/' . $row['btImage'] ?>" alt="image"/>
            </td>
            <td class="cp_img">
                <?= $row['btTitle'] ?>
            </td>
            <td class="cp_img">
                <?= $row['btDescription'] ?>
            </td>
            <td class="cp_img">
                <?= $row['btPrice'] ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
