<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Estimate */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Presupuestos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="estimate-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estas seguro de eleminar este elemento?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
			'title',
			[
				'label' => 'Cliente',
				'attribute' => 'client.name',
			],
			[
				'label' => 'Usuario',
				'attribute' => 'user.username',
			],
			[
				'label' => 'Estado',
				'attribute' => 'statusLabel',
			],
			'request_date',
			'sent_date',
            [
				'attribute' => 'total',
				'format' => ['decimal', 2]
			],
			[
				'attribute' => 'cost',
				'format' => ['decimal', 2]
			],
			[
				'attribute' => 'total_checked',
				'format' => ['decimal', 2]
			],
			[
				'attribute' => 'cost_checked',
				'format' => ['decimal', 2]
			],
			[
				'attribute' => 'us',
				'format' => ['decimal', 2]
			],
        ],
    ]) ?>
	
	<h2>Resumen</h2>
	
	<p>
	
	<?= Html::a('Agregar producto', ['create-entry', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
	
	</p>
		
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [	'label' => 'Producto',
				'value' => 'product.title'
			],
			[	'label' => 'Proveedor',
				'value' => 'product.supplier.name'
			],
			'product.supplier_code',
			'product.bukmark_code',
			'description:ntext',
			'quantity',
            [
				'attribute' => 'price',
				'format' => ['decimal', 2],
				'filter' => false,
			],
			[
				'label' => 'Moneda',
				'value' => 'currencyLabel'
			],
			[
				'attribute' => 'variant_price',
				'format' => ['decimal', 2],
				'filter' => false,
			],
			[
				'label' => 'Moneda',
				'value' => 'variantCurrencyLabel'
			],
			[
				'label' => 'Suma',
				'value' => 'cost',
				'format' => ['decimal', 2],
			],
			[
				'attribute' => 'utility',
				'format' => ['decimal', 2],
				'filter' => false,
			],
			[
				'label' => 'Subtotal',
				'value' => 'subtotal',
				'format' => ['decimal', 2],
			],
			[
				'label' => 'Subtotal x cantidad',
				'value' => 'quantitySubtotal',
				'format' => ['decimal', 2],
			],
			[
				'label' => 'Margen de utilidad',
				'value' => 'utilityMargin',
				'format' => ['decimal', 2],
			],
            [
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
			],
        ],
    ]); ?>

</div>
