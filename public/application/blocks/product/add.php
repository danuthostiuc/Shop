<?php defined('C5_EXECUTE') or die(_("Access Denied.")) ?>
<div class="ccm-ui">
	<div class="alert-message block-message info">
		<?= t("Add Product") ?>
	</div>

    <form method="post"
          enctype="multipart/form-data"
          action="<?= $this->action('save') ?>">

        <input type="text" name="title"
               value=""
               placeholder="<?= t("Title") ?>" required>
        <br>
        <input type="text" name="description"
               value=""
               placeholder="<?= t("Description") ?>" required>
        <br>
        <input type="number" name="price"
               value=""
               placeholder="<?= t("Price") ?>" required>
        <br>
<!--        <input type="file"
               name="image"
               accept=".png, .gif, .jpeg, .jpg"
               required>
        <br>-->
        <input class="btn btn-primary"
               type="submit"
               value="Save"/>
    </form>
</div>
