<?php
/**
 * Created by PhpStorm.
 * User: "Danut"
 * Date: 2019-03-21
 * Time: 2:06 PM
 */

?>

<?php switch ($view->controller->getTask()): ?>
<?php case 'cart': ?>
        <table>
            <?php /*var_dump($cart) */?>
            <?php foreach ($qb as $row): ?>
                <tr>
                    <td class="cp_img">
                        <img src="<?= '/application/files/img/' . $row['btImage'] ?>" alt="image"/>
                    </td>
                    <td class="cp_img">
                        <ul>
                            <li><?= $row["btTitle"] ?></li>
                            <li><?= $row["btDescription"] ?></li>
                            <li><?= $row["btPrice"] ?></li>
                        </ul>
                    </td>
                    <td class="cp_img">
                        <a href="<?= URL::to('/shop/cart', $row['bID']) ?>"><?= t("Remove") ?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <form method="post">
            <input type="text" name="name" value=""
                   placeholder="<?= t("Name") ?>">
            <br>
            <input type="text" name="contact" value=""
                   placeholder="<?= t("Contact details") ?>">
            <br>
            <input type="text" name="comment" value=""
                   placeholder="<?= t("Comments") ?>">
            <br>
            <input type="submit" name="checkout" value="<?= t("Checkout") ?>">
        </form>
        <a href="<?= URL::to('/shop') ?>"><?= t("Go to index") ?></a>
        <?php break; ?>
    <?php default: ?>
        <table>
            <?php /*var_dump($cart) */?>
            <?php foreach ($qb->fetchAll() as $row): ?>
                <tr>
                    <td class="cp_img">
                        <img src="<?= '/application/files/img/' . $row['btImage'] ?>" alt="image"/>
                    </td>
                    <td class="cp_img">
                        <ul>
                            <li><?= $row['btTitle'] ?></li>
                            <li><?= $row['btDescription'] ?></li>
                            <li><?= $row['btPrice'] ?></li>
                        </ul>
                    </td>
                    <td class="cp_img">
                        <a href="<?= URL::to('/shop', $row['bID']) ?>"><?= t("Add") ?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="<?= URL::to('/shop/cart') ?>"><?= t("Go to cart") ?></a>
        <?php break; ?>
    <?php endswitch; ?>
