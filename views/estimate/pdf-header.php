<?php
/* @var $estimate app\models\Estimate */
?>

<h1><?= Yii::$app->name ?></h1>
<br>
Fecha: <?= Yii::$app->formatter->asDate(time()) ?>
<br>
Cliente: <?= $estimate->client->name ?>
<br>
Presupuesto Nro: <?= $estimate->id ?>