<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Supplier;
use app\models\Category;
use kartik\export\ExportMenu;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SupplierSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Proveedores';
$this->params['breadcrumbs'][] = $this->title;
$categories = Category::find()->all();
?>
<div class="supplier-index">

    <h1><?= Html::encode($this->title) ?></h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
		<?= Html::a('Crear proveedor', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

	<h3>Filtrar por categoría</h3>

	<div class="receipt-form">

		<?php $form = ActiveForm::begin(['method' => 'get']); ?>

		<?= $form->field($searchModel, 'category_id')->dropDownList(ArrayHelper::map($categories, 'id', 'title')) ?>

		<div class="form-group">
			<?= Html::submitButton('Filtrar', ['class' => 'btn btn-primary']) ?>
		</div>

		<?php ActiveForm::end(); ?>

	</div>
	
	<?php
	$columns = [
		[
			'attribute' => 'name',
			'filter' => Html::activeDropDownList($searchModel, 'id', Supplier::getIdNameArray(), ['class' => 'form-control', 'prompt' => 'Nombre']),
		],
		'contactFullName',
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
