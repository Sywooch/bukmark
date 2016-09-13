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
foreach ($estimate->entries as $entry) {
	$groups[$entry->product->id][] = $entry;
}

$qGroups = count($groups);
$i = 0;
?>

<?php $linesTotal = 0; ?>

<?php foreach ($groups as $group): ?>
	<?php
	$entry = $group[0];
	$product = $group[0]->product;
	
	$lines = 0;
	$lines += ceil((strlen($product->description) + 1) / 46.0);
	foreach ($group as $entry) {
		$lines += ceil((strlen($entry->description) + 1) / 46.0);
	}
	$lines = $lines > 6 ? $lines : 6;
	$lines += 7;
	$linesTotal += $lines;
	?>

	<?php if ($linesTotal > 55): ?>
		<pagebreak />
		<?php $linesTotal = $lines; ?>
	<?php endif; ?>

	<div style="margin-top: 20px;">
		<div style="float: left; margin: 0; width: 35%; text-align: center; height: 1px;">
			<?= $lines ?> 
			<?= $entry->product_image_id ? Html::img(ltrim($product->getBehavior(Product::GALLERY_IMAGE_BEHAVIOR)->getUrl($entry->product_image_id, 'medium'), '/'), ['height' => 100]) : '' ?>
		</div>
		<div style="float: left; margin: 0; width: 60%;">
			<div style="margin: 0; width: 58%;">
				<b><?= $product->bukmark_code ?></b>
			</div>
			<?php
				$style = "margin: 0; width: 58%;";
				if (count ($group) == 1) {
					$style .= ' float: left;';
				}
			?>
			<div style="<?= $style ?>">
				<?= $product->description ?>
				<?php if (count($group) == 1): ?>
					<br>
					<?= $group[0]->description ?>
				<?php endif; ?>
			</div>
			<?php if (count($group) == 1): ?>
				<div style="float: left; margin: 0; width: 25%; text-align: center; height: 1px;">
					<?= $group[0]->quantity ?>
				</div>
				<div style="float: left; margin: 0; text-align: center; height: 1px;">
					<?= Currency::format($group[0]->subtotal, Currency::CURRENCY_ARS); ?>
				</div>
			<?php endif; ?>
			<?php if (count($group) > 1): ?>
				<div style="float: left; margin: 0;">
					<?php foreach ($group as $entry): ?>
						<div style="float: left; margin: 0; width: 58%; height: 1px;">
							<?= $entry->description ?>
						</div>
						<div style="float: left; margin: 0; width: 25%; text-align: center; height: 1px;">
							<?= $entry->quantity ?>
						</div>
						<div style="float: left; margin: 0; text-align: center; height: 1px;">
							<?= Currency::format($entry->subtotal, Currency::CURRENCY_ARS); ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php if (++$i < $qGroups): ?>
		<hr>
	<?php endif; ?>

<?php endforeach; ?>