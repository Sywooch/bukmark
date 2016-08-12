<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;
use app\models\Currency;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Estimate */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->client->name . ' - ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Presupuestos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;
$user = User::getActiveUser();
?>
<div class="estimate-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
		<?= Html::a('Añadir producto', ['create-entry', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Editar información', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a('Generar factura', ['receipt/create', 'estimateId' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a('Generar PDF', ['get-pdf', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estas seguro de eleminar este elemento?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

	<div id="estimate-detail" style="display: none;">
		<?= DetailView::widget([
			'model' => $model,
			'attributes' => [
				'id',
				[
					'label' => 'Cliente',
					'attribute' => 'client.name',
				],
				[
					'label' => 'Atención',
					'attribute' => 'clientContact.displayName',
				],
				[
					'label' => 'Email',
					'attribute' => 'clientContact.email',
				],
				[
					'label' => 'Teléfono',
					'attribute' => 'clientContact.phone',
				],
				'title',
				[
					'label' => 'Usuario',
					'attribute' => 'user.username',
				],
				[
					'label' => 'Estado',
					'attribute' => 'statusLabel',
				],
				[
					'attribute' => 'request_date',
					'format' => 'date',
				],
				[
					'attribute' => 'sent_date',
					'format' => 'date',
				],
				[
					'attribute' => 'total',
					'value' => Currency::format($model->total, Currency::CURRENCY_ARS),
				],
				[
					'attribute' => 'cost',
					'value' => Currency::format($model->cost, Currency::CURRENCY_ARS),
				],
				[
					'attribute' => 'total_checked',
					'value' => Currency::format($model->total_checked, Currency::CURRENCY_ARS),
				],
				[
					'attribute' => 'cost_checked',
					'value' => Currency::format($model->cost_checked, Currency::CURRENCY_ARS),
				],
				[
					'attribute' => 'us',
					'value' => Currency::format($model->us, Currency::CURRENCY_ARS),
				],
			],
		]) ?>
	</div>
	
	<p>
		<?= Html::button('Mostrar/Ocultar detalle', ['class' => 'btn btn-primary', 'onclick' => '$("#estimate-detail").toggle()']) ?>
	</p>
	
	<?php
	// This function is used to display utility values on the gridview and editable fields
	$utilityDisplayFunction = function ($model, $key, $index, $column = null) {
		return Yii::$app->formatter->asPercent($model->utility / 100, 2);
	};
	
	$columns = [
		[	'label' => 'Proveedor',
			'value' => 'product.supplier.name'
		],
		[
			'label' => 'Cod. proveedor',
			'attribute' => 'product.supplier_code',
		],
		[
			'label' => 'Cod. interno',
			'attribute' => 'product.bukmark_code',
		],
		[	
			'label' => 'Producto',
			'value' => 'product.title'
		],
		[
			'class' => 'kartik\grid\EditableColumn',
			'attribute' => 'quantity',
			'refreshGrid' => true,
		],
		[
			'class' => 'kartik\grid\EditableColumn',
			'attribute' => 'price',
			'value' => function ($model, $key, $index, $column) {
				return Currency::format($model->price, $model->currency);
			},
			'editableOptions' => function ($model, $key, $index) {
				return [
					'formOptions' => [
						'enableClientValidation' => false,
					],
					'inputFieldConfig' => [
						'inputOptions' => [
							'value' => Yii::$app->formatter->asDecimal($model->price, 2),
						],
					]
				];
			},
			'refreshGrid' => true,
			'filter' => false,
		],
		[
			'class' => 'kartik\grid\EditableColumn',
			'attribute' => 'variant_price',
			'value' => function ($model, $key, $index, $column) {
				return Currency::format($model->variant_price, $model->variant_currency);
			},
			'editableOptions' => function ($model, $key, $index) {
				return [
					'formOptions' => [
						'enableClientValidation' => false,
					],
					'inputFieldConfig' => [
						'inputOptions' => [
							'value' => Yii::$app->formatter->asDecimal($model->variant_price, 2),
						],
					]
				];
			},
			'refreshGrid' => true,
			'filter' => false,
		],
		[
			'label' => 'Suma',
			'value' => function ($model, $key, $index, $column) {
				return Currency::format($model->cost, Currency::CURRENCY_ARS);
			},
		],
		[
			'class' => 'kartik\grid\EditableColumn',
			'attribute' => 'utility',
			'value' => $utilityDisplayFunction,
			'editableOptions' => function ($model, $key, $index) use ($utilityDisplayFunction) {
				return [
					'formOptions' => [
						'enableClientValidation' => false,
					],
					'inputFieldConfig' => [
						'inputOptions' => [
							'value' => call_user_func($utilityDisplayFunction, $model, $key, $index),
						],
					]
				];
			},
			'refreshGrid' => true,
			'filter' => false,
		],
		[
			'label' => 'Subtotal',
			'value' => function ($model, $key, $index, $column) {
				return Currency::format($model->subtotal, Currency::CURRENCY_ARS);
			},
		],
		[
			'label' => 'Subt. x cant.',
			'value' => function ($model, $key, $index, $column) {
				return Currency::format($model->quantitySubtotal, Currency::CURRENCY_ARS);
			},
		],
	];
			
	if($user->admin) {
		$marginColumn = [
			'label' => 'Margen',
			'value' => function ($model, $key, $index, $column) {
				return Currency::format($model->utilityMargin, Currency::CURRENCY_ARS);
			},
		];
		array_push($columns, $marginColumn);
	}
		
	$actionColumn = [
		'class' => 'yii\grid\ActionColumn',
		'template' => '{check} {update} {delete}',
		'buttons' => [
			'check' => function ($url, $model, $key) {
				$options = array_merge([
					'title' => Yii::t('yii', 'Check'),
					'aria-label' => Yii::t('yii', 'Check'),
					'data-method' => 'post',
					'data-pjax' => '0',
				]);
				$icon = $model->checked ? 'glyphicon-check' : 'glyphicon-unchecked';
				return Html::a('<span class="glyphicon ' . $icon . '"></span>', $url, $options);
			},
		],
		'urlCreator' => function($action, $model, $key, $index) {
			$url = [''];
			switch ($action) {
				case 'check':
					$url = ['check-entry', 'id' => $model->id, 'check' => !$model->checked];
					break;
				case 'update':
					$url = ['update-entry', 'id' => $model->id];
					break;
				case 'delete':
					$url = ['delete-entry', 'id' => $model->id];
					break;
			}
			return Url::to($url);
		},
	];
		
	array_push($columns, $actionColumn);
	?>
		
	<?= \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns,
    ]); ?>

</div>
