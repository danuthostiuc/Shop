<?php defined('C5_EXECUTE') or die(_("Access Denied.")) ?>

<table>
    <tr>
        <td class="cp_img">
            <img src="<?= '/application/files/img/' . $image ?>" alt="image"/>
        </td>
        <td class="cp_img">
            <ul>
                <li><?= $title ?></li>
                <li><?= $description ?></li>
                <li><?= $price ?></li>
            </ul>
        </td>
        <td class="cp_img">
            <?php if ($c->getCollectionName() === "Index"): ?>
                <a href="<?= URL::to('/index', $id) ?>"><?= t("Add") ?></a>
            <?php endif; ?>
            <?php if ($c->getCollectionName() === "Cart"): ?>
                <a href="<?= URL::to('/cart', $id) ?>"><?= t("Remove") ?></a>
            <?php endif; ?>
        </td>
    </tr>
</table>

