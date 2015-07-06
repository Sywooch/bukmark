<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Contact */

$this->title = $model->displayName;
$this->params['breadcrumbs'][] = ['label' => 'Suppliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->supplier->name, 'url' => ['view', 'id' => $model->supplier->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_contact-form', [
        'model' => $model,
    ]) ?>

</div>
