<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Currency;
use yii\widgets\Pjax;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\EstimateEntry */
/* @var $form yii\widgets\ActiveForm */

$currencies = Currency::labels();

?>

<div class="estimate-entry-form">

	<?php Pjax::begin(['id' => 'products']) ?>
	<?=
	GridView::widget([
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
				'class' => 'yii\grid\ActionColumn',
				'template' => '{check}',
				'buttons' => [
					'check' => function ($url, $model, $key) {
						$options = array_merge([
							'title' => Yii::t('yii', 'Check'),
							'aria-label' => Yii::t('yii', 'Check'),
							'id' => $model->id,
							'value' => $model->title,
							'onclick' => '$("#estimateentry-product_id").val($(this).attr("id")); $("#product_title").val($(this).attr("value")); return false;',
						]);
						return Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, $options);
					},
				],
			],
		],
	]);
	?>
	<?php Pjax::end() ?>
	
	<?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>
	
	<?= $form->field($model, 'product_id')->hiddenInput() ?>
	
	<div class="form-group">
		
		<?= Html::input('text', 'product_title', $model->product ? $model->product->title : '', ['class' => 'form-control', 'id' => 'product_title', 'disabled' => true]) ?>
	
	</div>
		
	<?= $form->field($model, 'quantity')->textInput() ?>
	
	<?= $form->field($model, 'utility')->textInput(['value' => is_numeric($model->utility) ? Yii::$app->formatter->asDecimal($model->utility, 2) : null]) ?>
	
	<?= $form->field($model, 'price')->textInput(['value' => is_numeric($model->price) ? Yii::$app->formatter->asDecimal($model->price, 2) : null]) ?>
	
	<?= $form->field($model, 'currency')->dropDownList($currencies) ?>
	
	<?= $form->field($model, 'variant_price')->textInput(['value' => is_numeric($model->variant_price) ? Yii::$app->formatter->asDecimal($model->variant_price, 2) : null]) ?>

	<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

	<?php ActiveForm::end(); ?>

</div>
