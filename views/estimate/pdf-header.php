<?php
/* @var $estimate app\models\Estimate */

use yii\helpers\Html;
use app\models\Client;
?>

<div>
	<div style="background: red; padding-top: 2px; padding-bottom: 2px; width: 80px; float: right; margin-right: 50px; text-align: center;">
		<h4 style="color:white;"><b>{PAGENO}/{nbpg}</b></h4>
	</div>
</div>

<div style="margin-top: 35px; margin-left: 35px;">
	<div style="float:left; margin:0; width:33%;">
		<table style="width: 100%; height: 100%; margin: 0; padding: 0; border: 0;">
			<tr>
				<td style="vertical-align: middle; text-align: center;"><?= Html::img('images/logo.png', ['width' => 235]); ?></td>
			</tr>
		</table>
	</div>

	<div style="float:left; margin:0; width:33%;">
		<span style="font-size: 14px"><b>PRESUPUESTO N°: <?= sprintf('%08d', $estimate->id) ?></b></span>
		<br>
		<b>Fecha: <?= Yii::$app->formatter->asDate(time()) ?> <span style="color: red;">Validez: 7 días</span></b>
		<br>
		<span style="font-style: italic;">Los precios no incluyen IVA</span>
	</div>

	<div  style="float:left; margin:0; width:33%">
		<span style="color:red;"><b>5% DE DESCUENTO</b></span>
		<br>
		<span style="color:red;">Con pago del 100% adelantado en
			<br>
			efectivo o cheque al dia a la orden
		</span>
	</div>
</div>

<div style="margin-top: 35px; margin-left: 35px; margin-bottom: 15px">
	<div style="float:left; margin:0; width:33%;">
		<b>Cliente: <?= $estimate->client ? $estimate->client->name : '' ?></b>
		<br>
		<b>Atención: <?= $estimate->clientContact ? $estimate->clientContact->displayName : '' ?></b>
	</div>

	<div style="float:left; margin:0; width:33%;">
		<b>Condiciones de pago:</b>
		<br>
		<?= $estimate->client->payment_conditions ?>
	</div>

	<div  style="float:left; margin:0; width:33%">
		<b>Plazo de Entrega:</b>
		<br>
		A convenir
	</div>
</div>