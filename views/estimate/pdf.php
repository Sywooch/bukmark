<?php

use yii\helpers\Html;
use app\models\Currency;
use app\models\Product;

/* @var $estimate app\models\Estimate */
?>

<br>
<?php
// Group entries by product
$groups = [];
foreach($estimate->entries as $entry) {
	$groups[$entry->product->id][] = $entry;
}

$qGroups = count($groups);
$i = 0;

?>

<div style="background: #232323; color: lightgray;">
	<div style="float:left; margin: 0; width: 30%; margin-left: 35px">
		<b>Imágen</b>
	</div>
	<div style="float:left; margin: 0; width: 40%;">
		<b>Código/Descripción</b>
	</div>
	<div style="float:left; margin: 0; width: 10%; text-align: center;">
		<b>Cantidad</b>
	</div>
	<div style="float:left; margin: 0; width: 15%; text-align: center;">
		<b>Precio unit.</b>
	</div>
</div>

<?php foreach ($groups as $group): ?>
	<?php
		$entry = $group[0];
		$product = $group[0]->product;
	?>

	<div style="margin-top: 20px;">
		<div style="float:left; margin: 0; width: 35%; text-align: center; height: 1px;">
			<?= $entry->product_image_id ? Html::img(ltrim($product->getBehavior(Product::GALLERY_IMAGE_BEHAVIOR)->getUrl($entry->product_image_id, 'small'), '/'), ['height' => 100]) : '' ?>
		</div>
		<div style="float: left; margin: 0; width: 60%;">
			<div style="margin: 0; width: 58%;">
				<b><?= $product->bukmark_code ?></b>
				<br>
				<?= $product->title ?>
				<br>
				<?= $product->description ?>
			</div>
			<div style="float:left; margin: 0;">
				<?php foreach ($group as $entry): ?>
					<div style="float:left; margin: 0; width: 58%; height: 1px;">
						<?= $entry->description ?>
					</div>
					<div style="float:left; margin: 0; width: 30%; text-align: center; height: 1px;">
						<?= $entry->quantity ?>
					</div>
					<div style="float:left; margin: 0; text-align: center; height: 1px;">
						<?= Currency::format($entry->subtotal, Currency::CURRENCY_ARS); ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<?php if (++$i < $qGroups): ?>
		<hr>
	<?php endif; ?>

<?php endforeach; ?>