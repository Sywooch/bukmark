<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Client;
use app\models\Product;
use app\models\Currency;
use yii\widgets\ActiveForm;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $exportDataProvider yii\data\ActiveDataProvider */
/* @var $searchModel app\models\SummarySearch */

$this->title = 'Resumen';
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

	<?php
	$columns = [
		[
			'attribute' => 'bukmark_code',
			'label' => 'Código',
			'value' => 'product.bukmark_code',
		],
		[
			'attribute' => 'product_id',
			'value' => 'product.title',
			'filter' =>  Html::activeDropDownList($searchModel, 'product_id', Product::getDropdownData(), ['class' => 'form-control', 'prompt' => 'Elegir producto']),
			'enableSorting' => false,
		],
		[
			'label' => 'Cliente',
			'value' => 'estimate.client.name',
			'filter' => Html::activeDropDownList($searchModel, 'client_id', Client::getIdNameArray(), ['class' => 'form-control', 'prompt' => 'Nombre']),
		],
		[
			'attribute' => 'estimate.receipt.created_date',
			'format' => 'date',
			'filter' => false,
		],
		[
			'label' => 'Total',
			'value' => function ($model, $key, $index, $column) {
				return Currency::format($model->quantitySubtotal, Currency::CURRENCY_ARS);
			},
		],
	];
	?>

	<?=
	GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => $columns,
	]);
	?>

	<?=
	ExportMenu::widget([
		'dataProvider' => $exportDataProvider,
		'target' => ExportMenu::TARGET_SELF,
		'showConfirmAlert' => false,
		'filename' => 'resumen',
		'columns' => $columns,
	]);
	?>

</div>
