<?php

use yii\helpers\Html;
use app\models\Currency;

/* @var $estimate app\models\Estimate */
?>

<table width="100%">

	<tr>
		<td><b>Imágen</b></td>
		<td><b>Descripción</b></td>
		<td><b>Precio unitario</b></td>
	</tr>
	
<?php foreach ($estimate->entries as $entry): ?>
	<tr>
		<td><?= Html::img(ltrim($entry->product->imageUrl, '/'), ['height' => 100]) ?></td>
		<td>
			<?= $entry->product->title . ' - ' . $entry->product->bukmark_code?>
			<br>
			<?= $entry->product->description ?>
		</td>
		<td>
			<?= Currency::format($entry->price, $entry->currency); ?>
		</td>
	</tr>

<?php endforeach; ?>

</table>