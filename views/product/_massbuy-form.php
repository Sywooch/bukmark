<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Massbuy */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="variant-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'quantity')->textInput() ?>
	
	<?= $form->field($model, 'utility_drop')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
