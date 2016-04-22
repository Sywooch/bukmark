<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\editable\Editable;
use app\models\Receipt;
use app\models\Client;
use app\models\Currency;
use yii\widgets\ActiveForm;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $exportDataProvider yii\data\ActiveDataProvider */
/* @var $searchModel app\models\ReceiptSearch */

$this->title = 'Facturas';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1>Filtrar por fecha</h1>

<div class="receipt-form">

	<?php $form = ActiveForm::begin(['method' => 'get']); ?>

	<?= $form->field($searchModel, 'from_date')->widget(\yii\jui\DatePicker::classname(), ['options' => ['class' => 'form-control']]) ?>

	<?= $form->field($searchModel, 'to_date')->widget(\yii\jui\DatePicker::classname(), ['options' => ['class' => 'form-control']]) ?>

    <div class="form-group">
		<?= Html::submitButton('Filtrar', ['class' => 'btn btn-primary']) ?>
    </div>

	<?php ActiveForm::end(); ?>

</div>

<div class="receipt-index">

    <h1><?= Html::encode($this->title) ?></h1>

	<?=
	GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			[
				'attribute' => 'id',
				'options' => ['style' => 'width: 100px;'],
			],
			[
				'label' => 'Cliente',
				'value' => 'estimate.client.name',
				'filter' => Html::activeDropDownList($searchModel, 'client_id', Client::getIdNameArray(), ['class' => 'form-control', 'prompt' => 'Nombre']),
			],
			[
				'label' => 'Presupuesto',
				'value' => 'estimate.title',
			],
			'number',
			[
				'class' => 'kartik\grid\EditableColumn',
				'attribute' => 'status',
				'label' => 'Estado',
				'value' => 'statusLabel',
				'filter' => Html::activeDropDownList($searchModel, 'status', Receipt::statusLabels(), ['class' => 'form-control', 'prompt' => 'Estado']),
				'editableOptions' => [
					'inputType' => Editable::INPUT_DROPDOWN_LIST,
					'data' => Receipt::statusLabels(),
				],
			],
			[
				'attribute' => 'created_date',
				'format' => 'date',
				'filter' => false,
			],
			['class' => 'yii\grid\ActionColumn'],
		],
	]);
	?>

	<?=
	ExportMenu::widget([
		'dataProvider' => $exportDataProvider,
		'target' => ExportMenu::TARGET_SELF,
		'showConfirmAlert' => false,
		'filename' => 'facturas',
		'columns' => [
			[
				'attribute' => 'created_date',
				'format' => 'date',
				'filter' => false,
			],
			[
				'attribute' => 'type',
				'value' => 'typeLabel',
			],
			[
				'attribute' => 'number',
				'label' => 'Factura Nro',
			],
			[
				'label' => 'Cliente',
				'value' => 'estimate.client.name',
			],
			[
				'label' => 'CUIT',
				'value' => 'estimate.client.cuit',
			],
			[
				'label' => 'Facturación Neta',
				'value' => function ($model, $key, $index, $column) {
					return Currency::format($model->estimate->total_checked, Currency::CURRENCY_ARS);
				},
			],
			[
				'label' => 'IVA',
				'value' => function ($model, $key, $index, $column) {
					return Currency::format($model->IVATotal, Currency::CURRENCY_ARS);
				},
			],
			[
				'label' => 'Facturación Bruta',
				'value' => function ($model, $key, $index, $column) {
					return Currency::format($model->gross, Currency::CURRENCY_ARS);
				},
			],
			[
				'label' => 'Utilidad',
				'value' => function ($model, $key, $index, $column) {
					return Currency::format($model->estimate->total_checked - $model->estimate->cost_checked, Currency::CURRENCY_ARS);
				},
			],
			[
				'label' => 'Porcentaje',
				'value' => null,
			],
			[
				'label' => 'Productos',
				'value' => 'products',
			],
			[
				'label' => 'Estado',
				'value' => 'statusLabel',
			],
			[
				'label' => 'Recibo Nro',
				'value' => null,
			],
		],
	]);
	?>

</div>
