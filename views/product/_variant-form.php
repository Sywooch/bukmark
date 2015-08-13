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

    <?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>
	
	<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
	
	<?= $form->field($model, 'price')->textInput(['value' => is_numeric($model->price) ? Yii::$app->formatter->asDecimal($model->price, 2) : null]) ?>

	<?= $form->field($model, 'currency')->dropDownList($currencies) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
