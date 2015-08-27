<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Estimate;
use yii\widgets\Pjax;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Estimate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="estiamte-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'title')->textInput() ?>

	<?= $form->field($model, 'client_id')->hiddenInput() ?>
	
	<?= Html::input('text', 'client_name', $model->client ? $model->client->name : '', ['class' => 'form-control', 'id' => 'client_name', 'disabled' => true]) ?>
	
	<p>
	
	<?php Pjax::begin(['id' => 'clients']) ?>
	<?=
	GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			'id',
			'name',
			'cuit',
			'delivery_address',
			'address',
			'payment_conditions',
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{check}',
				'buttons' => [
					'check' => function ($url, $model, $key) {
						$options = array_merge([
							'title' => Yii::t('yii', 'Check'),
							'aria-label' => Yii::t('yii', 'Check'),
							'id' => $model->id,
							'value' => $model->name,
							'onclick' => '$("#estimate-client_id").val($(this).attr("id")); $("#client_name").val($(this).attr("value")); return false;',
						]);
						return Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, $options);
					},
				],
			],
		],
	]);
	?>
	<?php Pjax::end() ?>
		
	</p>

	<?= $form->field($model, 'status')->dropDownList(Estimate::statusLabels()) ?>

	<?= $form->field($model, 'request_date')->widget(\yii\jui\DatePicker::classname(), ['options' => ['class' => 'form-control']]) ?>

	<?= $form->field($model, 'sent_date')->widget(\yii\jui\DatePicker::classname(), ['options' => ['class' => 'form-control']]) ?>

    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

	<?php ActiveForm::end(); ?>

</div>
