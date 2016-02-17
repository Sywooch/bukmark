<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Estimate;
use app\models\Client;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model app\models\Estimate */
/* @var $form yii\widgets\ActiveForm */

$clients = Client::find()->all();
?>

<div class="estiamte-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'client_id')->dropDownList(ArrayHelper::map($clients, 'id', 'name'), ['prompt' => 'Elegir cliente', 'id' => 'client_id']) ?>
	
	<?=
	$form->field($model, 'client_contact_id')->widget(DepDrop::classname(), [
		'data' => $model->client ? Client::getDefaultContactsArray() + $model->client->getContactsArray() : Client::getDefaultContactsArray(),
		'pluginOptions' => [
			'placeholder' => Client::CONTACT_DROPDOWN_PLACEHOLDER,
			'depends' => ['client_id'],
			'url' => Url::to(['/client/get-contacts'])
		]
	]);
	?>
	
	<?= $form->field($model, 'title')->textInput() ?>
	
	<?= $form->field($model, 'status')->dropDownList(Estimate::statusLabels()) ?>

	<?= $form->field($model, 'request_date')->widget(\yii\jui\DatePicker::classname(), ['options' => ['class' => 'form-control']]) ?>

	<?= $form->field($model, 'sent_date')->widget(\yii\jui\DatePicker::classname(), ['options' => ['class' => 'form-control']]) ?>

    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

	<?php ActiveForm::end(); ?>

</div>
