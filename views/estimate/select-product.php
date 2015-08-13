<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $estimate app\models\Estimate */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Agregar producto';
$this->params['breadcrumbs'][] = ['label' => 'Presupuestos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Presupuesto ' . $estimate->id, 'url' => ['view', 'id' => $estimate->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="add-product">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            ['label' => 'Categoría', 'attribute' => 'category.title'],
            ['label' => 'Proveedor', 'attribute' => 'supplier.name'],
			'title',
            'supplier_code',
            'bukmark_code',
            [
				'attribute' => 'price',
				'format' => ['decimal', 2],
				'filter' => false,
			],
            [
				'label' => 'Moneda',
				'value' => 'currencyLabel',
			],
			[
				'attribute' => 'utility',
				'format' => ['decimal', 2],
				'filter' => false,
			],
            [
				'class' => 'yii\grid\ActionColumn',
				'template' => '{update}',
				'urlCreator' => function($action, $model, $key, $index) use ($estimate) {
					$url = '';
					switch ($action) {
						case 'update':
							$url = 'select-variant';
							break;
					}
					return Url::to([$url, 'id' => $estimate->id, 'productId' => $model->id]);
				},
			],
        ],
    ]); ?>

</div>
