<?php

use yii\helpers\Html;
use app\models\Currency;

/* @var $estimate app\models\Estimate */
?>

<b>Condiciones de pago:</b> <?= $estimate->client->payment_conditions ?>

<br>

<b>Notas:</b>
<ul>
	<li>Se incluye impresión de logo</li>
	<li>Los precios no incluyen el IVA</li>
	<li>Plazos de entrega: 15 / 20 días</li>
</ul>

<hr>

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
	
<?php foreach ($groups as $group): ?>
	<?php
		$product = $group[0]->product;
	?>

	<table width="100%">
		<tr>
			<td><?= $product->imageUrl ? Html::img(ltrim($product->imageUrl, '/'), ['height' => 100]) : '' ?></td>
			<td>
				<?= $product->title . ' - ' . $product->bukmark_code?>
				<br>
				<?= $product->description ?>
			</td>
		</tr>
	</table>

	<table width="100%">
	<tr>
		<td><b>Impresión</b></td>
		<td><b>Cantidad</b></td>
		<td><b>Precio unitario</b></td>
	</tr>
	<?php foreach ($group as $entry): ?>
		<tr>
			<td><?= $entry->description ?></td>
			<td>
				<?= $entry->quantity ?>
			</td>
			<td>
				<?= Currency::format($entry->subtotal, Currency::CURRENCY_ARS); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>

	<?php if (++$i < $qGroups): ?>
		<hr>
	<?php endif; ?>

<?php endforeach; ?>