<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Facturas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="receipt-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
			[
				'label' => 'Cliente',
				'value' => 'estimate.client.name',
			],
            [
				'label' => 'Presupuesto',
				'value' => 'estimate.title',
			],
            [
				'label' => 'Estado',
				'value' => 'statusLabel',
			],
            [
				'attribute' => 'created_date',
				'format' => 'date',
			],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
