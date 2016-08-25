<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;
use app\models\Currency;
use app\models\User;
use yii\widgets\Pjax;
use app\assets\EstimateViewAsset;

/* @var $this yii\web\View */
/* @var $model app\models\Estimate */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->client->name . ' - ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Presupuestos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;
$user = User::getActiveUser();
EstimateViewAsset::register($this);
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
		<?php Pjax::begin(['id' => 'estimate-view']); ?>
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
		<?php Pjax::end(); ?>
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
			'value' => 'product.supplier.name',
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
	
	$sampleColumn = [
		'class' => 'yii\grid\ActionColumn',
		'header' => 'Muestra',
		'options' => ['style' => 'width: 70px;'],
		'template' => '{checkSample}',
		'buttons' => [
			'checkSample' => function ($url, $model, $key) {
				$options = array_merge([
					'title' => 'Muestra entregada',
					'aria-label' => 'Muetra entregada',
				]);
				$icon = $model->sample_delivered ? 'glyphicon-check' : 'glyphicon-unchecked';
				return Html::a('<span class="glyphicon ' . $icon . '"></span>', $url, $options);
			},
		],
		'urlCreator' => function($action, $model, $key, $index) {
			$url = [''];
			switch ($action) {
				case 'checkSample':
					$url = ['check-entry-sample', 'id' => $model->id, 'check' => !$model->sample_delivered, 'page' => Yii::$app->request->getQueryParam('page', null)];
					break;
			}
			return Url::to($url);
		},
	];
	
	$actionColumn = [
		'class' => 'yii\grid\ActionColumn',
		'options' => ['style' => 'width: 115px;'],
		'template' => '{check} {duplicate} {up} {down} {update} {delete}',
		'buttons' => [
			'check' => function ($url, $model, $key) {
				$options = array_merge([
					'title' => 'Chequear',
					'aria-label' => 'Chequear',
				]);
				$icon = $model->checked ? 'glyphicon-check' : 'glyphicon-unchecked';
				return Html::a('<span class="glyphicon ' . $icon . '"></span>', $url, $options);
			},
			'duplicate' => function ($url, $model, $key) {
				$options = array_merge([
					'title' => 'Duplicar',
					'aria-label' => 'Duplicar',
				]);
				return Html::a('<span class="glyphicon glyphicon-plus"></span>', $url, $options);
			},
			'up' => function ($url, $model, $key) {
				$options = array_merge([
					'title' => 'Subir',
					'aria-label' => 'Subir',
				]);
				return Html::a('<span class="glyphicon glyphicon-arrow-up"></span>', $url, $options);
			},
			'down' => function ($url, $model, $key) {
				$options = array_merge([
					'title' => 'Bajar',
					'aria-label' => 'Bajar',
				]);
				return Html::a('<span class="glyphicon glyphicon-arrow-down"></span>', $url, $options);
			},
			'delete' => function ($url, $model, $key) {
				$options = array_merge([
					'title' => 'Eliminar',
					'aria-label' => 'Eliminar',
				]);
				return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
			},
		],
		'urlCreator' => function($action, $model, $key, $index) {
			$url = [''];
			switch ($action) {
				case 'check':
					$url = ['check-entry', 'id' => $model->id, 'check' => !$model->checked, 'page' => Yii::$app->request->getQueryParam('page', null)];
					break;
				case 'update':
					$url = ['update-entry', 'id' => $model->id];
					break;
				case 'up':
					$url = ['reorder-entry', 'id' => $model->id, 'up' => true, 'page' => Yii::$app->request->getQueryParam('page', null)];
					break;
				case 'down':
					$url = ['reorder-entry', 'id' => $model->id, 'up' => false, 'page' => Yii::$app->request->getQueryParam('page', null)];
					break;
				case 'duplicate':
					$url = ['duplicate-entry', 'id' => $model->id, 'page' => Yii::$app->request->getQueryParam('page', null)];
					break;
				case 'delete':
					$url = ['delete-entry', 'id' => $model->id, 'page' => Yii::$app->request->getQueryParam('page', null)];
					break;
			}
			return Url::to($url);
		},
	];
	
	array_push($columns, $sampleColumn);
	array_push($columns, $actionColumn);
	?>
		
	<?= \kartik\grid\GridView::widget([
		'pjax' => true,
		'pjaxSettings' => [
			'options' => [
				'id' => 'estimate-entries-gridview',
				'enablePushState' => false,
			],
		],
        'dataProvider' => $dataProvider,
        'columns' => $columns,
		'rowOptions' => function ($model, $key, $index, $grid) {
			if ($model->checked) {
				return ['style' => 'font-weight: bold;'];
			}
		},
    ]); ?>

</div>
