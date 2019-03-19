<?php defined('C5_EXECUTE') or die(_("Access Denied.")) ?>
<div class="ccm-ui">
	<div class="alert-message block-message info">
		<?= t("This is the edit template for the product block.") ?>
	</div>

    <form method="post"
          enctype="multipart/form-data"
          action="<?= $this->action('save') ?>">

        <input type="text" name="title"
               value="<?= $title ?>"
               placeholder="<?= t("Title") ?>" required>
        <br>
        <input type="text" name="description"
               value="<?= $description ?>"
               placeholder="<?= t("Description") ?>" required>
        <br>
        <input type="number" name="price"
               value="<?= $price ?>"
               placeholder="<?= t("Price") ?>" required>
        <br>
        <input
            type="file"
            name="image"
            accept=".png, .gif, .jpeg, .jpg"
            >
        <br>
        <input class="btn btn-primary"
               type="submit"
               value="Save"/>
    </form>
</div>
