<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\editable\Editable;
use app\models\Estimate;
use app\models\Currency;
use app\assets\EstimateIndexAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EstimateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Presupuestos';
$this->params['breadcrumbs'][] = $this->title;

EstimateIndexAsset::register($this);
?>
<div class="estimate-index">

    <h1><?= Html::encode($this->title) ?></h1>
	
	<div style="float: right">
		<h3>Valor dólar: <?= Currency::format(Currency::getUsToArs(), Currency::CURRENCY_ARS) ?></h3>
	</div>

    <p>
		<?= Html::a('Crear presupuesto', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	
	<?=
	GridView::widget([
		'pjax' => true,
		'pjaxSettings' => [
			'options' => [
				'id' => 'estimates-gridview',
			],
		],
		'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'rowOptions' => function ($model, $index, $widget, $grid) {
			return ['style'=>"background-color:$model->statusColor;"];
		},
		'columns' => [
			[
				'attribute' => 'id',
				'label' => 'Número',
				'options' => ['style' => 'width: 100px;'],
			],
			[
				'label' => 'Cliente',
				'value' => 'client.name',
				'filter' => Html::activeTextInput($searchModel, 'clientName', ['class'=>'form-control']),
			],
			'title',
			[
				'class' => 'kartik\grid\EditableColumn',
				'attribute' => 'status',
				'label' => 'Estado',
				'value' => 'statusLabel',
				'filter' => Html::activeDropDownList($searchModel, 'status', Estimate::statusLabels(), ['class'=>'form-control', 'prompt' => 'Estado']),
				'editableOptions' => [
					'inputType' => Editable::INPUT_DROPDOWN_LIST,
					'data' => Estimate::statusLabels(),
				],
				'refreshGrid' => true,
				'options' => ['style' => 'width: 160px;'],
			],
			[
				'attribute' => 'request_date',
				'format' => 'date',
				'filter' => false,
				'options' => ['style' => 'width: 125px;'],
			],
			[
				'attribute' => 'sent_date',
				'format' => 'date',
				'filter' => false,
				'options' => ['style' => 'width: 125px;'],
			],
			[
				'label' => 'Muestra',
				'filter' => Html::activeCheckbox($searchModel, 'sampleDelivered', ['label' => '']),
				'value' => function  ($model, $key, $index, $column) {
					return $model->entriesWithSampleDeliveredCount > 0 ? 'Si' : 'No';
				},
				'options' => ['style' => 'width: 70px;'],
			],
			[
				'class' => 'yii\grid\ActionColumn',
				'options' => ['style' => 'width: 60px;'],
			],
		],
	]);
	?>

</div>
