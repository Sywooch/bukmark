<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Supplier;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SupplierSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Proveedores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-index">

    <h1><?= Html::encode($this->title) ?></h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
		<?= Html::a('Crear proveedor', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

	<?php
	$columns = [
		[
			'attribute' => 'id',
			'options' => ['style' => 'width: 150px;'],
		],
		[
			'attribute' => 'name',
			'filter' => Html::activeDropDownList($searchModel, 'id', Supplier::getIdNameArray(), ['class' => 'form-control', 'prompt' => 'Nombre']),
		],
		'contactEmail',
		'contactPhone',
		'address',
	];
	?>

	<?=
	GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => array_merge($columns, [
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{website} {view} {update} {delete}',
				'buttons' => [
					'website' => function ($url, $model, $key) {
						if($model->website) {
							$options = array_merge([
								'title' => Yii::t('yii', 'Website'),
								'aria-label' => Yii::t('yii', 'Website'),
								'target' => '_blank',
								'disabled' => true,
							]);
							return Html::a('<span class="glyphicon glyphicon-globe"></span>', $model->website, $options);
						} else {
							return '';
						}
					},
				],
			],
		]),
	]);
	?>

	<?=
	ExportMenu::widget([
		'dataProvider' => $exportDataProvider,
		'target' => ExportMenu::TARGET_SELF,
		'showConfirmAlert' => false,
		'filename' => 'proveedores',
		'columns' => $columns,
	]);
	?>

</div>
