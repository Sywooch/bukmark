<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Currency;

/* @var $this yii\web\View */
/* @var $model app\models\Receipt */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Facturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="receipt-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estas seguro de eliminar este elemento?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
			[
				'label' => 'Cliente',
				'value' => $model->estimate->client->name,
			],
            [
				'label' => 'Presupuesto',
				'value' => $model->estimate->title,
			],
			'number',
			[
				'attribute' => 'created_date',
				'format' => 'date',
			],
			[
				'label' => 'Tipo',
				'value' => $model->typeLabel,
			],
            [
				'label' => 'Estado',
				'value' => $model->statusLabel,
			],
			[
				'label' => 'Neto',
				'value' => Currency::format($model->estimate->total_checked, Currency::CURRENCY_ARS),
			],
            [
				'attribute' => 'iva',
				'value' => $model->iva / 100,
				'format' => ['percent', 2],
			],
			[
				'label' => 'Bruto',
				'value' => Currency::format($model->gross, Currency::CURRENCY_ARS),
			],
        ],
    ]) ?>

</div>
