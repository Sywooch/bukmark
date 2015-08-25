<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Client;
use yii\helpers\ArrayHelper;
use app\models\Estimate;

/* @var $this yii\web\View */
/* @var $model app\models\Estimate */
/* @var $form yii\widgets\ActiveForm */

$clients = Client::find()->all();

?>

<div class="product-form">

	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'title')->textInput() ?>
	
	<?= $form->field($model, 'client_id')->dropDownList(ArrayHelper::map($clients, 'id', 'name')) ?>

	<?= $form->field($model, 'status')->dropDownList(Estimate::statusLabels()) ?>
	
	<?= $form->field($model, 'request_date')->widget(\yii\jui\DatePicker::classname(), ['options' => ['class' => 'form-control']]) ?>
	
	<?= $form->field($model, 'sent_date')->widget(\yii\jui\DatePicker::classname(), ['options' => ['class' => 'form-control']]) ?>

    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

	<?php ActiveForm::end(); ?>

</div>