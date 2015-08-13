<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Massbuy */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="variant-form">

    <?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>
	
	<?= $form->field($model, 'quantity')->textInput() ?>
	
	<?= $form->field($model, 'utility_drop')->textInput(['value' => is_numeric($model->utility_drop) ? Yii::$app->formatter->asDecimal($model->utility_drop, 2) : null]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>