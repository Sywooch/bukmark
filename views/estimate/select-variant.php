<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $estimate app\models\Estimate */
/* @var $product app\models\Product */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Seleccionar variante';
$this->params['breadcrumbs'][] = ['label' => 'Presupuestos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Presupuesto ' . $estimate->id, 'url' => ['view', 'id' => $estimate->id]];
$this->params['breadcrumbs'][] = ['label' => 'Agregar producto', 'url' => ['select-product', 'id' => $estimate->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="select-variant">

    <h1><?= Html::encode($this->title) ?></h1>
	
	<p>
		<?= Html::a('No agregar variante', ['add-product', 'id' => $estimate->id, 'productId' => $product->id], ['class' => 'btn btn-primary']) ?>
	</p>
		
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
			'description',
			[
				'attribute' => 'price',
				'format' => ['decimal', 2]
			],
			[
				'label' => 'Moneda',
				'value' => 'currencyLabel',
			],
            [
				'class' => 'yii\grid\ActionColumn',
				'template' => '{update}',
				'urlCreator' => function($action, $model, $key, $index) use ($estimate, $product) {
					$url = '';
					switch ($action) {
						case 'update':
							$url = 'add-product';
							break;
					}
					return Url::to([$url, 'id' => $estimate->id, 'productId' => $product->id, 'variantId' => $model->id]);
				},
			],
        ],
    ]); ?>

</div>
