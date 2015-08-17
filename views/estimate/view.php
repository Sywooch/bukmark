<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Estimate */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Presupuesto ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Presupuestos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="estimate-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Agregar producto', ['select-product', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
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
	
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            ['label' => 'Producto', 'attribute' => 'product.title'],
            [
				'label' => 'Variante',
				'attribute' => 'variant.description',
				'format' => 'ntext'
			],
			'quantity',
			[
				'attribute' => 'utility',
				'format' => ['decimal', 2],
				'filter' => false,
			],
            [
				'label' => 'Precio producto',
				'attribute' => 'price',
				'format' => ['decimal', 2],
				'filter' => false,
			],
			[
				'label' => 'Precio variante',
				'attribute' => 'variant_price',
				'format' => ['decimal', 2],
				'filter' => false,
			],
			[
				'attribute' => 'checked',
				'format' => 'boolean'
			],
            [
				'class' => 'yii\grid\ActionColumn',
				'template' => '{delete} {update}',
				'urlCreator' => function($action, $model, $key, $index) {
					$url = [''];
					switch ($action) {
						case 'delete':
							$url = ['delete-entry', 'id' => $model->id];
							break;
						case 'update':
							$url = ['check-entry', 'id' => $model->id, 'check' => !$model->checked];
							break;
					}
					return Url::to($url);
				},
			],
        ],
    ]); ?>

</div>
