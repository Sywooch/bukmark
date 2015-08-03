<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Currency;

/* @var $this yii\web\View */
/* @var $model app\models\Variant */
/* @var $form yii\widgets\ActiveForm */

$currencies = Currency::labels();

?>

<div class="variant-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
	
	<?= $form->field($model, 'price')->textInput() ?>

	<?= $form->field($model, 'currency')->dropDownList($currencies) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
