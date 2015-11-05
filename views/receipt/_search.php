<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ReceiptSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="receipt-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'estimate_id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'created_date') ?>

    <?= $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'iva') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
