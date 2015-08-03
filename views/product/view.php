<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Está seguro de eliminar este elemento?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            ['label' => 'Categoría', 'value' => $model->category->title],
            ['label' => 'Proveedor', 'value' => $model->supplier->name],
            'supplier_code',
            'bukmark_code',
            ['label' => 'Imagen', 'value' => '@web/images/product/' . $model->image, 'format' => ['image',['height'=>'100']]],
            'description:ntext',
            'price',
            ['label' => 'Moneda', 'value' => $model->currencyLabel],
			'utility',
        ],
    ]) ?>
	
	<h2>Variantes</h2>
	
	<?=
	GridView::widget([
		'dataProvider' => $variantDataProvider,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			'id',
			'description',
			'price',
			'currency',
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{delete}',
				'urlCreator' => function($action, $model, $key, $index) {
					$url = '';
					switch ($action) {
						case 'view':
							$url = 'view-variant';
							break;
						case 'update':
							$url = 'update-variant';
							break;
						case 'delete':
							$url = 'delete-variant';
							break;
					}
					return Url::to([$url, 'id' => $model->id]);
				},
			],
		],
	]);
	?>
	
	<?= Html::a('Agregar variante', ['add-variant', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
	
	<h2>Descuento por cantidad</h2>
	
	<?=
	GridView::widget([
		'dataProvider' => $massbuyDataProvider,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			'id',
			'quantity',
			'utility_drop',
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{delete}',
				'urlCreator' => function($action, $model, $key, $index) {
					$url = '';
					switch ($action) {
						case 'view':
							$url = 'view-massbuy';
							break;
						case 'update':
							$url = 'update-masssbuy';
							break;
						case 'delete':
							$url = 'delete-massbuy';
							break;
					}
					return Url::to([$url, 'id' => $model->id]);
				},
			],
		],
	]);
	?>
	
	<?= Html::a('Agregar descuento', ['add-massbuy', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

</div>
