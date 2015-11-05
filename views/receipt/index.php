<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Receipt;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Facturas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="receipt-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'columns' => [
            [
				'attribute' => 'id',
				'options' => ['style' => 'width: 200px;'],
			],
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
				'filter' => Html::activeDropDownList($searchModel, 'status', Receipt::statusLabels(), ['class'=>'form-control', 'prompt' => 'Estado']),
			],
            [
				'attribute' => 'created_date',
				'format' => 'date',
				'filter' => false,
			],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
