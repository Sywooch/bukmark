<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Category;
use app\models\Supplier;
use yii\helpers\ArrayHelper;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Productos';
$this->params['breadcrumbs'][] = $this->title;

$categories = Category::find()->all();
$suppliers = Supplier::find()->all();
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
		<?= Html::a('Crear producto', ['create'], ['class' => 'btn btn-success']) ?>
		<?= Html::a('Administrar categorías', ['category/index'], ['class' => 'btn btn-primary']) ?>
    </p>

	<?php
	$columns = [
		[
			'attribute' => 'bukmark_code',
			'label' => 'Cód. interno',
			'options' => ['style' => 'width: 140px;'],
		],
		[
			'label' => 'Categoría',
			'attribute' => 'category.title',
			'filter' => Html::activeDropDownList($searchModel, 'category_id', ArrayHelper::map($categories, 'id', 'title'), ['class' => 'form-control', 'prompt' => 'Categoría']),
		],
		[
			'label' => 'Proveedor',
			'attribute' => 'supplier.name',
			'filter' => Html::activeDropDownList($searchModel, 'supplier_id', ArrayHelper::map($suppliers, 'id', 'name'), ['class' => 'form-control', 'prompt' => 'Proveedor']),
		],
		'title',
		[
			'attribute' => 'supplier_code',
			'label' => 'Cód. proveedor',
			'options' => ['style' => 'width: 140px;'],
		],
	];
	?>

	<?=
	GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => array_merge($columns, [['class' => 'yii\grid\ActionColumn']]),
	]);
	?>

	<?=
	ExportMenu::widget([
		'dataProvider' => $exportDataProvider,
		'target' => ExportMenu::TARGET_SELF,
		'showConfirmAlert' => false,
		'filename' => 'productos',
		'columns' => $columns,
	]);
	?>

</div>
