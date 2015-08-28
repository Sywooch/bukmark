<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Receipt;

/* @var $this yii\web\View */
/* @var $model app\models\Receipt */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="receipt-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'type')->textInput()->dropDownList(Receipt::typeLabels()) ?>

    <?= $form->field($model, 'status')->dropDownList(Receipt::statusLabels()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
