<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Estimate */

$this->title = 'Editar presupuesto: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Presupuestos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>